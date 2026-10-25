<?php

use App\Enums\UserRole;
use App\Filament\Site\Pages\EditProfile;
use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Livewire\Livewire;

it('restricts panel access by user role', function (UserRole $role, string $panelId, bool $canAccess): void {
    $user = User::factory()->make([
        'role' => $role,
    ]);

    expect($user->canAccessPanel(Filament::getPanel($panelId)))->toBe($canAccess);
})->with([
    'user can access site panel' => [UserRole::User, 'site', true],
    'admin can access site panel' => [UserRole::Admin, 'site', true],
    'operator can access site panel' => [UserRole::Operator, 'site', true],
    'admin can access admin panel' => [UserRole::Admin, 'admin', true],
    'operator cannot access admin panel' => [UserRole::Operator, 'admin', false],
    'user cannot access admin panel' => [UserRole::User, 'admin', false],
    'operator can access operator panel' => [UserRole::Operator, 'operator', true],
    'admin can access operator panel' => [UserRole::Admin, 'operator', true],
    'user cannot access operator panel' => [UserRole::User, 'operator', false],
]);

it('assigns panel colors to user roles', function (UserRole $role, array $color): void {
    expect($role->getColor())->toBe($color);
})->with([
    'user uses site green' => [UserRole::User, Color::Green],
    'operator uses operator blue' => [UserRole::Operator, Color::Blue],
    'admin uses admin amber' => [UserRole::Admin, Color::Amber],
]);

it('shows guests a sign in button on the home page', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertSee('Sign in')
        ->assertSee(route('filament.site.auth.login'), false);
});

it('shows each role its available entries on the home page', function (UserRole $role, array $entries): void {
    $user = User::factory()->create([
        'role' => $role,
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertOk();

    foreach ($entries as [$label, $url]) {
        $response
            ->assertSee($label)
            ->assertSee($url, false);
    }

    $response
        ->assertSee('Sign out')
        ->assertSee(route('filament.site.auth.logout'), false);
})->with([
    'users see their profile entry' => [UserRole::User, [
        ['My profile', '/profile'],
    ]],
    'operators see their panel and profile entries' => [UserRole::Operator, [
        ['Operator panel', '/operator'],
        ['My profile', '/profile'],
    ]],
    'admins see all available entries' => [UserRole::Admin, [
        ['Admin panel', '/admin'],
        ['Operator panel', '/operator'],
        ['My profile', '/profile'],
    ]],
]);

it('redirects guests from protected panels to the shared login page', function (string $path): void {
    $this->get($path)
        ->assertRedirect('/login');
})->with([
    '/admin',
    '/operator',
]);

it('forbids an operator from the admin dashboard', function (): void {
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    $this->actingAs($operator)
        ->get('/admin')
        ->assertForbidden();
});

it('allows an operator to access the operator dashboard', function (): void {
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    $this->actingAs($operator)
        ->get('/operator')
        ->assertOk();
});

it('redirects each role to its own destination after shared login', function (UserRole $role, string $destination): void {
    $user = User::factory()->create([
        'role' => $role,
    ]);

    $this->actingAs($user);
    Filament::setCurrentPanel('site');
    session()->put('url.intended', '/admin');

    $response = app(LoginResponse::class)->toResponse(request());

    expect($response->getTargetUrl())->toBe(url($destination));
})->with([
    'admin goes to admin panel' => [UserRole::Admin, '/admin'],
    'operator goes to operator panel' => [UserRole::Operator, '/operator'],
    'user goes to landing page' => [UserRole::User, '/'],
]);

it('always redirects logout to shared login', function (): void {
    Filament::setCurrentPanel('admin');

    $response = app(LogoutResponse::class)->toResponse(request());

    expect($response->getTargetUrl())->toBe(route('filament.site.auth.login'));
});

it('logs out from the operator panel and redirects guests to shared login', function (): void {
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    $this->actingAs($operator)
        ->post('/operator/logout')
        ->assertRedirect('/login');

    $this->get('/operator')
        ->assertRedirect('/login');
});

it('uses the configured profile content width', function (): void {
    $panel = Filament::getPanel('site');
    $profilePage = $panel->getProfilePage();

    expect($profilePage)->toBe(EditProfile::class)
        ->and((new $profilePage)->getMaxContentWidth())->toBe(Width::SevenExtraLarge);
});

it('allows every role to access the shared profile page', function (UserRole $role): void {
    $user = User::factory()->create([
        'role' => $role,
    ]);

    $this->actingAs($user)
        ->get('/profile')
        ->assertOk()
        ->assertSee('My profile')
        ->assertSee('Manage your account')
        ->assertSee('Account information')
        ->assertSee('Profile information')
        ->assertSee('fi-fieldset', false)
        ->assertSee('Password')
        ->assertSee('New password')
        ->assertSee('Active sessions')
        ->assertSee('Terminate other sessions')
        ->assertDontSee('Confirm new password')
        ->assertDontSee('Update password')
        ->assertDontSee('fi-fo-field-has-inline-label');
})->with([
    UserRole::User,
    UserRole::Operator,
    UserRole::Admin,
]);

it('reveals conditional password fields when a new password is entered', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);
    Filament::setCurrentPanel('site');

    Livewire::test(EditProfile::class)
        ->fillForm([
            'password' => 'NewPassword123!',
        ])
        ->assertSee('Current password')
        ->assertSee('Confirm new password');
});

