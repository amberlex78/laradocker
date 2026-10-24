<?php

use App\Enums\UserRole;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;

it('assigns a distinct color to each user role', function () {
    expect(UserRole::User->getColor())->toBe('gray')
        ->and(UserRole::Operator->getColor())->toBe('info')
        ->and(UserRole::Admin->getColor())->toBe('danger');
});

it('provides all roles to administrators in role filters', function () {
    expect(UserRole::options(UserRole::Admin))->toBe([
        UserRole::User->value => 'User',
        UserRole::Operator->value => 'Operator',
        UserRole::Admin->value => 'Admin',
    ])
        ->and(UserRole::options())->toBe(UserRole::options(UserRole::Admin));
});

it('allows administrators to access the admin panel', function () {
    $user = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    expect($user->canAccessPanel(Filament::getPanel('admin')))->toBeTrue();
});

it('allows administrators to access the admin panel route', function () {
    actingAs(User::factory()->create([
        'role' => UserRole::Admin,
    ]));

    $this->get('/admin')->assertOk();
});

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
