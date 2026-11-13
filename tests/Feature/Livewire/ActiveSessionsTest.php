<?php

use App\Enums\LoginSessionEndReason;
use App\Enums\LoginSessionStatus;
use App\Livewire\ActiveSessionsTable;
use App\Livewire\LoginHistoryTable;
use App\Models\LoginSession;
use App\Models\User;
use App\Services\LoginSessionStatusContext;
use App\Services\LoginSessionStatusResolver;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('forbids guests from rendering active sessions', function (): void {
    Filament::setCurrentPanel('site');

    Livewire::test(ActiveSessionsTable::class)
        ->assertForbidden();
});

it('renders active sessions for the authenticated user with device details', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    LoginSession::factory()->for($user)->create([
        'session_id' => session()->getId(),
        'ip_address' => '203.0.113.10',
        'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 '
            .'(KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',
        'client' => 'Chrome 149.0',
        'operating_system' => 'GNU/Linux',
        'device' => 'Desktop',
    ]);
    LoginSession::factory()->for($user)->create([
        'ip_address' => '203.0.113.11',
        'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) '
            .'AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 '
            .'Mobile/15E148 Safari/604.1',
        'client' => 'Safari 17.0',
        'operating_system' => 'iOS',
        'device' => 'Smartphone',
    ]);
    LoginSession::factory()->for($user)->expired()->create(['ip_address' => '203.0.113.12']);
    LoginSession::factory()->for($user)->ended()->create(['ip_address' => '203.0.113.14']);
    LoginSession::factory()->for($otherUser)->create(['ip_address' => '203.0.113.13']);

    Livewire::test(ActiveSessionsTable::class)
        ->assertSee('Chrome 149.0 / GNU/Linux')
        ->assertSee('Desktop')
        ->assertSee('Safari 17.0')
        ->assertSee('203.0.113.10')
        ->assertSee('203.0.113.11')
        ->assertSee('Current')
        ->assertDontSee('203.0.113.12')
        ->assertDontSee('203.0.113.13')
        ->assertDontSee('203.0.113.14');
});

it('uses stored device details instead of parsing user agents in the table', function (): void {
    $user = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    LoginSession::factory()->for($user)->create([
        'session_id' => session()->getId(),
        'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 '
            .'(KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',
        'client' => null,
        'operating_system' => null,
        'device' => null,
    ]);

    Livewire::test(ActiveSessionsTable::class)
        ->assertSee('Unknown / Unknown')
        ->assertSee('Unknown')
        ->assertDontSee('Chrome 149.0 / GNU/Linux');
});

it('uses the locale-specific global date-time format in session tables', function (): void {
    $user = User::factory()->create();
    $displayedAt = Carbon::create(2026, 11, 10, 12, 22);

    $this->travelTo($displayedAt);

    actingAs($user);
    Filament::setCurrentPanel('site');

    $session = LoginSession::factory()->for($user)->ended()->create([
        'session_id' => session()->getId(),
        'logged_in_at' => $displayedAt,
        'logged_out_at' => $displayedAt->copy()->addMinutes(8),
    ]);
    LoginSession::factory()->for($user)->create([
        'last_seen_at' => $displayedAt,
    ]);

    $originalLocale = app()->getLocale();

    try {
        app()->setLocale('en');
        $englishActive = Livewire::test(ActiveSessionsTable::class);
        $englishActiveTable = $englishActive->instance()->getTable();
        $englishHistoryTable = Livewire::test(LoginHistoryTable::class)->instance()->getTable();

        expect($englishActiveTable->getDefaultDateTimeDisplayFormat())
            ->toBe(config('date_formats.date_time.en'))
            ->and($englishHistoryTable->getDefaultDateTimeDisplayFormat())
            ->toBe(config('date_formats.date_time.en'));
        $englishActive->assertSee('Nov 10, 2026 12:22:00');

        $englishDateTimeFormat = $englishActiveTable->getDefaultDateTimeDisplayFormat();

        app()->setLocale('uk');
        $ukrainianActiveTable = Livewire::test(ActiveSessionsTable::class)->instance()->getTable();
        $ukrainianHistory = Livewire::test(LoginHistoryTable::class);

        expect($ukrainianActiveTable->getDefaultDateTimeDisplayFormat())
            ->toBe(config('date_formats.date_time.uk'))
            ->not->toBe($englishDateTimeFormat)
            ->and($ukrainianHistory->instance()->getTable()->getDefaultDateTimeDisplayFormat())
            ->toBe(config('date_formats.date_time.uk'));
        Livewire::test(ActiveSessionsTable::class)
            ->assertSee('10 листопада 2026, 12:22:00');

        $ukrainianHistory
            ->assertCanSeeTableRecords([$session])
            ->assertSee('10 листопада 2026, 12:30:00');
    } finally {
        app()->setLocale($originalLocale);
    }
});

