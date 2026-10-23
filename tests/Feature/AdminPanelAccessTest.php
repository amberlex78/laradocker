<?php

use App\Enums\UserRole;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;

it('allows admin and developer users to access the admin panel', function (UserRole $role) {
    $user = User::factory()->create([
        'role' => $role,
    ]);

    expect($user->canAccessPanel(Filament::getPanel('admin')))->toBeTrue();
})->with([
    'admin' => UserRole::Admin,
    'developer' => UserRole::Developer,
]);

it('allows admin and developer roles to access the admin panel route', function (UserRole $role) {
    actingAs(User::factory()->create([
        'role' => $role,
    ]));

    $this->get('/admin')->assertOk();
})->with([
    'admin' => UserRole::Admin,
    'developer' => UserRole::Developer,
]);

it('denies user and operator users access to the admin panel', function (UserRole $role) {
    $user = User::factory()->create([
        'role' => $role,
    ]);

    expect($user->canAccessPanel(Filament::getPanel('admin')))->toBeFalse();
})->with([
    'user' => UserRole::User,
    'operator' => UserRole::Operator,
]);

it('forbids user and operator roles from the admin panel route', function (UserRole $role) {
    actingAs(User::factory()->create([
        'role' => $role,
    ]));

    $this->get('/admin')->assertForbidden();
})->with([
    'user' => UserRole::User,
    'operator' => UserRole::Operator,
]);
