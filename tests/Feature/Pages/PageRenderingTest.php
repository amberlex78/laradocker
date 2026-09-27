<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the public home page renders', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('A Docker-ready Laravel foundation for whatever you build.')
        ->assertSee('View repository')
        ->assertSee('data-icon="github"', false)
        ->assertSee('href="https://github.com/amberlex78/laradocker"', false)
        ->assertSee('target="_blank"', false)
        ->assertSee('Create an account')
        ->assertDontSee('Try the demo')
        ->assertSee('Tailwind CSS + Flowbite for Laravel')
        ->assertSee('Tailwind CSS 4')
        ->assertSee('Flowbite 4')
        ->assertSee('Blade components')
        ->assertSee('Docker-first development')
        ->assertSee('Development and production')
        ->assertSee('Authentication and roles')
        ->assertSee('Clone the foundation. Build your own project.')
        ->assertDontSee('Everything your team needs to move forward.')
        ->assertDontSee('Workspace overview')
        ->assertDontSee('Ready to make your next step simpler?')
        ->assertSee('Toggle theme')
        ->assertSee('data-theme-init', false)
        ->assertSee("localStorage.getItem('color-theme'", false)
        ->assertSee('dark:bg-gray-900', false)
        ->assertSee('data-icon="user-plus"', false);
});

test('an authenticated admin sees the admin workspace menu on the public home page', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->get('/')
        ->assertOk()
        ->assertSee('Admin workspace')
        ->assertSee('href="'.route('admin.dashboard').'"', false)
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSee('x-data="{ profileMenuOpen: false }"', false);
});

test('an authenticated developer sees the developer workspace menu on the public home page', function (): void {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($developer)
        ->get('/')
        ->assertOk()
        ->assertSee('Developer workspace')
        ->assertSee('href="'.route('developer.dashboard').'"', false)
        ->assertSee('action="'.route('logout').'"', false);
});

test('an authenticated standard user sees profile and logout without a workspace menu on the public home page', function (): void {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $this->actingAs($user)
        ->get('/')
        ->assertOk()
        ->assertSee('Profile')
        ->assertSee('action="'.route('logout').'"', false)
        ->assertDontSee('Admin workspace')
        ->assertDontSee('Developer workspace')
        ->assertDontSee('href="'.route('admin.dashboard').'"', false)
        ->assertDontSee('href="'.route('developer.dashboard').'"', false);
});

test('the login page renders the Blade authentication screen', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Sign in to your account');
});

test('authentication pages render the Flowbite auth shell', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('data-default-theme="light"', false)
        ->assertSee('Sign in to your account')
        ->assertSee(config('app.name', 'Laravel'))
        ->assertSee('Toggle theme')
        ->assertDontSee('One workspace for your applications.');
});

test('authentication pages render named Lucide icons through the shared component', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('data-icon="sun"', false)
        ->assertSee('data-icon="moon"', false);
});

