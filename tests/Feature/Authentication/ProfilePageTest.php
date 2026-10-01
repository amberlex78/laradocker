<?php

use App\Enums\LogoutReason;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('a guest is redirected to login from account settings', function (): void {
    $this->get(route('account'))
        ->assertRedirect(route('login'));
});

test('an authenticated session is rejected after the stored password hash changes', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['password_hash_web' => 'stale-password-hash'])
        ->get(route('account'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
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
        ->assertSee('id="terminate_sessions_current_password"', false)
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
            'id="active-sessions"',
            'id="login-history"',
        ], false)
        ->assertDontSee('id="latest-login-device"', false);
});

test('the account page keeps login device details in the login history', function (): void {
    $user = User::factory()->create();

    LoginHistory::create([
        'user_id' => $user->id,
        'device_type' => 'smartphone',
        'device_model' => 'iPhone',
        'operating_system' => 'iOS',
        'browser' => 'Mobile Safari',
        'browser_version' => '17.0',
        'ip_address' => '203.0.113.7',
        'logged_in_at' => '2026-09-30 12:34:56',
    ]);

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertDontSee('Latest login device', false)
        ->assertSee('Login history', false)
        ->assertSee('Smartphone', false)
        ->assertSee('iPhone', false)
        ->assertSee('iOS', false)
        ->assertSee('Mobile Safari', false)
        ->assertSee('17.0', false)
        ->assertSee('203.0.113.7', false)
        ->assertSee('2026-09-30 12:34', false);
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
        ->assertSee('2026-09-30 12:34', false)
        ->assertSeeInOrder(['203.0.113.11', '203.0.113.10'], false);
});

test('login history shows separate login and logout columns with the compact date format', function (): void {
    $user = User::factory()->create();

    LoginHistory::create([
        'user_id' => $user->id,
        'logged_in_at' => '2026-10-01 07:06:00',
        'ip_address' => '203.0.113.11',
        'device_type' => 'smartphone',
    ]);

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('Login time', false)
        ->assertSee('Logout time', false)
        ->assertSee('2026-10-01 07:06', false)
        ->assertSee('Not recorded', false);
});

test('login history distinguishes active, explicitly ended, and unrecorded sessions', function (): void {
    $this->travelTo('2026-10-01 07:06:00');

    $user = User::factory()->create();

    DB::table('sessions')->insert([
        'id' => 'active-login-session',
        'user_id' => $user->id,
        'ip_address' => '203.0.113.11',
        'user_agent' => 'Mozilla/5.0 Chrome/130.0.0.0',
        'payload' => '{}',
        'last_activity' => now()->timestamp,
    ]);

    LoginHistory::create([
        'user_id' => $user->id,
        'session_id' => 'active-login-session',
        'logged_in_at' => '2026-10-01 07:06:00',
        'ip_address' => '203.0.113.11',
        'device_type' => 'desktop',
        'operating_system' => 'GNU/Linux',
        'browser' => 'Chrome',
        'browser_version' => '149.0',
    ]);
    LoginHistory::create([
        'user_id' => $user->id,
        'session_id' => 'ended-login-session',
        'logged_in_at' => '2026-10-01 06:00:00',
        'logged_out_at' => '2026-10-01 07:05:00',
        'logout_reason' => LogoutReason::Terminated,
        'ip_address' => '203.0.113.12',
        'device_type' => 'smartphone',
    ]);
    LoginHistory::create([
        'user_id' => $user->id,
        'session_id' => 'expired-login-session',
        'logged_in_at' => '2026-09-30 23:00:00',
        'ip_address' => '203.0.113.13',
        'device_type' => 'tablet',
    ]);

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('Chrome 149.0 / GNU/Linux', false)
        ->assertSeeInOrder(['Chrome', '149.0', 'GNU/Linux', 'Desktop'], false)
        ->assertSee('Active', false)
        ->assertSee('bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300', false)
        ->assertSee('2026-10-01 07:05', false)
        ->assertSee('Terminated', false)
        ->assertSee('Not recorded', false);
});

test('login history identifies active sessions beyond the ten-row display limit', function (): void {
    $user = User::factory()->create();

    foreach (range(1, 11) as $sessionNumber) {
        DB::table('sessions')->insert([
            'id' => 'status-session-'.$sessionNumber,
            'user_id' => $user->id,
            'ip_address' => '198.51.100.'.$sessionNumber,
            'user_agent' => 'Mozilla/5.0 Chrome/130.0.0.0',
            'payload' => '{}',
            'last_activity' => now()->subMinutes($sessionNumber)->timestamp,
        ]);
    }

    LoginHistory::create([
        'user_id' => $user->id,
        'session_id' => 'status-session-11',
        'logged_in_at' => now(),
        'ip_address' => '198.51.100.11',
        'device_type' => 'desktop',
    ]);

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('Active', false)
        ->assertDontSee('Not recorded', false);
});

test('login history uses its id as a stable newest-first tie breaker', function (): void {
    $user = User::factory()->create();
    $loggedInAt = now()->subHour();

    LoginHistory::create([
        'user_id' => $user->id,
        'logged_in_at' => $loggedInAt,
        'ip_address' => '198.51.100.10',
    ]);
    LoginHistory::create([
        'user_id' => $user->id,
        'logged_in_at' => $loggedInAt,
        'ip_address' => '198.51.100.11',
    ]);

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSeeInOrder(['198.51.100.11', '198.51.100.10'], false);
});