it('makes the terminate other sessions table action available to authenticated users', function (): void {
    actingAs(User::factory()->create());
    Filament::setCurrentPanel('site');

    Livewire::test(ActiveSessionsTable::class)
        ->assertActionExists(TestAction::make('terminateOtherSessions')->table())
        ->assertActionVisible(TestAction::make('terminateOtherSessions')->table())
        ->assertActionHasLabel(TestAction::make('terminateOtherSessions')->table(), 'Terminate other sessions');
});

it('uses the active Filament guard for session display and termination', function (): void {
    config([
        'auth.guards.site_test' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ]);

    $defaultUser = User::factory()->create([
        'password' => Hash::make('default-password'),
    ]);
    $panelUser = User::factory()->create([
        'password' => Hash::make('panel-password'),
    ]);

    actingAs($defaultUser, 'web');
    Filament::getPanel('site')->authGuard('site_test');
    Filament::setCurrentPanel('site');
    Filament::auth()->login($panelUser);

    $currentSession = LoginSession::factory()->for($panelUser)->create([
        'session_id' => session()->getId(),
        'ip_address' => '203.0.113.20',
    ]);
    $otherPanelSession = LoginSession::factory()->for($panelUser)->create(['ip_address' => '203.0.113.21']);
    $defaultSession = LoginSession::factory()->for($defaultUser)->create(['ip_address' => '203.0.113.22']);

    Livewire::test(ActiveSessionsTable::class)
        ->assertSee('203.0.113.20')
        ->assertSee('203.0.113.21')
        ->assertDontSee('203.0.113.22');

    Livewire::test(ActiveSessionsTable::class)
        ->callAction(
            TestAction::make('terminateOtherSessions')->table(),
            data: ['currentPassword' => 'panel-password'],
        )
        ->assertNotified('Other sessions terminated');

    expect($currentSession->refresh()->logged_out_at)->toBeNull()
        ->and($otherPanelSession->refresh()->logged_out_at)->not->toBeNull()
        ->and($defaultSession->refresh()->logged_out_at)->toBeNull();
});

it('renders the active sessions page', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    $this->get('/sessions')
        ->assertOk()
        ->assertSee('Active sessions')
        ->assertSee('Devices currently signed in to your account.')
        ->assertSee('Terminate other sessions')
        ->assertDontSee('wire:submit="terminateOtherSessions"', false);
});

it('does not terminate sessions when the current password is invalid', function (): void {
    $user = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    LoginSession::factory()->for($user)->create(['session_id' => session()->getId()]);
    LoginSession::factory()->for($user)->create();

    Livewire::test(ActiveSessionsTable::class)
        ->callAction(
            TestAction::make('terminateOtherSessions')->table(),
            data: ['currentPassword' => 'wrong-password'],
        )
        ->assertHasErrors()
        ->assertNotNotified();

    expect(LoginSession::query()->whereBelongsTo($user)->whereNull('logged_out_at')->count())->toBe(2);
});

it('terminates other sessions after the current password is confirmed', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    $currentSession = LoginSession::factory()->for($user)->create(['session_id' => session()->getId()]);
    $otherSession = LoginSession::factory()->for($user)->create(['session_id' => 'other-session-id']);
    $otherUserSession = LoginSession::factory()->for($otherUser)->create(['session_id' => 'other-user-session-id']);

    foreach (['other-session-id' => $user, 'other-user-session-id' => $otherUser] as $id => $owner) {
        DB::table(config('session.table'))->insert([
            'id' => $id,
            'user_id' => $owner->getKey(),
            'payload' => '',
            'last_activity' => now()->timestamp,
        ]);
    }

    Livewire::test(ActiveSessionsTable::class)
        ->callAction(
            TestAction::make('terminateOtherSessions')->table(),
            data: ['currentPassword' => 'password'],
        )
        ->assertNotified('Other sessions terminated');

    expect(Auth::check())->toBeTrue()
        ->and(session()->get('password_hash_'.Filament::getAuthGuard()))
        ->toBe(Auth::user()->getAuthPassword())
        ->and($currentSession->refresh()->logged_out_at)->toBeNull()
        ->and($otherSession->refresh()->logged_out_at)->not->toBeNull()
        ->and($otherSession->end_reason->value)->toBe('terminated')
        ->and($otherUserSession->refresh()->logged_out_at)->toBeNull()
        ->and(DB::table(config('session.table'))->where('id', 'other-session-id')->exists())->toBeFalse()
        ->and(DB::table(config('session.table'))->where('id', 'other-user-session-id')->exists())->toBeTrue();

    $this->get('/profile')->assertOk();
});

