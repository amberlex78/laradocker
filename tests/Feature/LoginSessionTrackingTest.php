<?php

use App\Enums\LoginSessionEndReason;
use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\actingAs;

it('records a login session with a session token on login', function (): void {
    $user = User::factory()->create();

    Auth::login($user);

    $loginSession = LoginSession::query()->whereBelongsTo($user)->sole();

    expect(session()->get(LoginSession::SESSION_KEY))->toBe($loginSession->getKey())
        ->and($loginSession->logged_out_at)->toBeNull();
});

it('closes the login session on logout', function (): void {
    $user = User::factory()->create();

    Auth::login($user);
    Auth::logout();

    $loginSession = LoginSession::query()->whereBelongsTo($user)->sole();

    expect($loginSession->logged_out_at)->not->toBeNull()
        ->and($loginSession->end_reason)->toBe(LoginSessionEndReason::LoggedOut);
});

it('lazily creates a login session for already authenticated requests', function (): void {
    $user = User::factory()->create();

    actingAs($user)->get('/profile')->assertOk();

    expect(LoginSession::query()->whereBelongsTo($user)->count())->toBe(1);
});

it('does not write on every request but refreshes stale activity', function (): void {
    $user = User::factory()->create();

    actingAs($user)->get('/profile')->assertOk();
    $loginSession = LoginSession::query()->whereBelongsTo($user)->sole();
    $loginSession->forceFill(['last_seen_at' => now()->subSeconds(10)])->save();
    $recent = $loginSession->refresh()->last_seen_at;

    $this->get('/profile')->assertOk();

    expect($loginSession->refresh()->last_seen_at->equalTo($recent))->toBeTrue();

    $loginSession->forceFill(['last_seen_at' => now()->subMinutes(5)])->save();

    $this->get('/profile')->assertOk();

    expect($loginSession->refresh()->last_seen_at->gt(now()->subMinute()))->toBeTrue()
        ->and(LoginSession::query()->whereBelongsTo($user)->count())->toBe(1);
});
