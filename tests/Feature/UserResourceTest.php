<?php

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    actingAs($this->admin);
});

it('can list users', function () {
    $users = User::factory()->count(3)->create();

    Livewire::test(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($users);
});

it('allows administrators to see users of every role', function () {
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);
    $anotherAdmin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords([$operator, $anotherAdmin]);
});

it('keeps administrator accounts independent while allowing admins to edit themselves', function () {
    $anotherAdmin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    expect(UserResource::canView($anotherAdmin))->toBeFalse()
        ->and(UserResource::canEdit($anotherAdmin))->toBeFalse()
        ->and(UserResource::canDelete($anotherAdmin))->toBeFalse()
        ->and(UserResource::canDelete($this->admin))->toBeFalse();

    Livewire::test(ViewUser::class, ['record' => $anotherAdmin->id])
        ->assertForbidden();

    Livewire::test(EditUser::class, ['record' => $anotherAdmin->id])
        ->assertForbidden();

    Livewire::test(EditUser::class, [
        'record' => $this->admin->id,
    ])
        ->assertFormFieldEnabled('name')
        ->assertFormFieldHidden('role')
        ->fillForm(['name' => 'Updated Admin'])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertActionHidden(DeleteAction::class);

    expect($this->admin->refresh()->name)->toBe('Updated Admin')
        ->and($this->admin->role)->toBe(UserRole::Admin);
});

it('does not allow administrators to create administrator users', function () {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Second Admin',
            'email' => 'second-admin@example.com',
            'role' => UserRole::Admin->value,
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['role' => 'in']);

    assertDatabaseMissing(User::class, [
        'email' => 'second-admin@example.com',
    ]);
});

it('authorizes role changes only for non-admin users and roles', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);
    $anotherAdmin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $gate = Gate::forUser($this->admin);

    expect($gate->allows('changeRole', [$user, UserRole::Operator]))->toBeTrue()
        ->and($gate->allows('changeRole', [$user, UserRole::Admin]))->toBeFalse()
        ->and($gate->allows('changeRole', [$anotherAdmin, UserRole::User]))->toBeFalse()
        ->and($gate->allows('changeRole', [$this->admin, UserRole::User]))->toBeFalse();
});

it('allows administrators to change non-admin roles but not promote users to admin', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    Livewire::test(EditUser::class, [
        'record' => $user->id,
    ])
        ->fillForm(['role' => UserRole::Operator->value])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh()->role)->toBe(UserRole::Operator);

    Livewire::test(EditUser::class, [
        'record' => $user->id,
    ])
        ->fillForm(['role' => UserRole::Admin->value])
        ->call('save')
        ->assertHasFormErrors(['role' => 'in']);

    expect($user->refresh()->role)->toBe(UserRole::Operator);
});

it('can render create user page', function () {
    Livewire::test(CreateUser::class)
        ->assertOk();
});

it('can create a user', function () {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => UserRole::Operator->value,
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas(User::class, [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'role' => UserRole::Operator->value,
    ]);

    $createdUser = User::where('email', 'john@example.com')->first();
    expect(Hash::check('password123', $createdUser->password))->toBeTrue();
});

it('validates required fields on create', function () {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => null,
            'email' => null,
            'role' => null,
            'password' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);
});

it('validates unique email on create', function () {
    $existing = User::factory()->create(['email' => 'existing@example.com']);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Another User',
            'email' => 'existing@example.com',
            'role' => UserRole::User->value,
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'unique']);
});

it('can render edit user page', function () {
    $user = User::factory()->create();

    Livewire::test(EditUser::class, [
        'record' => $user->id,
    ])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

it('can update user without changing password', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'password' => 'original-password',
    ]);
    $oldPasswordHash = $user->password;

    Livewire::test(EditUser::class, [
        'record' => $user->id,
    ])
        ->fillForm([
            'name' => 'New Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $user->refresh();
    expect($user->name)->toBe('New Name')
        ->and($user->password)->toBe($oldPasswordHash);
});

it('can update user with a new password', function () {
    $user = User::factory()->create([
        'name' => 'User Name',
    ]);

    Livewire::test(EditUser::class, [
        'record' => $user->id,
    ])
        ->fillForm([
            'password' => 'new-secret-password',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $user->refresh();
    expect(Hash::check('new-secret-password', $user->password))->toBeTrue();
});

it('can delete a user', function () {
    $user = User::factory()->create();

    Livewire::test(EditUser::class, [
        'record' => $user->id,
    ])
        ->callAction(DeleteAction::class)
        ->assertNotified();

    assertDatabaseMissing(User::class, [
        'id' => $user->id,
    ]);
});
