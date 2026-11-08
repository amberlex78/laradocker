<?php

use App\Enums\LoginSessionEndReason;
use App\Enums\LoginSessionStatus;
use App\Filament\Site\Pages\EditProfile;
use App\Livewire\ActiveSessions;
use App\Livewire\LoginHistory;
use App\Models\LoginSession;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('forbids guests from rendering active sessions', function (): void {
    Filament::setCurrentPanel('site');

    Livewire::test(ActiveSessions::class)
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
    ]);
    LoginSession::factory()->for($user)->create([
        'ip_address' => '203.0.113.11',
        'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) '
            .'AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 '
            .'Mobile/15E148 Safari/604.1',
    ]);
    LoginSession::factory()->for($user)->expired()->create(['ip_address' => '203.0.113.12']);
    LoginSession::factory()->for($user)->ended()->create(['ip_address' => '203.0.113.14']);
    LoginSession::factory()->for($otherUser)->create(['ip_address' => '203.0.113.13']);

    Livewire::test(ActiveSessions::class)
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

    Livewire::test(ActiveSessions::class)
        ->assertSee('203.0.113.20')
        ->assertSee('203.0.113.21')
        ->assertDontSee('203.0.113.22');

    Livewire::test(EditProfile::class)
        ->set('terminateSessions.currentPassword', 'panel-password')
        ->call('terminateOtherSessions')
        ->assertNotified('Other sessions terminated');

    expect($currentSession->refresh()->logged_out_at)->toBeNull()
        ->and($otherPanelSession->refresh()->logged_out_at)->not->toBeNull()
        ->and($defaultSession->refresh()->logged_out_at)->toBeNull();
});

it('renders active sessions on the profile page', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    $this->get('/profile')
        ->assertOk()
        ->assertSee('Active sessions')
        ->assertSee('Devices currently signed in to your account.')
        ->assertSee('Current password')
        ->assertSee('Terminate other sessions')
        ->assertSee('wire:submit="terminateOtherSessions"', false);
});

it('does not terminate sessions when the current password is invalid', function (): void {
    $user = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    LoginSession::factory()->for($user)->create(['session_id' => session()->getId()]);
    LoginSession::factory()->for($user)->create();

    Livewire::test(EditProfile::class)
        ->set('terminateSessions.currentPassword', 'wrong-password')
        ->call('terminateOtherSessions')
        ->assertHasErrors(['terminateSessions.currentPassword'])
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

    Livewire::test(EditProfile::class)
        ->set('terminateSessions.currentPassword', 'password')
        ->call('terminateOtherSessions')
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

it('renders login history with all statuses for the authenticated user only', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');

    LoginSession::factory()->for($user)->create([
        'session_id' => session()->getId(),
        'ip_address' => '203.0.113.29',
    ]);
    LoginSession::factory()->for($user)->create(['ip_address' => '203.0.113.30']);
    LoginSession::factory()->for($user)->expired()->create(['ip_address' => '203.0.113.31']);
    LoginSession::factory()->for($user)->ended()->create(['ip_address' => '203.0.113.32']);
    LoginSession::factory()->for($user)->ended()->create([
        'ip_address' => '203.0.113.33',
        'end_reason' => 'terminated',
    ]);
    LoginSession::factory()->for($otherUser)->create(['ip_address' => '203.0.113.34']);

    Livewire::test(LoginHistory::class)
        ->assertSee('203.0.113.29')
        ->assertSee('203.0.113.30')
        ->assertSee('203.0.113.31')
        ->assertSee('203.0.113.32')
        ->assertSee('203.0.113.33')
        ->assertDontSee('203.0.113.34')
        ->assertSee('Current session')
        ->assertSee('Active')
        ->assertSee('Expired')
        ->assertSee('Signed out')
        ->assertSee('Terminated');
});

it('resolves login session status with the documented precedence', function (): void {
    $user = User::factory()->create();
    $now = Carbon::create(2026, 11, 8, 12);
    Carbon::setTestNow($now);
    config(['session.lifetime' => 30]);

    $terminated = LoginSession::factory()->for($user)->create([
        'session_id' => session()->getId(),
        'last_seen_at' => $now->copy()->subMinutes(31),
        'logged_out_at' => $now,
        'end_reason' => LoginSessionEndReason::Terminated,
    ]);
    $signedOut = LoginSession::factory()->for($user)->create([
        'session_id' => session()->getId(),
        'last_seen_at' => $now->copy()->subMinutes(31),
        'logged_out_at' => $now,
        'end_reason' => LoginSessionEndReason::LoggedOut,
    ]);
    $expired = LoginSession::factory()->for($user)->create([
        'session_id' => session()->getId(),
        'last_seen_at' => $now->copy()->subMinutes(31),
    ]);
    $current = LoginSession::factory()->for($user)->create([
        'session_id' => session()->getId(),
    ]);
    $active = LoginSession::factory()->for($user)->create();

    expect($terminated->resolveStatus())->toBe(LoginSessionStatus::Terminated)
        ->and($signedOut->resolveStatus())->toBe(LoginSessionStatus::SignedOut)
        ->and($expired->resolveStatus())->toBe(LoginSessionStatus::Expired)
        ->and($current->resolveStatus())->toBe(LoginSessionStatus::Current)
        ->and($active->resolveStatus())->toBe(LoginSessionStatus::Active)
        ->and(LoginSessionStatus::Current->getColor())->toBe(Color::Green)
        ->and(LoginSessionStatus::Active->getColor())->toBe(Color::Green)
        ->and(LoginSessionStatus::Expired->getColor())->toBe(Color::Amber)
        ->and(LoginSessionStatus::SignedOut->getColor())->toBe(Color::Gray)
        ->and(LoginSessionStatus::Terminated->getColor())->toBe(Color::Red);
});

it('renders the sign-in history section on the profile page', function (): void {
    actingAs(User::factory()->create());

    $this->get('/profile')
        ->assertOk()
        ->assertSee('Sign-in history')
        ->assertSee('Recent successful sign-ins to your account.');
});