test('authentication forms only expose supported application fields', function () {
    $this->get('/login')
        ->assertOk()
        ->assertDontSee('Sign in with Google')
        ->assertDontSee('Sign in with X');

    $this->get('/register')
        ->assertOk()
        ->assertSee('Full name')
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
        ->assertSee('type="checkbox"', false)
        ->assertDontSee('checkboxToggle', false);

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

test('authentication forms use standard Flowbite inputs and server-side validation', function (): void {
    foreach (['/login', '/register', '/forgot-password', '/reset-password/test-token?email=user%40example.com'] as $path) {
        $this->get($path)
            ->assertOk()
            ->assertSee('focus:ring-blue-500', false)
            ->assertSee('focus:border-blue-500', false)
            ->assertDontSee('novalidate', false)
            ->assertDontSee('formValidation', false)
            ->assertDontSee('data-validation-field', false)
            ->assertDontSee('data-validation-confirm', false);
    }
});

test('a user sees the account profile settings page', function () {
    $user = User::factory()->create([
        'role' => UserRole::User,
    ]);

    $this->actingAs($user)
        ->get('/account')
        ->assertOk()
        ->assertSee('Profile information')
        ->assertSee('Update password')
        ->assertSee('Toggle theme')
        ->assertSee('data-theme-init', false)
        ->assertSee("localStorage.getItem('color-theme'", false)
        ->assertSee('flex flex-col gap-6', false)
        ->assertSee('id="profile-information"', false)
        ->assertSee('id="update-password"', false);
});

test('authorized account users can return to their workspace from the shared menu', function (): void {
    $workspaces = [
        UserRole::Admin->value => ['admin.dashboard', 'Admin workspace'],
        UserRole::Operator->value => ['admin.dashboard', 'Admin workspace'],
        UserRole::Developer->value => ['developer.dashboard', 'Developer workspace'],
    ];

    foreach ($workspaces as $role => [$workspaceRoute, $workspaceLabel]) {
        $user = User::factory()->create([
            'role' => UserRole::from($role),
        ]);

        $this->actingAs($user)
            ->get(route('account'))
            ->assertOk()
            ->assertSee('Profile')
            ->assertSee($workspaceLabel)
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
        ->assertSee('Admin navigation')
        ->assertSee('Overview')
        ->assertDontSee('Developer navigation');
});

test('an admin sees the responsive business dashboard shell', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Admin workspace')
        ->assertSee(now()->format('l, F j, Y'))
        ->assertSee('data-default-theme="dark"', false)
        ->assertSee('md:ms-64', false)
        ->assertSee('bg-gray-50', false)
        ->assertSee('grid gap-6 sm:grid-cols-2 xl:grid-cols-4', false)
        ->assertSee('rounded-base border border-gray-200 bg-white p-6 shadow-sm', false)
        ->assertSee('mt-6 grid gap-6 xl:grid-cols-2', false)
        ->assertDontSee('xl:col-span-2', false)
        ->assertDontSee('text-lg font-semibold text-gray-900 dark:text-white">Admin dashboard', false)
        ->assertSee('Admin dashboard')
        ->assertSee('data-icon="layout-dashboard"', false)
        ->assertDontSee('>Overview</h1>', false)
        ->assertSee('Overview')
        ->assertSee('Quick actions')
        ->assertSee('No recent activity yet')
        ->assertDontSee('rounded-2xl border border-slate-200', false)
        ->assertDontSee('text-indigo-600', false)
        ->assertDontSee('data-icon="shield-check"', false)
        ->assertDontSee('data-icon="activity"', false)
        ->assertDontSee('flex items-start justify-between gap-4', false)
        ->assertSee('sidebarOpen')
        ->assertSee('x-show="sidebarOpen"', false);
});

test('an admin shell renders the Flowbite sidebar and navbar structure', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertSee('data-default-theme="dark"', false)
        ->assertSee('aria-label="Admin sidebar"', false)
        ->assertSee('Admin workspace')
        ->assertSee('Open sidebar')
        ->assertSee('Close sidebar')
        ->assertSee('View public site')
        ->assertSee('md:ms-64', false)
        ->assertSee('bg-gray-800', false)
        ->assertSee(now()->format('l, F j, Y'))
        ->assertDontSee('min-h-screen bg-slate-50', false);
});

test('an admin dashboard exposes a users action beside its overview header', function (): void {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertSee('Manage Users')
        ->assertSee('href="'.route('admin.users.index').'"', false)
        ->assertSee('px-4 py-2.5 text-sm', false)
        ->assertSee('Workspace status')
        ->assertSee('Configured modules')
        ->assertSee('Open actions')
        ->assertSee('Activity events');
});

test('an operator dashboard does not expose the admin-only users action', function (): void {
    $operator = User::factory()->create([
        'role' => UserRole::Operator,
    ]);

    $this->actingAs($operator)
        ->get(route('admin.dashboard'))
        ->assertSee('Admin dashboard')
        ->assertDontSee('Manage Users')
        ->assertDontSee('href="'.route('admin.users.index').'"', false);
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
        ->assertSee('divide-y divide-gray-100', false)
        ->assertDontSee('Back to workspace')
        ->assertSee('Open user menu for Ada Lovelace');
});

test('workspace user menus expose a public site link', function (): void {
    $workspaces = [
        [UserRole::Admin, 'admin.dashboard'],
        [UserRole::Developer, 'developer.dashboard'],
    ];

    foreach ($workspaces as [$role, $workspaceRoute]) {
        $user = User::factory()->create([
            'role' => $role,
        ]);

        $this->actingAs($user)
            ->get(route($workspaceRoute))
            ->assertOk()
            ->assertSee('Public site')
            ->assertSee('href="'.route('home').'" role="menuitem"', false);
    }
});

test('a developer sees the developer shell and can open the admin shell', function () {
    $developer = User::factory()->create([
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($developer)
        ->get('/developer')
        ->assertOk()
        ->assertSee('Developer navigation')
        ->assertSee('Developer workspace')
        ->assertDontSee('Back to workspace')
        ->assertSee('bg-gray-800', false);

    $this->actingAs($developer)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Admin navigation');
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
        ->assertSee('data-default-theme="dark"', false)
        ->assertSee('Developer workspace')
        ->assertSee(now()->format('l, F j, Y'))
        ->assertSee('grid gap-6 sm:grid-cols-2 xl:grid-cols-4', false)
        ->assertSee('rounded-base border border-gray-200 bg-white p-6 shadow-sm', false)
        ->assertDontSee('flex items-start justify-between gap-4', false)
        ->assertDontSee('text-slate-600', false)
        ->assertDontSee('rounded-xl border border-slate-200', false)
        ->assertSee('data-icon="layout-dashboard"', false)
        ->assertDontSee('text-lg font-semibold text-gray-900 dark:text-white">Developer dashboard', false)
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
            ->assertSee('data-theme-init', false)
            ->assertSee('$store.theme.toggle()', false);
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
        ->assertSee('rounded-base p-2', false)
        ->assertSee('ms-3', false);
});
