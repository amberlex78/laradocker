<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class);

test('only administrators and developers can view the user index', function (UserRole $role, bool $allowed): void {
    $actor = User::factory()->make(['role' => $role]);

    expect(Gate::forUser($actor)->allows('viewAny', User::class))->toBe($allowed);
})->with([
    'developer' => [UserRole::Developer, true],
    'admin' => [UserRole::Admin, true],
    'operator' => [UserRole::Operator, false],
    'user' => [UserRole::User, false],
]);

test('admins cannot view developers but can view business users', function (UserRole $targetRole, bool $allowed): void {
    $actor = User::factory()->make(['role' => UserRole::Admin]);
    $target = User::factory()->make(['role' => $targetRole]);

    expect(Gate::forUser($actor)->allows('view', $target))->toBe($allowed);
})->with([
    'admin' => [UserRole::Admin, true],
    'operator' => [UserRole::Operator, true],
    'user' => [UserRole::User, true],
    'developer' => [UserRole::Developer, false],
]);

test('developers can view every user role', function (UserRole $targetRole): void {
    $actor = User::factory()->make(['role' => UserRole::Developer]);
    $target = User::factory()->make(['role' => $targetRole]);

    expect(Gate::forUser($actor)->allows('view', $target))->toBeTrue();
})->with(UserRole::cases());

test('administrators can create business roles but not developers', function (UserRole $role, bool $allowed): void {
    $actor = User::factory()->make(['role' => UserRole::Admin]);

    expect(Gate::forUser($actor)->allows('create', [User::class, $role]))->toBe($allowed);
})->with([
    'admin' => [UserRole::Admin, true],
    'operator' => [UserRole::Operator, true],
    'user' => [UserRole::User, true],
    'developer' => [UserRole::Developer, false],
]);

test('developers can create every user role', function (UserRole $role): void {
    $actor = User::factory()->make(['role' => UserRole::Developer]);

    expect(Gate::forUser($actor)->allows('create', [User::class, $role]))->toBeTrue();
})->with(UserRole::cases());

test('admins can update profile fields for business users including themselves', function (UserRole $targetRole): void {
    $actor = User::factory()->make(['role' => UserRole::Admin]);
    $target = User::factory()->make(['role' => $targetRole]);

    expect(Gate::forUser($actor)->allows('update', $target))->toBeTrue();
})->with([
    'self' => [UserRole::Admin],
    'other admin' => [UserRole::Admin],
    'operator' => [UserRole::Operator],
    'user' => [UserRole::User],
]);

test('administrators cannot update developer profiles', function (): void {
    $actor = User::factory()->make(['role' => UserRole::Admin]);
    $target = User::factory()->make(['role' => UserRole::Developer]);

    expect(Gate::forUser($actor)->allows('update', $target))->toBeFalse();
});

test('administrators can change business roles except for admin demotions', function (UserRole $targetRole, UserRole $role, bool $allowed): void {
    $actor = User::factory()->make(['role' => UserRole::Admin]);
    $target = User::factory()->make(['role' => $targetRole]);

    expect(Gate::forUser($actor)->allows('changeRole', [$target, $role]))->toBe($allowed);
})->with([
    'promote user' => [UserRole::User, UserRole::Admin, true],
    'lower operator' => [UserRole::Operator, UserRole::User, true],
    'demote admin' => [UserRole::Admin, UserRole::User, false],
]);

test('administrators cannot change their own role', function (): void {
    $actor = User::factory()->make(['role' => UserRole::Admin]);

    expect(Gate::forUser($actor)->allows('changeRole', [$actor, UserRole::Operator]))->toBeFalse();
});

test('developers can change every target to every role', function (UserRole $targetRole, UserRole $newRole): void {
    $actor = User::factory()->make(['role' => UserRole::Developer]);
    $target = User::factory()->make(['role' => $targetRole]);

    expect(Gate::forUser($actor)->allows('changeRole', [$target, $newRole]))->toBeTrue();
})->with(function (): array {
    $cases = [];

    foreach (UserRole::cases() as $targetRole) {
        foreach (UserRole::cases() as $newRole) {
            $cases["{$targetRole->value} to {$newRole->value}"] = [$targetRole, $newRole];
        }
    }

    return $cases;
});

test('administrators can delete users and operators but not administrators or developers', function (UserRole $targetRole, bool $allowed): void {
    $actor = User::factory()->make(['role' => UserRole::Admin]);
    $target = User::factory()->make(['role' => $targetRole]);

    expect(Gate::forUser($actor)->allows('delete', $target))->toBe($allowed);
})->with([
    'user' => [UserRole::User, true],
    'operator' => [UserRole::Operator, true],
    'admin' => [UserRole::Admin, false],
    'developer' => [UserRole::Developer, false],
]);

test('developers can delete every target role', function (UserRole $targetRole): void {
    $actor = User::factory()->make(['role' => UserRole::Developer]);
    $target = User::factory()->make(['role' => $targetRole]);

    expect(Gate::forUser($actor)->allows('delete', $target))->toBeTrue();
})->with(UserRole::cases());
