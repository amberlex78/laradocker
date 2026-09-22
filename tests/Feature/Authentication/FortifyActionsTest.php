<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

uses(RefreshDatabase::class);

test('profile updates validate and update the profile through the Fortify contract', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'old@example.com',
        'email_verified_at' => now(),
    ]);

    app(UpdatesUserProfileInformation::class)->update($user, [
        'name' => 'Updated User',
        'email' => 'new@example.com',
    ]);

    expect($user->fresh()->name)->toBe('Updated User')
        ->and($user->fresh()->email)->toBe('new@example.com')
        ->and($user->fresh()->email_verified_at)->toBeNull();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('authenticated password updates validate the current password', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    app(UpdatesUserPasswords::class)->update($user, [
        'current_password' => 'password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

test('password reset stores a validated new password', function () {
    $user = User::factory()->create();

    app(ResetsUserPasswords::class)->reset($user, [
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});
