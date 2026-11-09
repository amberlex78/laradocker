<?php

use App\Enums\UserRole;
use App\Filament\Widgets\DeveloperAccountWidget;
use App\Filament\Widgets\DeveloperFilamentInfoWidget;
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
        ->assertSee('Linux')
        ->assertDontSee('GNU/Linux')
        ->assertSee('Desktop / Chrome');
});

it('registers the admin dashboard widgets', function (): void {
    $widgets = Filament::getPanel('admin')->getWidgets();

    expect($widgets)
        ->toContain(SystemOverview::class)
        ->toContain(DeveloperAccountWidget::class)
        ->toContain(DeveloperFilamentInfoWidget::class)
        ->and(in_array(AccountWidget::class, $widgets, true))->toBeFalse()
        ->and(in_array(FilamentInfoWidget::class, $widgets, true))->toBeFalse();
});

it('shows account and Filament information widgets only to developers', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    actingAs($admin);
    get('/admin')
        ->assertOk()
        ->assertDontSee('fi-account-widget', false)
        ->assertDontSee('fi-filament-info-widget', false);

    actingAs($developer);
    get('/admin')
        ->assertOk()
        ->assertSee('fi-account-widget', false)
        ->assertSee('fi-filament-info-widget', false);
});

it('is also used on the operator dashboard', function (): void {
    expect(Filament::getPanel('operator')->getWidgets())
        ->toContain(SystemOverview::class);
});

it('excludes developer counts from total users when viewed by administrators', function (): void {
    User::factory()->create([
        'role' => UserRole::User,
    ]);
    User::factory()->create([
        'role' => UserRole::Operator,
    ]);
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    actingAs($admin);
    get('/admin')
        ->assertOk()
        ->assertSeeInOrder([
            'Total users',
            '3',
            'Administrators',
            '1',
            'Operators',
            '1',
        ]);

    actingAs($developer);
    get('/admin')
        ->assertOk()
        ->assertSeeInOrder([
            'Total users',
            '4',
            'Administrators',
            '1',
            'Operators',
            '1',
        ]);
});