it('renders only historical login sessions for the authenticated user', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    $currentSession = LoginSession::factory()->for($user)->create([
        'session_id' => session()->getId(),
        'ip_address' => '203.0.113.29',
    ]);
    $activeSession = LoginSession::factory()->for($user)->create(['ip_address' => '203.0.113.30']);
    $expiredSession = LoginSession::factory()->for($user)->expired()->create(['ip_address' => '203.0.113.31']);
    $signedOutSession = LoginSession::factory()->for($user)->ended()->create(['ip_address' => '203.0.113.32']);
    $terminatedSession = LoginSession::factory()->for($user)->ended()->create([
        'ip_address' => '203.0.113.33',
        'end_reason' => 'terminated',
    ]);
    $otherUserSession = LoginSession::factory()->for($otherUser)->ended()->create(['ip_address' => '203.0.113.34']);

    Livewire::test(LoginHistoryTable::class)
        ->assertCanSeeTableRecords([$expiredSession, $signedOutSession, $terminatedSession])
        ->assertCanNotSeeTableRecords([$currentSession, $activeSession, $otherUserSession]);
});

it('filters login history by its computed historical status', function (): void {
    $user = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    $expiredSession = LoginSession::factory()->for($user)->expired()->create();
    $signedOutSession = LoginSession::factory()->for($user)->ended()->create();
    $terminatedSession = LoginSession::factory()->for($user)->ended()->create([
        'end_reason' => 'terminated',
        'logged_out_at' => null,
    ]);

    Livewire::test(LoginHistoryTable::class)
        ->filterTable('status', LoginSessionStatus::Expired)
        ->assertCanSeeTableRecords([$expiredSession])
        ->assertCanNotSeeTableRecords([$signedOutSession, $terminatedSession])
        ->filterTable('status', LoginSessionStatus::SignedOut)
        ->assertCanSeeTableRecords([$signedOutSession])
        ->assertCanNotSeeTableRecords([$expiredSession, $terminatedSession])
        ->filterTable('status', LoginSessionStatus::Terminated)
        ->assertCanSeeTableRecords([$terminatedSession])
        ->assertCanNotSeeTableRecords([$expiredSession, $signedOutSession]);
});

it('filters login history by device and operating system', function (): void {
    $user = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    $desktopSession = LoginSession::factory()->for($user)->ended()->create([
        'device' => 'Desktop',
        'operating_system' => 'GNU/Linux',
    ]);
    $smartphoneSession = LoginSession::factory()->for($user)->ended()->create([
        'device' => 'Smartphone',
        'operating_system' => 'iOS',
    ]);

    Livewire::test(LoginHistoryTable::class)
        ->filterTable('device', 'Desktop')
        ->assertCanSeeTableRecords([$desktopSession])
        ->assertCanNotSeeTableRecords([$smartphoneSession]);

    Livewire::test(LoginHistoryTable::class)
        ->filterTable('operating_system', 'iOS')
        ->assertCanSeeTableRecords([$smartphoneSession])
        ->assertCanNotSeeTableRecords([$desktopSession]);
});

it('builds the device filter options from stored session values', function (): void {
    $user = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    LoginSession::factory()->for($user)->create([
        'device' => 'Phablet',
        'operating_system' => 'Android',
    ]);

    Livewire::test(LoginHistoryTable::class)
        ->assertSee('Phablet');
});

