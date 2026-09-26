<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

test('guests are redirected from the admin user index', function (): void {
    $this->get(route('admin.users.index'))->assertRedirect('/login');
});

test('operators and regular users are forbidden from the admin user index', function (UserRole $role): void {
    $actor = User::factory()->create(['role' => $role]);

    $this->actingAs($actor)
        ->get(route('admin.users.index'))
        ->assertForbidden();
})->with([
    'operator' => UserRole::Operator,
    'user' => UserRole::User,
]);

test('the admin index excludes developers while showing business users', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $businessUser = User::factory()->create([
        'name' => 'Business User',
        'email' => 'business@example.com',
        'role' => UserRole::User,
    ]);
    $developer = User::factory()->create([
        'name' => 'Technical Developer',
        'email' => 'developer-only@example.com',
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee($businessUser->email)
        ->assertDontSee($developer->email);
});

test('an admin can create every business role', function (UserRole $role): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => "Created {$role->value}",
        'email' => "{$role->value}-created@example.com",
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => $role->value,
    ]);

    $response->assertRedirect(route('admin.users.index'));

    $createdUser = User::where('email', "{$role->value}-created@example.com")->firstOrFail();

    expect($createdUser->role)->toBe($role);
})->with([
    'admin' => UserRole::Admin,
    'operator' => UserRole::Operator,
    'user' => UserRole::User,
]);

test('an admin can update an allowed user profile', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $target = User::factory()->create(['role' => UserRole::User]);

    $response = $this->actingAs($admin)->put(route('admin.users.update', $target), [
        'name' => 'Updated Business User',
        'email' => 'updated-business@example.com',
        'role' => UserRole::Operator->value,
        'password' => '',
        'password_confirmation' => '',
    ]);

    $response->assertRedirect(route('admin.users.index'));

    expect($target->fresh()->name)->toBe('Updated Business User')
        ->and($target->fresh()->email)->toBe('updated-business@example.com')
        ->and($target->fresh()->role)->toBe(UserRole::Operator);
});

test('an admin cannot demote an existing administrator', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $target = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->put(route('admin.users.update', $target), [
            'name' => $target->name,
            'email' => $target->email,
            'role' => UserRole::User->value,
            'password' => '',
            'password_confirmation' => '',
        ])
        ->assertForbidden();

    expect($target->fresh()->role)->toBe(UserRole::Admin);
});

test('an admin cannot change their own role', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => UserRole::User->value,
            'password' => '',
            'password_confirmation' => '',
        ])
        ->assertForbidden();

    expect($admin->fresh()->role)->toBe(UserRole::Admin);
});

test('an admin cannot delete an administrator', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $target = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $target))
        ->assertForbidden();

    $this->assertModelExists($target);
});

test('an admin cannot resolve a developer record through the admin namespace', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $developer = User::factory()->create(['role' => UserRole::Developer]);

    $this->actingAs($admin)
        ->get(route('admin.users.show', $developer))
        ->assertNotFound();
});

test('an admin can delete users and operators', function (UserRole $role): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $target = User::factory()->create(['role' => $role]);

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $target))
        ->assertRedirect(route('admin.users.index'));

    $this->assertModelMissing($target);
})->with([
    'operator' => UserRole::Operator,
    'user' => UserRole::User,
]);

test('admin user creation returns validation errors for invalid input', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'password',
            'password_confirmation' => 'different-password',
            'role' => UserRole::Developer->value,
        ])
        ->assertSessionHasErrors(['name', 'email', 'password', 'role']);
});
