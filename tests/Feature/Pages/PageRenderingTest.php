<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the public home page renders', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Welcome')
        ->assertSee('Toggle theme')
        ->assertSee('darkMode')
        ->assertSee("localStorage.setItem('theme'", false)
        ->assertSee('dark:bg-slate-950', false);
});

test('the login page renders the Blade authentication screen', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Log in to your account');
});

test('authentication pages render the branded responsive shell', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Back to LaDocker')
        ->assertSee('One workspace for your applications.')
        ->assertSee('Toggle theme');
});

test('authentication pages render named Lucide icons through the shared component', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('data-icon="sun"', false)
        ->assertSee('data-icon="moon"', false)
        ->assertSee('data-icon="eye"', false)
        ->assertSee('data-icon="eye-off"', false);
});

test('authentication forms only expose supported application fields', function () {
    $this->get('/login')
        ->assertOk()
        ->assertDontSee('Sign in with Google')
        ->assertDontSee('Sign in with X');

    $this->get('/register')
        ->assertOk()
        ->assertSee('Name')
        ->assertDontSee('First Name')
        ->assertDontSee('Terms and Conditions');
});

test('authentication views preserve the Fortify form contracts', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('action="'.route('login').'"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertSee('name="remember"', false)
        ->assertSee('class="sr-only"', false)
        ->assertSee('checkboxToggle', false);

    $this->get('/register')
        ->assertOk()
        ->assertSee('action="'.route('register').'"', false)
        ->assertSee('name="name"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="password_confirmation"', false);

    $this->get('/forgot-password')
        ->assertOk()
        ->assertSee('action="'.route('password.email').'"', false)
        ->assertSee('name="email"', false);

    $this->get('/reset-password/test-token?email=user%40example.com')
        ->assertOk()
        ->assertSee('action="'.route('password.update').'"', false)
        ->assertSee('name="token"', false)
        ->assertSee('name="password_confirmation"', false);

    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get('/email/verify')
        ->assertOk()
        ->assertSee('action="'.route('verification.send').'"', false)
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSee($user->email);
});

test('a user sees the account page', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $this->actingAs($user)
        ->get('/account')
        ->assertOk()
        ->assertSee('Your account')
        ->assertSee('Toggle theme')
        ->assertSee('darkMode')
        ->assertSee("localStorage.setItem('theme'", false);
});

test('authorized account users can return to their workspace from the shared menu', function (): void {
    $workspaces = [
        UserRole::Admin->value => 'admin.dashboard',
        UserRole::Operator->value => 'admin.dashboard',
        UserRole::Developer->value => 'developer.dashboard',
    ];

    foreach ($workspaces as $role => $workspaceRoute) {
        $user = User::factory()->create([
            'role' => UserRole::from($role),
        ]);

        $this->actingAs($user)
            ->get(route('account'))
            ->assertOk()
            ->assertSee('Profile')
            ->assertSee('Back to workspace')
            ->assertSee('href="'.route($workspaceRoute).'"', false)
            ->assertSee('x-data="{ profileMenuOpen: false }"', false);
    }
});

test('standard account users do not see a workspace link in the shared menu', function (): void {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $this->actingAs($user)
        ->get(route('account'))
        ->assertOk()
        ->assertSee('Profile')
        ->assertDontSee('Back to workspace')
        ->assertDontSee('href="'.route('admin.dashboard').'"', false)
        ->assertDontSee('href="'.route('developer.dashboard').'"', false);
});

test('an admin sees the admin shell without developer navigation', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Admin workspace navigation')
        ->assertSee('Overview')
        ->assertDontSee('Developer workspace navigation');
});

test('an admin sees the responsive business dashboard shell', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Admin workspace')
        ->assertDontSee('Business workspace')
        ->assertDontSee('text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600', false)
        ->assertSee('Admin dashboard')
        ->assertSee('data-icon="layout-dashboard"', false)
        ->assertDontSee('>Overview</h1>', false)
        ->assertSee('Overview')
        ->assertSee('Quick actions')
        ->assertSee('No recent activity yet')
        ->assertSee('sidebarOpen')
        ->assertSee('x-show="sidebarOpen"', false);
});

test('workspace header exposes the authenticated user menu', function (): void {
    $admin = User::factory()->create([
        'name' => 'Ada Lovelace',
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Ada Lovelace')
        ->assertSee('Profile')
        ->assertSee('href="'.route('account').'"', false)
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSee('x-data="{ profileMenuOpen: false }"', false)
        ->assertSee('x-show="profileMenuOpen"', false)
        ->assertSee('mt-2 border-t border-slate-100 pt-2 dark:border-slate-800', false)
        ->assertDontSee('Back to workspace')
        ->assertSee('data-icon="chevron-down"', false);
});

test('a developer sees the developer shell and can open the admin shell', function () {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($developer)
        ->get('/developer')
        ->assertOk()
        ->assertSee('Developer workspace navigation')
        ->assertSee('Developer workspace')
        ->assertDontSee('Back to workspace')
        ->assertDontSee('text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600', false);

    $this->actingAs($developer)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Admin workspace navigation');
});

test('a developer sees the technical dashboard shell', function () {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($developer)
        ->get('/developer')
        ->assertOk()
        ->assertSee('Application status')
        ->assertSee('Technical workspace')
        ->assertDontSee('text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600', false)
        ->assertSee('No technical events yet')
        ->assertSee('sidebarOpen')
        ->assertSee('x-show="sidebarOpen"', false);
});

test('admin and developer workspaces expose the shared theme toggle', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    foreach ([[$admin, '/admin'], [$developer, '/developer']] as [$user, $path]) {
        $this->actingAs($user)
            ->get($path)
            ->assertOk()
            ->assertSee('Toggle theme')
            ->assertSee('darkMode')
            ->assertSee("localStorage.setItem('theme'", false);
    }
});

test('workspace navigation uses compact direct icons', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertDontSee('flex h-8 w-8 items-center justify-center rounded-lg')
        ->assertSee('flex h-6 w-6 shrink-0 items-center justify-center');
});
