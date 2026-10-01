<?php

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('a guest is redirected to login from account settings', function (): void {
    $this->get(route('account'))
        ->assertRedirect(route('login'));
});

test('a guest cannot update profile information', function (): void {
    $this->put('/user/profile-information', [
        '_token' => csrf_token(),
        'name' => 'Unauthenticated User',
        'email' => 'guest@example.com',
    ])->assertRedirect(route('login'));
});

test('an authenticated user sees profile and password forms on the account page', function (): void {
    $user = User::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    $response = $this->actingAs($user)->get(route('account'));

    $response
        ->assertOk()
        ->assertSee('Profile information')
        ->assertSee('Update your profile information and email address.')
        ->assertSee('id="profile-information"', false)
        ->assertSee('id="update-password"', false)
        ->assertSee('action="'.route('user-profile-information.update').'"', false)
        ->assertSee('action="'.route('user-password.update').'"', false)
        ->assertSee('name="current_password"', false)
        ->assertSee('value="Ada Lovelace"', false)
        ->assertSee('value="ada@example.com"', false);
});

test('the account page groups settings into a wide responsive layout', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12"', false)
        ->assertSee('class="grid gap-6 lg:grid-cols-2 lg:items-start"', false)
        ->assertSeeInOrder([
            'id="profile-information"',
            'id="update-password"',
            'id="latest-login-device"',
            'id="active-sessions"',
            'id="login-history"',
        ], false);
});

test('an authenticated user sees their latest login device on the account page', function (): void {
    $user = User::factory()->create();

    $user->forceFill([
        'last_login_device_type' => 'smartphone',
        'last_login_device_model' => 'iPhone',
        'last_login_os' => 'iOS',
        'last_login_browser' => 'Mobile Safari',
        'last_login_browser_version' => '17.0',
        'last_login_ip_address' => '203.0.113.7',
        'last_login_at' => '2026-09-30 12:34:56',
    ])->save();

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('Latest login device', false)
        ->assertSee('Smartphone', false)
        ->assertSee('iPhone', false)
        ->assertSee('iOS', false)
        ->assertSee('Mobile Safari', false)
        ->assertSee('17.0', false)
        ->assertSee('203.0.113.7', false)
        ->assertSee('September 30, 2026', false);
});

test('an authenticated user sees recent login history in reverse chronological order', function (): void {
    $user = User::factory()->create();

    LoginHistory::create([
        'user_id' => $user->id,
        'logged_in_at' => '2026-09-29 12:34:56',
        'ip_address' => '203.0.113.10',
        'device_type' => 'smartphone',
        'device_model' => 'iPhone',
        'operating_system' => 'iOS',
        'browser' => 'Safari',
        'browser_version' => '17.0',
    ]);
    LoginHistory::create([
        'user_id' => $user->id,
        'logged_in_at' => '2026-09-30 12:34:56',
        'ip_address' => '203.0.113.11',
        'device_type' => 'tablet',
        'device_model' => 'TAB 16',
        'operating_system' => 'Android',
        'browser' => 'Chrome',
        'browser_version' => '130.0',
    ]);

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('Login history', false)
        ->assertSee('203.0.113.11', false)
        ->assertSee('Tablet', false)
        ->assertSee('TAB 16', false)
        ->assertSee('Android', false)
        ->assertSee('Chrome', false)
        ->assertSee('130.0', false)
        ->assertSee('September 30, 2026 12:34 PM', false)
        ->assertSeeInOrder(['203.0.113.11', '203.0.113.10'], false);
});

test('account tables use distinct dark mode states for striped and hoverable rows', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('account'))
        ->assertSee('relative overflow-x-auto rounded-base border border-gray-200 shadow-sm', false)
        ->assertSee('bg-gray-700/50', false)
        ->assertSee('bg-gray-700/70', false)
        ->assertSee('dark:bg-gray-700 dark:text-gray-400', false);
});

test('an account page renders unknown values for incomplete login history', function (): void {
    $user = User::factory()->create();

    LoginHistory::create([
        'user_id' => $user->id,
        'logged_in_at' => '2026-09-30 12:34:56',
    ]);

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('Login history', false)
        ->assertSee('Unknown', false)
        ->assertSee('September 30, 2026 12:34 PM', false);
});

