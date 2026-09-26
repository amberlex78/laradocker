<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin navigation exposes only the admin user-management namespace', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Users')
        ->assertSee('href="'.route('admin.users.index').'"', false)
        ->assertDontSee(route('developer.users.index'));
});

test('developer navigation exposes the developer user-management namespace', function (): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);

    $this->actingAs($developer)
        ->get(route('developer.dashboard'))
        ->assertOk()
        ->assertSee('Users')
        ->assertSee('href="'.route('developer.users.index').'"', false)
        ->assertDontSee(route('admin.users.index'));
});

test('admin user pages show business roles and exclude developer records', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $businessUser = User::factory()->create([
        'name' => "O'Reilly <script>alert('xss')</script>",
        'email' => 'business@example.com',
        'role' => UserRole::User,
    ]);
    $developer = User::factory()->create([
        'name' => 'Hidden Developer',
        'email' => 'hidden-developer@example.com',
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee($businessUser->email)
        ->assertSee('&lt;script&gt;alert(&#039;xss&#039;)&lt;/script&gt;', false)
        ->assertDontSee("<script>alert('xss')</script>", false)
        ->assertDontSee($developer->name)
        ->assertDontSee($developer->email)
        ->assertDontSee('Developer');
});

test('developer user pages show developer records and role badges', function (): void {
    $developer = User::factory()->create([
        'name' => 'Technical Developer',
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($developer)
        ->get(route('developer.users.index'))
        ->assertOk()
        ->assertSee($developer->name)
        ->assertSee('Developer');
});

test('admin create and edit forms expose business roles and delete controls', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $target = User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($admin)
        ->get(route('admin.users.create'))
        ->assertOk()
        ->assertSee('name="name"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertSee('value="admin"', false)
        ->assertSee('value="operator"', false)
        ->assertSee('value="user"', false)
        ->assertDontSee('value="developer"', false);

    $this->actingAs($admin)
        ->get(route('admin.users.edit', $target))
        ->assertOk()
        ->assertSee('name="_method" value="PUT"', false)
        ->assertSee('action="'.route('admin.users.destroy', $target).'"', false)
        ->assertSee('value="DELETE"', false);
});

test('developer forms expose every role including developer', function (): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);

    $this->actingAs($developer)
        ->get(route('developer.users.create'))
        ->assertOk()
        ->assertSee('value="developer"', false)
        ->assertSee('value="admin"', false)
        ->assertSee('value="operator"', false)
        ->assertSee('value="user"', false);
});

test('edit pages place the danger zone in the second column without empty create placeholders', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $adminTarget = User::factory()->create(['role' => UserRole::User]);
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $developerTarget = User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($admin)
        ->get(route('admin.users.create'))
        ->assertSee('lg:w-1/2', false)
        ->assertDontSee('min-h-[420px]', false)
        ->assertDontSee('Danger zone');

    $this->actingAs($admin)
        ->get(route('admin.users.edit', $adminTarget))
        ->assertSee('lg:grid-cols-2', false)
        ->assertSee('Danger zone')
        ->assertSee('Delete user');

    $this->actingAs($developer)
        ->get(route('developer.users.create'))
        ->assertSee('lg:w-1/2', false)
        ->assertDontSee('min-h-[420px]', false)
        ->assertDontSee('Danger zone');

    $this->actingAs($developer)
        ->get(route('developer.users.edit', $developerTarget))
        ->assertSee('lg:grid-cols-2', false)
        ->assertSee('Danger zone')
        ->assertSee('Delete user');
});
