<?php

use App\Filament\Site\Pages\EditProfile;
use App\Livewire\ActiveSessions;
use App\Models\User;
use App\Models\UserSession;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('forbids guests from rendering active sessions', function (): void {
    Filament::setCurrentPanel('site');

    Livewire::test(ActiveSessions::class)
        ->assertForbidden();
});

it('uses configured session storage metadata', function (): void {
    config([
        'session.table' => 'custom_sessions',
        'session.connection' => 'custom_connection',
    ]);

    $session = new UserSession;

    expect($session->getTable())->toBe('custom_sessions')
        ->and($session->getConnectionName())->toBe('custom_connection');
});

it('renders active sessions for the authenticated user with device details', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');
    $currentSessionId = session()->getId();

    UserSession::create([
        'id' => $currentSessionId,
        'user_id' => $user->getKey(),
        'ip_address' => '203.0.113.10',
        'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 '
            .'(KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);
    UserSession::create([
        'id' => 'other-active-session-id',
        'user_id' => $user->getKey(),
        'ip_address' => '203.0.113.11',
        'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) '
            .'AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 '
            .'Mobile/15E148 Safari/604.1',
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);
    UserSession::create([
        'id' => 'stale-session-id',
        'user_id' => $user->getKey(),
        'ip_address' => '203.0.113.12',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '
            .'AppleWebKit/537.36 (KHTML, like Gecko) Firefox/140.0 Safari/537.36',
        'payload' => '',
        'last_activity' => now()->subMinutes((int) config('session.lifetime') + 1)->timestamp,
    ]);
    UserSession::create([
        'id' => 'other-user-session-id',
        'user_id' => $otherUser->getKey(),
        'ip_address' => '203.0.113.13',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '
            .'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);

    Livewire::test(ActiveSessions::class)
        ->assertSee('Chrome 149.0 / GNU/Linux')
        ->assertSee('Desktop')
        ->assertSee('Safari 17.0')
        ->assertSee('203.0.113.10')
        ->assertSee('203.0.113.11')
        ->assertSee('Current')
        ->assertDontSee('203.0.113.12')
        ->assertDontSee('203.0.113.13');
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
    $currentSessionId = session()->getId();

    $currentSession = UserSession::create([
        'id' => $currentSessionId,
        'user_id' => $panelUser->getKey(),
        'ip_address' => '203.0.113.20',
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);
    $otherPanelSession = UserSession::create([
        'id' => 'other-panel-session-id',
        'user_id' => $panelUser->getKey(),
        'ip_address' => '203.0.113.21',
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);
    $defaultSession = UserSession::create([
        'id' => 'default-guard-session-id',
        'user_id' => $defaultUser->getKey(),
        'ip_address' => '203.0.113.22',
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);

    Livewire::test(ActiveSessions::class)
        ->assertSee('203.0.113.20')
        ->assertSee('203.0.113.21')
        ->assertDontSee('203.0.113.22');

    Livewire::test(EditProfile::class)
        ->set('terminateSessions.currentPassword', 'panel-password')
        ->call('terminateOtherSessions')
        ->assertNotified('Other sessions terminated');

    expect(UserSession::query()->find($currentSession->getKey()))->not->toBeNull()
        ->and(UserSession::query()->find($otherPanelSession->getKey()))->toBeNull()
        ->and(UserSession::query()->find($defaultSession->getKey()))->not->toBeNull();
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
    $currentSessionId = session()->getId();

    UserSession::create([
        'id' => $currentSessionId,
        'user_id' => $user->getKey(),
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);
    UserSession::create([
        'id' => 'other-session-id',
        'user_id' => $user->getKey(),
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);

    Livewire::test(EditProfile::class)
        ->set('terminateSessions.currentPassword', 'wrong-password')
        ->call('terminateOtherSessions')
        ->assertHasErrors(['terminateSessions.currentPassword'])
        ->assertNotNotified();

    expect(UserSession::query()->whereBelongsTo($user)->count())->toBe(2);
});

it('terminates other sessions after the current password is confirmed', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    actingAs($user);
    Filament::setCurrentPanel('site');
    $currentSessionId = session()->getId();

    $currentSession = UserSession::create([
        'id' => $currentSessionId,
        'user_id' => $user->getKey(),
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);
    $otherSession = UserSession::create([
        'id' => 'other-session-id',
        'user_id' => $user->getKey(),
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);
    $otherUserSession = UserSession::create([
        'id' => 'other-user-session-id',
        'user_id' => $otherUser->getKey(),
        'payload' => '',
        'last_activity' => now()->timestamp,
    ]);

    Livewire::test(EditProfile::class)
        ->set('terminateSessions.currentPassword', 'password')
        ->call('terminateOtherSessions')
        ->assertNotified('Other sessions terminated');

    expect(Auth::check())->toBeTrue()
        ->and(session()->get('password_hash_'.Filament::getAuthGuard()))
        ->toBe(Auth::user()->getAuthPassword())
        ->and(UserSession::query()->find($currentSession->getKey()))->not->toBeNull()
        ->and(UserSession::query()->find($otherSession->getKey()))->toBeNull()
        ->and(UserSession::query()->find($otherUserSession->getKey()))->not->toBeNull();

    $this->get('/profile')->assertOk();
});
