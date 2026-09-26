<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

test('guests are redirected from the developer user index', function (): void {
    $this->get(route('developer.users.index'))->assertRedirect('/login');
});

test('only developers can access the developer user index', function (UserRole $role): void {
    $actor = User::factory()->create(['role' => $role]);

    $this->actingAs($actor)
        ->get(route('developer.users.index'))
        ->assertForbidden();
})->with([
    'admin' => UserRole::Admin,
    'operator' => UserRole::Operator,
    'user' => UserRole::User,
]);

test('the developer index includes every user role', function (): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $target = User::factory()->create([
        'email' => 'visible-to-developer@example.com',
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($developer)
        ->get(route('developer.users.index'))
        ->assertOk()
        ->assertSee($target->email)
        ->assertSee('developer');
});

test('a developer can create every user role', function (UserRole $role): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);

    $response = $this->actingAs($developer)->post(route('developer.users.store'), [
        'name' => "Developer Created {$role->value}",
        'email' => "developer-{$role->value}@example.com",
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => $role->value,
    ]);

    $response->assertRedirect(route('developer.users.index'));

    expect(User::where('email', "developer-{$role->value}@example.com")->firstOrFail()->role)
        ->toBe($role);
})->with(UserRole::cases());

test('a developer can update a user profile and role', function (): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $target = User::factory()->create(['role' => UserRole::User]);

    $response = $this->actingAs($developer)->put(route('developer.users.update', $target), [
        'name' => 'Developer Updated User',
        'email' => 'developer-updated@example.com',
        'password' => '',
        'password_confirmation' => '',
        'role' => UserRole::Developer->value,
    ]);

    $response->assertRedirect(route('developer.users.index'));

    expect($target->fresh()->name)->toBe('Developer Updated User')
        ->and($target->fresh()->email)->toBe('developer-updated@example.com')
        ->and($target->fresh()->role)->toBe(UserRole::Developer);
});

test('a developer can delete every user role', function (UserRole $role): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $target = User::factory()->create(['role' => $role]);

    $this->actingAs($developer)
        ->delete(route('developer.users.destroy', $target))
        ->assertRedirect(route('developer.users.index'));

    $this->assertModelMissing($target);
})->with(UserRole::cases());

test('a developer cannot delete their own account', function (): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);

    $this->actingAs($developer)
        ->delete(route('developer.users.destroy', $developer))
        ->assertForbidden();

    $this->assertModelExists($developer);
});