test('an authenticated user sees their active sessions with the current session first', function (): void {
    $this->travelTo('2026-10-01 12:00:00');

    $user = User::factory()->create();
    $this->startSession();
    $currentSessionId = $this->app['session']->getId();

    DB::table('sessions')->insert([
        [
            'id' => $currentSessionId,
            'user_id' => $user->id,
            'ip_address' => '203.0.113.7',
            'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/130.0.0.0 Safari/537.36',
            'payload' => '{}',
            'last_activity' => now()->subMinutes(10)->timestamp,
        ],
        [
            'id' => 'other-active-session',
            'user_id' => $user->id,
            'ip_address' => '203.0.113.8',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Version/17.0 Mobile/15E148 Safari/604.1',
            'payload' => '{}',
            'last_activity' => now()->subMinutes(2)->timestamp,
        ],
        [
            'id' => 'expired-session',
            'user_id' => $user->id,
            'ip_address' => '203.0.113.9',
            'user_agent' => 'Mozilla/5.0 Firefox/130.0',
            'payload' => '{}',
            'last_activity' => now()->subMinutes(121)->timestamp,
        ],
        [
            'id' => 'another-users-session',
            'user_id' => User::factory()->create()->id,
            'ip_address' => '203.0.113.10',
            'user_agent' => 'Mozilla/5.0 Chrome/131.0.0.0',
            'payload' => '{}',
            'last_activity' => now()->subMinute()->timestamp,
        ],
    ]);

    $response = $this->withCookie(config('session.cookie'), $currentSessionId)
        ->actingAs($user)
        ->get(route('account'));

    $response
        ->assertOk()
        ->assertSee('Active sessions', false)
        ->assertSee('Current', false)
        ->assertSee('Desktop', false)
        ->assertSee('Chrome', false)
        ->assertSee('GNU/Linux', false)
        ->assertSee('130.0', false)
        ->assertSee('Smartphone', false)
        ->assertSee('Safari', false)
        ->assertSee('17.0', false)
        ->assertSee('203.0.113.7', false)
        ->assertSee('203.0.113.8', false)
        ->assertSeeInOrder(['203.0.113.7', '203.0.113.8'], false)
        ->assertDontSee('203.0.113.9', false)
        ->assertDontSee('203.0.113.10', false)
        ->assertDontSee($currentSessionId, false);

    $content = $response->getContent();
    $activeSessionsStart = strpos($content, 'id="active-sessions"');
    $loginHistoryStart = strpos($content, 'id="login-history"');
    $activeSessionsMarkup = substr($content, $activeSessionsStart, $loginHistoryStart - $activeSessionsStart);

    expect($activeSessionsMarkup)
        ->toContain('bg-red-700 text-white hover:bg-red-800')
        ->toContain('class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"')
        ->toContain('px-4 py-2.5 text-sm')
        ->toContain('Device')
        ->toContain('IP address')
        ->toContain('Last activity')
        ->not->toContain('Session</th>')
        ->not->toContain('Browser</th>')
        ->not->toContain('class="mb-4 flex justify-end"');
});

test('an active session displays its Unix timestamp in the application timezone', function (): void {
    $user = User::factory()->create();

    DB::table('sessions')->insert([
        'id' => 'kyiv-timezone-session',
        'user_id' => $user->id,
        'ip_address' => '203.0.113.7',
        'user_agent' => 'Mozilla/5.0 Chrome/130.0.0.0',
        'payload' => '{}',
        'last_activity' => 1790845200,
    ]);

    $this->travelTo('2026-10-01 12:00:00 Europe/Kyiv');

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('October 1, 2026 12:00 PM', false);
});

test('an active session displays unknown values when its stored data is incomplete', function (): void {
    $user = User::factory()->create();

    DB::table('sessions')->insert([
        [
            'id' => 'unknown-session',
            'user_id' => $user->id,
            'ip_address' => null,
            'user_agent' => null,
            'payload' => '{}',
            'last_activity' => now()->timestamp,
        ],
    ]);

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('Unknown', false);
});

test('the account page limits the number of active sessions displayed', function (): void {
    $user = User::factory()->create();

    foreach (range(1, 11) as $sessionNumber) {
        DB::table('sessions')->insert([
            'id' => 'limited-session-'.$sessionNumber,
            'user_id' => $user->id,
            'ip_address' => '198.51.100.'.$sessionNumber,
            'user_agent' => 'Mozilla/5.0 Chrome/130.0.0.0',
            'payload' => '{}',
            'last_activity' => now()->subMinutes($sessionNumber)->timestamp,
        ]);
    }

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('198.51.100.1', false)
        ->assertSee('198.51.100.10', false)
        ->assertDontSee('198.51.100.11', false);
});

