<?php

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
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

it('does not show developers to administrators', function () {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords([$operator])
        ->assertCanNotSeeTableRecords([$developer]);
});

it('allows developers to view but not manage developer users', function () {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);
    $anotherDeveloper = User::factory()->create([
        'role' => UserRole::Developer,
    ]);
    actingAs($developer);

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords([$anotherDeveloper]);

    expect(UserResource::canEdit($anotherDeveloper))->toBeFalse()
        ->and(UserResource::canDelete($anotherDeveloper))->toBeFalse()
        ->and(UserResource::canEdit($this->admin))->toBeTrue()
        ->and(UserResource::canDelete($this->admin))->toBeTrue();
});

it('shows other administrators as read-only and allows editing your own record', function () {
    $anotherAdmin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    expect(UserResource::canDelete($anotherAdmin))->toBeFalse();

    Livewire::test(ViewUser::class, [
        'record' => $anotherAdmin->id,
    ])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $anotherAdmin->name,
            'email' => $anotherAdmin->email,
        ])
        ->assertActionHidden(EditAction::class);

    Livewire::test(EditUser::class, [
        'record' => $this->admin->id,
    ])
        ->assertFormFieldEnabled('name')
        ->fillForm(['name' => 'Updated Admin'])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertActionHidden(DeleteAction::class);

    expect($this->admin->refresh()->name)->toBe('Updated Admin');
});

it('does not allow administrators to create privileged roles', function () {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Privileged User',
            'email' => 'privileged@example.com',
            'role' => UserRole::Developer->value,
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['role' => 'in']);
});

it('does not allow developers to create developer users', function () {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);
    actingAs($developer);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Another Developer',
            'email' => 'another-developer@example.com',
            'role' => UserRole::Developer->value,
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['role' => 'in']);
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