it('saves profile details without changing the password when password fields are empty', function (): void {
    $user = User::factory()->create([
        'name' => 'Original name',
    ]);

    $this->actingAs($user);
    Filament::setCurrentPanel('site');

    Livewire::test(EditProfile::class)
        ->fillForm([
            'name' => 'Updated name',
            'email' => $user->email,
            'password' => null,
            'passwordConfirmation' => null,
            'currentPassword' => null,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh()->name)->toBe('Updated name');
});

it('allows an admin to update their own details through the profile', function (): void {
    $admin = User::factory()->create([
        'name' => 'Original admin name',
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin);
    Filament::setCurrentPanel('site');

    Livewire::test(EditProfile::class)
        ->fillForm([
            'name' => 'Updated admin name',
            'email' => $admin->email,
            'password' => null,
            'passwordConfirmation' => null,
            'currentPassword' => null,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($admin->refresh()->name)->toBe('Updated admin name')
        ->and($admin->role)->toBe(UserRole::Admin);
});

it('hides conditional password fields after saving a password change', function (): void {
    $user = User::factory()->create([
        'name' => 'Original name',
    ]);

    $this->actingAs($user);
    Filament::setCurrentPanel('site');

    Livewire::test(EditProfile::class)
        ->fillForm([
            'name' => 'Updated name',
            'email' => $user->email,
            'currentPassword' => 'password',
            'password' => 'NewPassword123!',
            'passwordConfirmation' => 'NewPassword123!',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertFormSet([
            'password' => null,
            'passwordConfirmation' => null,
        ])
        ->assertDontSee('Confirm new password');
});

it('shows both panel links to admins in the profile menu', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin);
    Filament::setCurrentPanel('site');

    $items = Filament::getPanel('site')->getUserMenuItems();

    expect($items)->toHaveKeys(['admin-panel', 'operator-panel'])
        ->and($items['admin-panel']->getUrl())->toBe(Filament::getPanel('admin')->getUrl())
        ->and($items['operator-panel']->getUrl())->toBe(Filament::getPanel('operator')->getUrl());
});

it('shows only the operator panel link to operators in the profile menu', function (): void {
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    $this->actingAs($operator);
    Filament::setCurrentPanel('site');

    $items = Filament::getPanel('site')->getUserMenuItems();

    expect($items)->toHaveKey('operator-panel')
        ->and($items)->not->toHaveKey('admin-panel');
});

it('shows admins a link to the admin panel from the operator menu', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin);
    Filament::setCurrentPanel('operator');

    $items = Filament::getPanel('operator')->getUserMenuItems();

    expect($items)->toHaveKey('admin-panel')
        ->and($items['admin-panel']->getUrl())->toBe(Filament::getPanel('admin')->getUrl());
});
