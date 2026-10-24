<?php

use App\Enums\UserRole;
use App\Models\User;
use Filament\Facades\Filament;

it('restricts panel access by user role', function (UserRole $role, string $panelId, bool $canAccess): void {
    $user = User::factory()->make([
        'role' => $role,
    ]);

    expect($user->canAccessPanel(Filament::getPanel($panelId)))->toBe($canAccess);
})->with([
    'admin can access admin panel' => [UserRole::Admin, 'admin', true],
    'operator cannot access admin panel' => [UserRole::Operator, 'admin', false],
    'user cannot access admin panel' => [UserRole::User, 'admin', false],
    'operator can access operator panel' => [UserRole::Operator, 'operator', true],
    'admin can access operator panel' => [UserRole::Admin, 'operator', true],
    'user cannot access operator panel' => [UserRole::User, 'operator', false],
]);

it('forbids an operator from the admin dashboard', function (): void {
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    $this->actingAs($operator)
        ->get('/admin')
        ->assertForbidden();
});

it('allows an operator to access the operator dashboard', function (): void {
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    $this->actingAs($operator)
        ->get('/operator')
        ->assertOk();
});
