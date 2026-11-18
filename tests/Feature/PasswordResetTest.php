<?php

use App\Enums\LoginSessionEndReason;
use App\Models\LoginSession;
use App\Models\User;
use Filament\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;

it('sends a password reset link without revealing whether the account exists', function (): void {
    Notification::fake();
    $user = User::factory()->create(['email' => 'ada@example.com']);
    Filament::setCurrentPanel('site');
    $page = Filament::getCurrentPanel()->getRequestPasswordResetRouteAction();

    Livewire::test($page)
        ->fillForm(['email' => $user->email])
        ->call('request')
        ->assertNotified(__(Password::RESET_LINK_SENT));

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

it('shows the same password reset response for an unknown email address', function (): void {
    Notification::fake();
    Filament::setCurrentPanel('site');
    $page = Filament::getCurrentPanel()->getRequestPasswordResetRouteAction();

    Livewire::test($page)
        ->fillForm(['email' => 'missing@example.com'])
        ->call('request')
        ->assertNotified(__(Password::RESET_LINK_SENT));

    Notification::assertNothingSent();
});

it('resets the password and terminates all existing sessions', function (): void {
    config()->set('session.driver', 'database');

    $user = User::factory()->create(['password' => 'old-password']);
    $otherUser = User::factory()->create();
    $loginSession = LoginSession::factory()->for($user)->create(['session_id' => 'user-session']);
    $otherLoginSession = LoginSession::factory()->for($otherUser)->create(['session_id' => 'other-session']);
    insertDatabaseSession('user-session', $user);
    insertDatabaseSession('other-session', $otherUser);
    $token = Password::broker()->createToken($user);
    Filament::setCurrentPanel('site');
    $page = Filament::getCurrentPanel()->getResetPasswordRouteAction();

    Livewire::test($page, ['email' => $user->email, 'token' => $token])
        ->fillForm([
            'password' => 'NewPassword123!',
            'passwordConfirmation' => 'NewPassword123!',
        ])
        ->call('resetPassword')
        ->assertHasNoFormErrors()
        ->assertRedirect('/login');

    expect(Hash::check('NewPassword123!', $user->refresh()->password))->toBeTrue()
        ->and($loginSession->refresh()->end_reason)->toBe(LoginSessionEndReason::Terminated)
        ->and($loginSession->logged_out_at)->not->toBeNull()
        ->and($otherLoginSession->refresh()->logged_out_at)->toBeNull()
        ->and(DB::table('sessions')->where('id', 'user-session')->exists())->toBeFalse()
        ->and(DB::table('sessions')->where('id', 'other-session')->exists())->toBeTrue();
});

it('rejects an invalid password reset token without terminating sessions', function (): void {
    $user = User::factory()->create(['password' => 'old-password']);
    $loginSession = LoginSession::factory()->for($user)->create(['session_id' => 'user-session']);
    insertDatabaseSession('user-session', $user);
    Filament::setCurrentPanel('site');
    $page = Filament::getCurrentPanel()->getResetPasswordRouteAction();

    Livewire::test($page, ['email' => $user->email, 'token' => 'invalid-token'])
        ->fillForm([
            'password' => 'NewPassword123!',
            'passwordConfirmation' => 'NewPassword123!',
        ])
        ->call('resetPassword')
        ->assertNotified(__(Password::INVALID_TOKEN))
        ->assertNoRedirect();

    expect(Hash::check('old-password', $user->refresh()->password))->toBeTrue()
        ->and($loginSession->refresh()->logged_out_at)->toBeNull()
        ->and(DB::table('sessions')->where('id', 'user-session')->exists())->toBeTrue();
});

it('rejects an expired password reset token', function (): void {
    $user = User::factory()->create(['password' => 'old-password']);
    $token = Password::broker()->createToken($user);
    Filament::setCurrentPanel('site');
    $page = Filament::getCurrentPanel()->getResetPasswordRouteAction();
    $this->travel(61)->minutes();

    Livewire::test($page, ['email' => $user->email, 'token' => $token])
        ->fillForm([
            'password' => 'NewPassword123!',
            'passwordConfirmation' => 'NewPassword123!',
        ])
        ->call('resetPassword')
        ->assertNotified(__(Password::INVALID_TOKEN))
        ->assertNoRedirect();

    expect(Hash::check('old-password', $user->refresh()->password))->toBeTrue();
});

function insertDatabaseSession(string $sessionId, User $user): void
{
    DB::table('sessions')->insert([
        'id' => $sessionId,
        'user_id' => $user->getKey(),
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Pest',
        'payload' => base64_encode(serialize([])),
        'last_activity' => now()->timestamp,
    ]);
}
