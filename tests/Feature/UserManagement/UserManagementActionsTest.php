<?php

use App\Actions\UserManagement\CreateUser;
use App\Actions\UserManagement\DeleteUser;
use App\Actions\UserManagement\UpdateUser;
use App\Enums\UserRole;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Developer\StoreUserRequest as DeveloperStoreUserRequest;
use App\Http\Requests\Developer\UpdateUserRequest as DeveloperUpdateUserRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

test('the create action persists a user with a hashed password and requested role', function (): void {
    $user = app(CreateUser::class)->handle([
        'name' => 'Created Operator',
        'email' => 'created-operator@example.com',
        'password' => 'password',
        'role' => UserRole::Operator,
    ]);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->role)->toBe(UserRole::Operator)
        ->and(Hash::check('password', $user->password))->toBeTrue();

    $this->assertModelExists($user);
});

test('the update action persists profile fields, role, and a supplied password', function (): void {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
        'role' => UserRole::User,
    ]);

    $updatedUser = app(UpdateUser::class)->handle($user, [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'password' => 'new-password',
        'role' => UserRole::Admin,
    ]);

    expect($updatedUser->fresh()->name)->toBe('Updated Name')
        ->and($updatedUser->fresh()->email)->toBe('updated@example.com')
        ->and($updatedUser->fresh()->role)->toBe(UserRole::Admin)
        ->and(Hash::check('new-password', $updatedUser->fresh()->password))->toBeTrue();
});

test('the update action preserves the existing password when the edit password is empty', function (): void {
    $user = User::factory()->create();
    $originalPasswordHash = $user->password;

    app(UpdateUser::class)->handle($user, [
        'name' => 'Updated Name',
        'email' => $user->email,
        'password' => '',
        'role' => UserRole::User,
    ]);

    expect($user->fresh()->password)->toBe($originalPasswordHash);
});

test('the delete action removes the supplied user', function (): void {
    $user = User::factory()->create();

    app(DeleteUser::class)->handle($user);

    $this->assertModelMissing($user);
});

test('the create action ignores unexpected attributes', function (): void {
    $user = app(CreateUser::class)->handle([
        'name' => 'Safe User',
        'email' => 'safe-user@example.com',
        'password' => 'password',
        'role' => UserRole::User,
        'is_admin' => true,
    ]);

    expect($user->getAttribute('is_admin'))->toBeNull();
});

test('admin store validation rejects duplicate email, invalid role, and mismatched password', function (): void {
    User::factory()->create(['email' => 'taken@example.com']);

    $validator = Validator::make([
        'name' => 'New User',
        'email' => 'taken@example.com',
        'password' => 'password',
        'password_confirmation' => 'different-password',
        'role' => UserRole::Developer->value,
    ], (new StoreUserRequest)->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue()
        ->and($validator->errors()->has('role'))->toBeTrue();
});

test('admin update validation allows the current email and only business roles', function (): void {
    $user = User::factory()->create(['email' => 'current@example.com']);
    $request = new UpdateUserRequest;
    $route = new Route('PUT', '/admin/users/{user}', ['user' => $user]);
    $route->bind(Request::create("/admin/users/{$user->id}", 'PUT'));
    $route->setParameter('user', $user);
    $request->setRouteResolver(fn (): Route => $route);

    $validator = Validator::make([
        'name' => 'Updated User',
        'email' => 'current@example.com',
        'role' => UserRole::Admin->value,
    ], $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('developer validation accepts every user role', function (UserRole $role): void {
    $storeValidator = Validator::make([
        'name' => 'Developer Managed User',
        'email' => "{$role->value}@example.com",
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => $role->value,
    ], (new DeveloperStoreUserRequest)->rules());

    $updateValidator = Validator::make([
        'name' => 'Developer Managed User',
        'email' => "{$role->value}-updated@example.com",
        'role' => $role->value,
    ], (new DeveloperUpdateUserRequest)->rules());

    expect($storeValidator->passes())->toBeTrue()
        ->and($updateValidator->passes())->toBeTrue();
})->with(UserRole::cases());