test('a guest is redirected to login when terminating active sessions', function (): void {
    $this->post(route('account.sessions.destroy-other'), [
        '_token' => csrf_token(),
    ])->assertRedirect(route('login'));
});

test('a user can terminate other sessions while keeping the current session active', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $this->startSession();
    $currentSessionId = $this->app['session']->getId();

    DB::table('sessions')->insert([
        [
            'id' => $currentSessionId,
            'user_id' => $user->id,
            'ip_address' => '203.0.113.7',
            'user_agent' => 'Mozilla/5.0 Chrome/130.0.0.0',
            'payload' => '{}',
            'last_activity' => now()->timestamp,
        ],
        [
            'id' => 'user-other-session',
            'user_id' => $user->id,
            'ip_address' => '203.0.113.8',
            'user_agent' => 'Mozilla/5.0 Safari/17.0',
            'payload' => '{}',
            'last_activity' => now()->timestamp,
        ],
        [
            'id' => 'other-users-session',
            'user_id' => $otherUser->id,
            'ip_address' => '203.0.113.9',
            'user_agent' => 'Mozilla/5.0 Firefox/130.0',
            'payload' => '{}',
            'last_activity' => now()->timestamp,
        ],
    ]);

    $response = $this->withCookie(config('session.cookie'), $currentSessionId)
        ->actingAs($user)
        ->post(route('account.sessions.destroy-other'), [
            '_token' => csrf_token(),
            'session_id' => 'other-users-session',
        ]);

    $response
        ->assertRedirect(route('account'))
        ->assertSessionHas('status', 'active-sessions-terminated');

    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseHas('sessions', ['id' => $currentSessionId, 'user_id' => $user->id]);
    $this->assertDatabaseMissing('sessions', ['id' => 'user-other-session']);
    $this->assertDatabaseHas('sessions', ['id' => 'other-users-session', 'user_id' => $otherUser->id]);

    $this->get(route('account'))
        ->assertOk()
        ->assertSee('203.0.113.7', false)
        ->assertDontSee('203.0.113.8', false)
        ->assertDontSee('203.0.113.9', false);
});

test('account forms use standard Flowbite fields and server-side submission', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('<form', false)
        ->assertSee('type="email"', false)
        ->assertSee('type="password"', false)
        ->assertSee('focus:ring-blue-500', false)
        ->assertSee('focus:border-blue-500', false)
        ->assertDontSee('novalidate', false)
        ->assertDontSee('formValidation', false)
        ->assertDontSee('data-validation-field', false)
        ->assertDontSee('data-validation-confirm', false)
        ->assertDontSee('x-auth.field', false)
        ->assertDontSee('x-auth.password-field', false);
});

test('profile validation errors are shown in the account alert', function (): void {
    $user = User::factory()->create();

    $this->from(route('account'))
        ->actingAs($user)
        ->put('/user/profile-information', [
            '_token' => csrf_token(),
            'name' => '',
            'email' => 'not-an-email',
        ])
        ->assertRedirect(route('account'));

    $this->get(route('account'))
        ->assertSee('We could not update your profile information.', false)
        ->assertSee('The name field is required.', false)
        ->assertSee('The email field must be a valid email address.', false);
});

test('a user can update profile information through the account page', function (): void {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'old@example.com',
        'email_verified_at' => now(),
    ]);

    $response = $this->from(route('account'))
        ->actingAs($user)
        ->put('/user/profile-information', [
            '_token' => csrf_token(),
            'name' => 'Updated User',
            'email' => 'new@example.com',
        ]);

    $response->assertRedirect(route('account'));

    expect($user->fresh()->name)->toBe('Updated User')
        ->and($user->fresh()->email)->toBe('new@example.com')
        ->and($user->fresh()->email_verified_at)->toBeNull();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('a password update rejects an incorrect current password', function (): void {
    $user = User::factory()->create();
    $originalPasswordHash = $user->password;

    $response = $this->from(route('account'))
        ->actingAs($user)
        ->followingRedirects()
        ->put('/user/password', [
            '_token' => csrf_token(),
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertOk()
        ->assertSee('We could not update your password.', false)
        ->assertSee('The provided password does not match your current password.', false);

    expect($user->fresh()->password)->toBe($originalPasswordHash);
});

test('a user can update their password with the correct current password', function (): void {
    $user = User::factory()->create();

    $response = $this->from(route('account'))
        ->actingAs($user)
        ->put('/user/password', [
            '_token' => csrf_token(),
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response->assertRedirect(route('account'));

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});
