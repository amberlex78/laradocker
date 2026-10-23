<?php

use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;

it('registers the public site user menu item before sign out', function () {
    $user = User::factory()->create();
    actingAs($user);

    $panel = Filament::getPanel('admin');
    $items = $panel->getUserMenuItems();

    expect(array_keys($items))->toContain('public-site')
        ->and($items['public-site']->getLabel())->toBe('Public site')
        ->and($items['public-site']->getUrl())->toBe('/')
        ->and($items['public-site']->shouldOpenUrlInNewTab())->toBeTrue();

    $keys = array_values(array_keys($items));
    $publicSiteIndex = array_search('public-site', $keys, true);
    $logoutIndex = array_search('logout', $keys, true);

    expect($publicSiteIndex)->toBeLessThan($logoutIndex);
});
