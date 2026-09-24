<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the public home page renders', function () {
    $this->get('/')->assertOk()->assertSee('Welcome');
});

test('the login page renders the Blade authentication screen', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Log in to your account');
});

test('a user sees the account page', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $this->actingAs($user)
        ->get('/account')
        ->assertOk()
        ->assertSee('Your account');
});

test('an admin sees the admin shell without developer navigation', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Admin workspace navigation')
        ->assertSee('Overview')
        ->assertDontSee('Developer workspace navigation');
});

test('an admin sees the responsive business dashboard shell', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Overview')
        ->assertSee('Quick actions')
        ->assertSee('No recent activity yet')
        ->assertSee('sidebarOpen')
        ->assertSee('x-show="sidebarOpen"', false);
});

test('a developer sees the developer shell and can open the admin shell', function () {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($developer)
        ->get('/developer')
        ->assertOk()
        ->assertSee('Developer workspace navigation')
        ->assertSee('Technical workspace');

    $this->actingAs($developer)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Admin workspace navigation');
});

test('a developer sees the technical dashboard shell', function () {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($developer)
        ->get('/developer')
        ->assertOk()
        ->assertSee('Application status')
        ->assertSee('Technical workspace')
        ->assertSee('No technical events yet')
        ->assertSee('sidebarOpen')
        ->assertSee('x-show="sidebarOpen"', false);
});
