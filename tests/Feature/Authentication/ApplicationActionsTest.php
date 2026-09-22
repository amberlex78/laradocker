<?php

use App\Actions\Auth\RegisterUser;
use App\Actions\Auth\ResolveUserLandingRoute;
use App\Actions\Auth\SetUserPassword;
use App\Actions\Auth\UpdateUserProfile;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('registration creates a user with a hashed password', function () {
    $user = app(RegisterUser::class)->handle([
        'name' => 'Regular User',
        'email' => 'user@example.com',
        'password' => 'password',
    ]);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->role)->toBe(UserRole::User)
        ->and(Hash::check('password', $user->password))->toBeTrue();
});

test('profile update clears verification and sends a notification when the email changes', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'old@example.com',
        'email_verified_at' => now(),
    ]);

    app(UpdateUserProfile::class)->handle($user, [
        'name' => 'Updated User',
        'email' => 'new@example.com',
    ]);

    expect($user->fresh()->name)->toBe('Updated User')
        ->and($user->fresh()->email)->toBe('new@example.com')
        ->and($user->fresh()->email_verified_at)->toBeNull();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('password update stores the new password through the user cast', function () {
    $user = User::factory()->create();

    app(SetUserPassword::class)->handle($user, 'new-password');

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue()
        ->and(Hash::check('password', $user->fresh()->password))->toBeFalse();
});

test('landing route resolver returns the named route for each role', function (UserRole $role, string $route): void {
    $user = User::factory()->make(['role' => $role]);

    expect(app(ResolveUserLandingRoute::class)->handle($user))->toBe($route);
})->with([
    'developer' => [UserRole::Developer, 'developer.dashboard'],
    'admin' => [UserRole::Admin, 'admin.dashboard'],
    'operator' => [UserRole::Operator, 'admin.dashboard'],
    'user' => [UserRole::User, 'account'],
]);
