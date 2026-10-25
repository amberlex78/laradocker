<?php

use App\Enums\UserRole;
use App\Filament\Widgets\SystemOverview;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('renders user totals and the current browser environment on the admin dashboard', function (): void {
    User::factory()->count(2)->create([
        'role' => UserRole::User,
    ]);
    User::factory()->count(3)->create([
        'role' => UserRole::Operator,
    ]);
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    actingAs($admin);

    $response = get('/admin', [
        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 '
            .'(KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36',
    ]);

    $response
        ->assertOk()
        ->assertSee('fi-wi-stats-overview-stat', false)
        ->assertSeeInOrder([
            'Total users',
            '7',
            'Administrators',
            '2',
            'Operators',
            '3',
        ])
        ->assertSee('Your system')
        ->assertSee('GNU/Linux')
        ->assertSee('Desktop / Chrome');
});

it('replaces the default admin dashboard widgets', function (): void {
    $widgets = Filament::getPanel('admin')->getWidgets();

    expect($widgets)
        ->toContain(SystemOverview::class)
        ->and(in_array(AccountWidget::class, $widgets, true))->toBeFalse()
        ->and(in_array(FilamentInfoWidget::class, $widgets, true))->toBeFalse();
});

it('is also used on the operator dashboard', function (): void {
    expect(Filament::getPanel('operator')->getWidgets())
        ->toContain(SystemOverview::class);
});
