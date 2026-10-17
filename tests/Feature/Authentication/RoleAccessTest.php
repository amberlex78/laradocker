<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user roles expose the configured hierarchy values', function () {
    expect(UserRole::Developer->value)->toBe('developer')
        ->and(UserRole::Admin->value)->toBe('admin')
        ->and(UserRole::Operator->value)->toBe('operator')
        ->and(UserRole::User->value)->toBe('user');
});

test('new users are regular users by default', function () {
    $user = User::factory()->create();

    expect($user->role)->toBe(UserRole::User);
});

test('guests are redirected from authenticated areas', function () {
    $this->get('/account')->assertRedirect('/login');
    $this->get('/admin')->assertRedirect('/login');
    $this->get('/developer')->assertRedirect('/login');
});

test('a regular user cannot access privileged areas', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $this->actingAs($user)->get('/account')->assertOk();
    $this->actingAs($user)->get('/admin')->assertForbidden();
    $this->actingAs($user)->get('/developer')->assertForbidden();
});

test('unverified users are redirected to email verification before application areas', function (UserRole $role, string $route): void {
    $user = User::factory()->unverified()->create([
        'role' => $role,
    ]);

    $this->actingAs($user)
        ->get(route($route))
        ->assertRedirect(route('verification.notice'));
})->with([
    'regular user account' => [UserRole::User, 'account'],
    'operator admin area' => [UserRole::Operator, 'admin.dashboard'],
    'admin admin area' => [UserRole::Admin, 'admin.dashboard'],
    'developer admin area' => [UserRole::Developer, 'admin.dashboard'],
    'developer developer area' => [UserRole::Developer, 'developer.dashboard'],
]);

test('an operator can access the admin area but not the developer area', function () {
    $user = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    $this->actingAs($user)->get('/admin')->assertOk();
    $this->actingAs($user)->get('/developer')->assertForbidden();
});

test('an admin can access the admin area but not the developer area', function () {
    $user = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($user)->get('/admin')->assertOk();
    $this->actingAs($user)->get('/developer')->assertForbidden();
});

test('a developer can access both privileged areas', function () {
    $user = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($user)->get('/admin')->assertOk();
    $this->actingAs($user)->get('/developer')->assertOk();
});
