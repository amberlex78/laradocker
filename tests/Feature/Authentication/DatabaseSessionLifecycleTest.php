<?php

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set([
        'session.driver' => 'database',
        'session.lottery' => [0, 100],
    ]);

    resetSessionRuntime();
    $this->withoutMiddleware(PreventRequestForgery::class);
});

test('a successful login persists a real database session linked to its history', function (): void {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('account'));

    $history = $user->loginHistories()->sole();

    expect(config('session.driver'))->toBe('database')
        ->and($history->session_id)->not->toBeNull();

    $this->assertDatabaseHas('sessions', [
        'id' => $history->session_id,
        'user_id' => $user->id,
    ]);
});

test('terminating other database sessions immediately signs the other client out', function (): void {
    $user = User::factory()->create();
    $currentSessionId = str_repeat('a', 40);
    $otherSessionId = str_repeat('b', 40);

    createDatabaseSession($user, $currentSessionId);
    createDatabaseSession($user, $otherSessionId);

    LoginHistory::create([
        'user_id' => $user->id,
        'session_id' => $otherSessionId,
        'logged_in_at' => now()->subHour(),
    ]);

    resetSessionRuntime();

    $this->withCookie(config('session.cookie'), $currentSessionId)
        ->post(route('account.sessions.destroy-other'), [
            'current_password' => 'password',
        ])
        ->assertRedirect(route('account'));

    $this->assertDatabaseHas('sessions', ['id' => $currentSessionId]);
    $this->assertDatabaseMissing('sessions', ['id' => $otherSessionId]);

    resetSessionRuntime();

    $this->withCookie(config('session.cookie'), $otherSessionId)
        ->get(route('account'))
        ->assertRedirect(route('login'));
});

test('changing a password keeps the current database session and rejects the other client', function (): void {
    $user = User::factory()->create();
    $currentSessionId = str_repeat('c', 40);
    $otherSessionId = str_repeat('d', 40);

    createDatabaseSession($user, $currentSessionId);
    createDatabaseSession($user, $otherSessionId);
    resetSessionRuntime();

    $this->from(route('account'))
        ->withCookie(config('session.cookie'), $currentSessionId)
        ->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertRedirect(route('account'));

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
    $this->assertDatabaseHas('sessions', ['id' => $currentSessionId]);
    $this->assertDatabaseMissing('sessions', ['id' => $otherSessionId]);

    resetSessionRuntime();

    $this->withCookie(config('session.cookie'), $currentSessionId)
        ->get(route('account'))
        ->assertOk();

    resetSessionRuntime();

    $this->withCookie(config('session.cookie'), $otherSessionId)
        ->get(route('account'))
        ->assertRedirect(route('login'));
});

function createDatabaseSession(User $user, string $sessionId): void
{
    $handler = new DatabaseSessionHandler(
        DB::connection(config('session.connection')),
        config('session.table'),
        (int) config('session.lifetime'),
        app(),
    );
    $session = new Store(
        config('session.cookie'),
        $handler,
        $sessionId,
        config('session.serialization', 'php'),
    );
    $guard = Auth::guard('web');

    $session->start();
    $session->put($guard->getName(), $user->getAuthIdentifier());
    $session->put('password_hash_web', $guard->hashPasswordForCookie($user->getAuthPassword()));
    $session->save();

    DB::table('sessions')
        ->where('id', $sessionId)
        ->update([
            'user_id' => $user->getKey(),
            'ip_address' => '203.0.113.7',
            'user_agent' => 'Database session integration test',
        ]);
}

function resetSessionRuntime(): void
{
    app('auth')->forgetGuards();
    app('session')->forgetDrivers();
    app()->forgetInstance('session.store');
}
