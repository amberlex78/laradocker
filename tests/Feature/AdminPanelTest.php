<?php

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('registers the public site user menu item before sign out', function () {
    $user = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    actingAs($user);

    $panel = Filament::getPanel('admin');
    $items = $panel->getUserMenuItems();

    expect(array_keys($items))->toContain('operator-panel')
        ->and($items['operator-panel']->getLabel())->toBe('Operator panel')
        ->and($items['operator-panel']->getUrl())->toBe(Filament::getPanel('operator')->getUrl())
        ->and(array_keys($items))->toContain('public-site')
        ->and($items['public-site']->getLabel())->toBe('Public site')
        ->and($items['public-site']->getUrl())->toBe('/')
        ->and($items['public-site']->shouldOpenUrlInNewTab())->toBeTrue();

    $keys = array_values(array_keys($items));
    $publicSiteIndex = array_search('public-site', $keys, true);
    $logoutIndex = array_search('logout', $keys, true);

    expect($publicSiteIndex)->toBeLessThan($logoutIndex);
});

it('does not offer mass deletion for users', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin);
    Filament::setCurrentPanel('admin');

    Livewire::test(ListUsers::class)
        ->assertTableBulkActionsExistInOrder([]);
});

it('renders verified and unverified statuses in the user list', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $verifiedUser = User::factory()->create();
    $unverifiedUser = User::factory()->unverified()->create();

    $this->actingAs($admin);
    Filament::setCurrentPanel('admin');

    Livewire::test(ListUsers::class)
        ->assertTableColumnStateSet('email_verified_at', 'Verified', record: $verifiedUser)
        ->assertTableColumnStateSet('email_verified_at', 'Unverified', record: $unverifiedUser);
});

it('allows sorting the verified column in the user list', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin);
    Filament::setCurrentPanel('admin');

    Livewire::test(ListUsers::class)
        ->assertTableColumnExists('email_verified_at', fn ($column): bool => $column->isSortable());
});

it('filters users by email verification status', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $verifiedUser = User::factory()->create();
    $unverifiedUser = User::factory()->unverified()->create();

    $this->actingAs($admin);
    Filament::setCurrentPanel('admin');

    Livewire::test(ListUsers::class)
        ->filterTable('email_verified_at', true)
        ->assertCanSeeTableRecords([$admin, $verifiedUser])
        ->assertCanNotSeeTableRecords([$unverifiedUser])
        ->filterTable('email_verified_at', false)
        ->assertCanSeeTableRecords([$unverifiedUser])
        ->assertCanNotSeeTableRecords([$admin, $verifiedUser]);
});

it('filters users by role using roles available to the current user', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($admin);
    Filament::setCurrentPanel('admin');

    Livewire::test(ListUsers::class)
        ->assertTableFilterExists('role', function (SelectFilter $filter): bool {
            return $filter->getOptions() === [
                UserRole::User->value => UserRole::User->getLabel(),
                UserRole::Operator->value => UserRole::Operator->getLabel(),
                UserRole::Admin->value => UserRole::Admin->getLabel(),
            ];
        })
        ->filterTable('role', UserRole::Operator)
        ->assertCanSeeTableRecords([$operator])
        ->assertCanNotSeeTableRecords([$admin, $user]);

    $this->actingAs($developer);

    Livewire::test(ListUsers::class)
        ->assertTableFilterExists('role', function (SelectFilter $filter): bool {
            return $filter->getOptions() === [
                UserRole::User->value => UserRole::User->getLabel(),
                UserRole::Operator->value => UserRole::Operator->getLabel(),
                UserRole::Admin->value => UserRole::Admin->getLabel(),
                UserRole::Developer->value => UserRole::Developer->getLabel(),
            ];
        })
        ->filterTable('role', UserRole::Developer)
        ->assertCanSeeTableRecords([$developer])
        ->assertCanNotSeeTableRecords([$admin, $user, $operator]);
});

it('sorts users by creation date with newest users first by default', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
        'created_at' => now()->subDays(3),
        'updated_at' => now()->subDays(3),
    ]);
    $olderUser = User::factory()->create([
        'created_at' => now()->subDays(2),
        'updated_at' => now()->subDays(2),
    ]);
    $newerUser = User::factory()->create([
        'created_at' => now()->subDay(),
        'updated_at' => now()->subDay(),
    ]);

    $this->actingAs($admin);
    Filament::setCurrentPanel('admin');

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords([$newerUser, $olderUser, $admin], inOrder: true);
});

it('allows viewing but not editing or deleting administrator records', function (): void {
    $actingAdmin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $administrator = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($actingAdmin);

    expect(UserResource::canView($administrator))->toBeTrue()
        ->and(UserResource::canEdit($administrator))->toBeFalse()
        ->and(UserResource::canDelete($administrator))->toBeFalse();

    $this->get(route('filament.admin.resources.users.view', ['record' => $administrator]))
        ->assertOk();

    $this->get(route('filament.admin.resources.users.edit', ['record' => $administrator]))
        ->assertForbidden();

    Filament::setCurrentPanel('admin');

    Livewire::test(ListUsers::class)
        ->assertTableActionVisible('view', $administrator)
        ->assertTableActionHidden('edit', $administrator)
        ->assertTableActionHidden('delete', $administrator);

    Livewire::test(ViewUser::class, ['record' => $administrator->getKey()])
        ->assertActionHidden('edit')
        ->assertSee($administrator->name)
        ->assertSee($administrator->email)
        ->assertSee('Name')
        ->assertSee('Email')
        ->assertSee('Role')
        ->assertSee('Created at')
        ->assertSee('Updated at');
});