it('sorts login history by its sortable columns', function (): void {
    $user = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    $olderSession = LoginSession::factory()->for($user)->ended()->create([
        'ip_address' => '203.0.113.20',
        'logged_in_at' => now()->subDay(),
    ]);
    $newerSession = LoginSession::factory()->for($user)->ended()->create([
        'ip_address' => '203.0.113.10',
        'logged_in_at' => now()->subHour(),
    ]);

    Livewire::test(LoginHistoryTable::class)
        ->sortTable('logged_in_at', 'asc')
        ->assertCanSeeTableRecords([$olderSession, $newerSession], inOrder: true)
        ->sortTable('ip_address', 'asc')
        ->assertCanSeeTableRecords([$newerSession, $olderSession], inOrder: true);
});

it('sorts login history by the status date after applying a status filter', function (): void {
    $user = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    $olderExpiredSession = LoginSession::factory()->for($user)->expired()->create([
        'logged_in_at' => now()->subDay(),
        'last_seen_at' => now()->subMinutes((int) config('session.lifetime') + 60),
    ]);
    $newerExpiredSession = LoginSession::factory()->for($user)->expired()->create([
        'logged_in_at' => now()->subHours(2),
        'last_seen_at' => now()->subMinutes((int) config('session.lifetime') + 30),
    ]);

    Livewire::test(LoginHistoryTable::class)
        ->filterTable('status', LoginSessionStatus::Expired)
        ->sortTable('status', 'asc')
        ->assertCanSeeTableRecords([$olderExpiredSession, $newerExpiredSession], inOrder: true);
});

it('resolves login session status with the documented precedence', function (): void {
    $user = User::factory()->create();
    $now = Carbon::create(2026, 11, 8, 12);
    $context = new LoginSessionStatusContext(
        currentLoginJournalId: 'current-journal-id',
        currentHttpSessionId: 'current-http-session-id',
        inactivityThreshold: $now->copy()->subMinutes(30),
    );
    $resolver = new LoginSessionStatusResolver;

    $terminated = LoginSession::factory()->for($user)->create([
        'session_id' => 'current-http-session-id',
        'last_seen_at' => $now->copy()->subMinutes(31),
        'logged_out_at' => $now,
        'end_reason' => LoginSessionEndReason::Terminated,
    ]);
    $signedOut = LoginSession::factory()->for($user)->create([
        'session_id' => 'current-http-session-id',
        'last_seen_at' => $now->copy()->subMinutes(31),
        'logged_out_at' => $now,
        'end_reason' => LoginSessionEndReason::LoggedOut,
    ]);
    $expired = LoginSession::factory()->for($user)->create([
        'session_id' => 'current-http-session-id',
        'last_seen_at' => $now->copy()->subMinutes(31),
    ]);
    $current = LoginSession::factory()->for($user)->create([
        'id' => 'current-journal-id',
    ]);
    $active = LoginSession::factory()->for($user)->create();

    expect($resolver->resolveStatus($terminated, $context))->toBe(LoginSessionStatus::Terminated)
        ->and($resolver->resolveStatus($signedOut, $context))->toBe(LoginSessionStatus::SignedOut)
        ->and($resolver->resolveStatus($expired, $context))->toBe(LoginSessionStatus::Expired)
        ->and($resolver->resolveStatus($current, $context))->toBe(LoginSessionStatus::Current)
        ->and($resolver->resolveStatus($active, $context))->toBe(LoginSessionStatus::Active)
        ->and(LoginSessionStatus::Current->getColor())->toBe(Color::Green)
        ->and(LoginSessionStatus::Active->getColor())->toBe(Color::Green)
        ->and(LoginSessionStatus::Expired->getColor())->toBe(Color::Amber)
        ->and(LoginSessionStatus::SignedOut->getColor())->toBe(Color::Gray)
        ->and(LoginSessionStatus::Terminated->getColor())->toBe(Color::Red);
});

it('captures the current request values in the login session status context', function (): void {
    $now = Carbon::create(2026, 11, 8, 12);
    $this->travelTo($now);
    config(['session.lifetime' => 30]);
    session()->put(LoginSession::SESSION_KEY, 12345);

    $context = LoginSessionStatusContext::fromCurrentRequest();

    expect($context->currentLoginJournalId)->toBe('12345')
        ->and($context->currentHttpSessionId)->toBe(session()->getId())
        ->and($context->inactivityThreshold->equalTo($now->copy()->subMinutes(30)))->toBeTrue();
});

it('renders the session history page', function (): void {
    actingAs(User::factory()->create());

    $this->get('/history')
        ->assertOk()
        ->assertSee('Session history')
        ->assertSee('Review your login sessions, including active, signed-out, expired, and terminated sessions.');
});
