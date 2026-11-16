<?php

use App\Enums\UserRole;
use App\Filament\Site\Pages\ViewProfile;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('shows developers a link to their profile in the welcome widget', function (): void {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    actingAs($developer);

    get('/admin')
        ->assertOk()
        ->assertSee('fi-account-widget', false)
        ->assertSee('My profile')
        ->assertSee(ViewProfile::getUrl(panel: 'site'), false);
});
