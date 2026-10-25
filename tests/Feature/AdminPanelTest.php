<?php

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
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
        ->assertActionHidden('edit');
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
