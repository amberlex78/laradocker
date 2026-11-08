<?php

use App\Actions\Auth\EndLoginSession;
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

it('ends only the login session identified by the current session token', function (): void {
    $user = User::factory()->create();
    $currentSession = LoginSession::factory()->for($user)->create();
    $otherSession = LoginSession::factory()->for($user)->create();

    session()->put(LoginSession::SESSION_KEY, $currentSession->getKey());

    app(EndLoginSession::class)->handle();

    expect($currentSession->refresh()->logged_out_at)->not->toBeNull()
        ->and($currentSession->end_reason)->toBe(LoginSessionEndReason::LoggedOut)
        ->and($otherSession->refresh()->logged_out_at)->toBeNull()
        ->and(session()->has(LoginSession::SESSION_KEY))->toBeFalse();
});

it('does not overwrite an already-ended login session', function (): void {
    $user = User::factory()->create();
    $endedAt = now()->subMinute();
    $loginSession = LoginSession::factory()->for($user)->ended()->create([
        'logged_out_at' => $endedAt,
        'end_reason' => LoginSessionEndReason::Terminated,
    ]);
    $originalLoggedOutAt = $loginSession->getRawOriginal('logged_out_at');

    session()->put(LoginSession::SESSION_KEY, $loginSession->getKey());

    app(EndLoginSession::class)->handle();

    expect($loginSession->refresh()->getRawOriginal('logged_out_at'))->toBe($originalLoggedOutAt)
        ->and($loginSession->end_reason)->toBe(LoginSessionEndReason::Terminated)
        ->and(session()->has(LoginSession::SESSION_KEY))->toBeFalse();
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
