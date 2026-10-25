<?php

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Facades\Filament;
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