it('allows full CRUD authorization for user and operator records', function (UserRole $role): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $record = User::factory()->make([
        'role' => $role,
    ]);

    $this->actingAs($admin);

    expect(UserResource::canView($record))->toBeTrue()
        ->and(UserResource::canEdit($record))->toBeTrue()
        ->and(UserResource::canDelete($record))->toBeTrue();
})->with([
    'user' => UserRole::User,
    'operator' => UserRole::Operator,
]);

it('authorizes user management through the user policy', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $administrator = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    expect(Gate::forUser($admin)->allows('viewAny', User::class))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('view', $administrator))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('create', User::class))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('update', $administrator))->toBeFalse()
        ->and(Gate::forUser($admin)->allows('delete', $administrator))->toBeFalse()
        ->and(Gate::forUser($admin)->allows('update', $operator))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('delete', $operator))->toBeTrue();
});

it('does not allow creating an administrator through user management', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $email = fake()->unique()->safeEmail();

    $this->actingAs($admin);
    Filament::setCurrentPanel('admin');

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'New administrator',
            'email' => $email,
            'password' => 'Password123!',
            'role' => UserRole::Admin->value,
        ])
        ->call('create')
        ->assertHasFormErrors(['role' => 'in'])
        ->assertNoRedirect();

    expect(User::query()->where('email', $email)->exists())->toBeFalse();
});

it('hides developer records from administrators in user management', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $developer = User::factory()->create([
        'name' => 'Hidden Developer',
        'email' => 'stealth@example.com',
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($admin);
    Filament::setCurrentPanel('admin');

    expect(UserResource::canView($developer))->toBeFalse()
        ->and(UserResource::canEdit($developer))->toBeFalse()
        ->and(UserResource::canDelete($developer))->toBeFalse();

    $this->get(route('filament.admin.resources.users.view', ['record' => $developer]))
        ->assertNotFound();

    $this->get(route('filament.admin.resources.users.edit', ['record' => $developer]))
        ->assertNotFound();

    Livewire::test(ListUsers::class)
        ->assertCanNotSeeTableRecords([$developer])
        ->searchTable('stealth')
        ->assertCanNotSeeTableRecords([$developer]);
});

it('allows developers to view, edit, and delete administrators', function (): void {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);
    $administrator = User::factory()->create([
        'name' => 'Existing Admin',
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($developer);
    Filament::setCurrentPanel('admin');

    expect(UserResource::canView($administrator))->toBeTrue()
        ->and(UserResource::canEdit($administrator))->toBeTrue()
        ->and(UserResource::canDelete($administrator))->toBeTrue();

    $this->get(route('filament.admin.resources.users.view', ['record' => $administrator]))
        ->assertOk();

    $this->get(route('filament.admin.resources.users.edit', ['record' => $administrator]))
        ->assertOk();

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords([$administrator])
        ->assertTableActionVisible('view', $administrator)
        ->assertTableActionVisible('edit', $administrator)
        ->assertTableActionVisible('delete', $administrator);

    Livewire::test(EditUser::class, ['record' => $administrator->getKey()])
        ->fillForm([
            'name' => 'Updated Admin Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($administrator->refresh()->name)->toBe('Updated Admin Name');
});

it('allows developers to create administrators through user management', function (): void {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);
    $email = fake()->unique()->safeEmail();

    $this->actingAs($developer);
    Filament::setCurrentPanel('admin');

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'New Administrator',
            'email' => $email,
            'password' => 'Password123!',
            'role' => UserRole::Admin->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $created = User::query()->where('email', $email)->first();
    expect($created)->not->toBeNull()
        ->and($created->role)->toBe(UserRole::Admin);
});

it('prevents developers from deleting themselves', function (): void {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($developer);
    Filament::setCurrentPanel('admin');

    expect(UserResource::canDelete($developer))->toBeFalse();

    Livewire::test(ListUsers::class)
        ->assertTableActionHidden('delete', $developer);
});

it('authorizes full permissions for developers in user policy', function (): void {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);
    $administrator = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    expect(Gate::forUser($developer)->allows('viewAny', User::class))->toBeTrue()
        ->and(Gate::forUser($developer)->allows('view', $administrator))->toBeTrue()
        ->and(Gate::forUser($developer)->allows('create', User::class))->toBeTrue()
        ->and(Gate::forUser($developer)->allows('update', $administrator))->toBeTrue()
        ->and(Gate::forUser($developer)->allows('delete', $administrator))->toBeTrue()
        ->and(Gate::forUser($developer)->allows('delete', $developer))->toBeFalse();
});
