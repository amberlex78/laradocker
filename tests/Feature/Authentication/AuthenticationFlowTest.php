<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

test('a visitor can register as a regular user', function () {
    $response = $this->post('/register', [
        '_token' => csrf_token(),
        'name' => 'Regular User',
        'email' => 'user@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/account');

    expect(User::query()->where('email', 'user@example.com')->firstOrFail()->role)
        ->toBe(UserRole::User);
});

test('a developer is redirected to the developer area after login', function () {
    $user = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    $response = $this->post('/login', [
        '_token' => csrf_token(),
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/developer');
});

test('an admin is redirected to the admin area after login', function () {
    $user = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $response = $this->post('/login', [
        '_token' => csrf_token(),
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/admin');
});

test('an operator is redirected to the admin area after login', function () {
    $user = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    $response = $this->post('/login', [
        '_token' => csrf_token(),
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/admin');
});

test('a regular user is redirected to the account area after login', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $response = $this->post('/login', [
        '_token' => csrf_token(),
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/account');
});

test('an authenticated user can log out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout', [
        '_token' => csrf_token(),
    ]);

    $response->assertRedirect('/');
    $this->assertGuest();
});
