<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        ->assertSee('action="'.route('user-profile-information.update').'"', false)
        ->assertSee('action="'.route('user-password.update').'"', false)
        ->assertSee('name="current_password"', false)
        ->assertSee('value="Ada Lovelace"', false)
        ->assertSee('value="ada@example.com"', false);
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
        ->put('/user/password', [
            '_token' => csrf_token(),
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertRedirect(route('account'))
        ->assertSessionHasErrorsIn('updatePassword', ['current_password']);

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