test('login history marks the current session separately from other active sessions', function (): void {
    $user = User::factory()->create();

    $this->post('/login', [
        '_token' => csrf_token(),
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('account'));

    $currentHistory = $user->loginHistories()->sole();
    $currentSessionId = $currentHistory->session_id;

    DB::table('sessions')->updateOrInsert([
        'id' => $currentSessionId,
    ], [
        'user_id' => $user->id,
        'ip_address' => '203.0.113.11',
        'user_agent' => 'Mozilla/5.0 Chrome/130.0.0.0',
        'payload' => '{}',
        'last_activity' => now()->timestamp,
    ]);
    DB::table('sessions')->insert([
        'id' => 'other-active-session',
        'user_id' => $user->id,
        'ip_address' => '203.0.113.12',
        'user_agent' => 'Mozilla/5.0 Chrome/130.0.0.0',
        'payload' => '{}',
        'last_activity' => now()->timestamp,
    ]);

    $currentHistory->forceFill([
        'ip_address' => '203.0.113.11',
        'device_type' => 'desktop',
        'browser' => 'Chrome',
        'browser_version' => '130.0',
    ])->save();
    LoginHistory::create([
        'user_id' => $user->id,
        'session_id' => 'other-active-session',
        'logged_in_at' => now()->subMinute(),
        'ip_address' => '203.0.113.12',
        'device_type' => 'smartphone',
        'browser' => 'Chrome Mobile',
        'browser_version' => '130.0',
    ]);

    $response = $this->get(route('account'));

    expect(preg_match_all('/<span[^>]*>\s*Current\s*<\/span>/', $response->getContent()))
        ->toBe(1)
        ->and(preg_match_all('/<span[^>]*>\s*Active\s*<\/span>/', $response->getContent()))
        ->toBe(1);
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
        ->assertSee('2026-09-30 12:34', false);
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
        ->toContain('class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"')
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
        ->assertSee('2026-10-01 12:00', false);
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

test('a user must confirm their current password before terminating other sessions', function (): void {
    $user = User::factory()->create();
    $this->startSession();
    $currentSessionId = $this->app['session']->getId();

    DB::table('sessions')->insert([
        'id' => 'protected-other-session',
        'user_id' => $user->id,
        'ip_address' => '203.0.113.8',
        'user_agent' => 'Mozilla/5.0 Safari/17.0',
        'payload' => '{}',
        'last_activity' => now()->timestamp,
    ]);

    $this->from(route('account'))
        ->withCookie(config('session.cookie'), $currentSessionId)
        ->actingAs($user)
        ->post(route('account.sessions.destroy-other'), [
            '_token' => csrf_token(),
            'current_password' => 'wrong-password',
        ])
        ->assertRedirect(route('account'))
        ->assertSessionHasErrorsIn('terminateSessions', 'current_password');

    $this->assertDatabaseHas('sessions', [
        'id' => 'protected-other-session',
        'user_id' => $user->id,
    ]);
});

test('failed session revocation rolls back the password rehash used to terminate sessions', function (): void {
    $user = User::factory()->create();
    $originalPasswordHash = $user->password;

    config()->set('session.table', 'missing_sessions_table');
    $this->withoutExceptionHandling();

    expect(fn () => $this->actingAs($user)
        ->post(route('account.sessions.destroy-other'), [
            '_token' => csrf_token(),
            'current_password' => 'password',
        ]))->toThrow(QueryException::class);

    expect($user->fresh()->password)->toBe($originalPasswordHash);
});

test('a user can terminate other sessions while keeping the current session active', function (): void {
    $user = User::factory()->create();
    $passwordHashBeforeTermination = $user->password;
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
            'current_password' => 'password',
            'session_id' => 'other-users-session',
        ]);

    $response
        ->assertRedirect(route('account'))
        ->assertSessionHas('status', 'active-sessions-terminated');

    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseHas('sessions', ['id' => $currentSessionId, 'user_id' => $user->id]);
    $this->assertDatabaseMissing('sessions', ['id' => 'user-other-session']);
    $this->assertDatabaseHas('sessions', ['id' => 'other-users-session', 'user_id' => $otherUser->id]);

    expect($user->fresh()->password)->not->toBe($passwordHashBeforeTermination)
        ->and(Hash::check('password', $user->fresh()->password))->toBeTrue();

    $this->get(route('account'))
        ->assertOk()
        ->assertSee('203.0.113.7', false)
        ->assertDontSee('203.0.113.8', false)
        ->assertDontSee('203.0.113.9', false);
});

test('terminating other sessions records their logout time in login history', function (): void {
    $this->travelTo('2026-10-01 07:06:00');

    $user = User::factory()->create();
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
    ]);

    LoginHistory::create([
        'user_id' => $user->id,
        'session_id' => 'user-other-session',
        'logged_in_at' => now()->subHour(),
        'ip_address' => '203.0.113.8',
        'device_type' => 'tablet',
    ]);

    $this->withCookie(config('session.cookie'), $currentSessionId)
        ->actingAs($user)
        ->post(route('account.sessions.destroy-other'), [
            '_token' => csrf_token(),
            'current_password' => 'password',
        ])
        ->assertRedirect(route('account'));

    expect($user->loginHistories()->sole()->logged_out_at?->format('Y-m-d H:i'))
        ->toBe('2026-10-01 07:06')
        ->and($user->loginHistories()->sole()->logout_reason)->toBe(LogoutReason::Terminated);

    $this->get(route('account'))
        ->assertOk()
        ->assertSee('2026-10-01 07:06', false)
        ->assertDontSee('Not recorded', false);
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

test('failed session revocation rolls back a password update', function (): void {
    $user = User::factory()->create();
    $originalPasswordHash = $user->password;

    config()->set('session.table', 'missing_sessions_table');
    $this->withoutExceptionHandling();

    expect(fn () => $this->from(route('account'))
        ->actingAs($user)
        ->put('/user/password', [
            '_token' => csrf_token(),
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]))->toThrow(QueryException::class);

    expect($user->fresh()->password)->toBe($originalPasswordHash);
});
