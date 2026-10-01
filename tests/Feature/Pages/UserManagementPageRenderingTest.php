<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin navigation exposes only the admin user-management namespace', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Users')
        ->assertSee('href="'.route('admin.users.index').'"', false)
        ->assertDontSee(route('developer.users.index'));
});

test('developer navigation exposes the developer user-management namespace', function (): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);

    $this->actingAs($developer)
        ->get(route('developer.dashboard'))
        ->assertOk()
        ->assertSee('Users')
        ->assertSee('href="'.route('developer.users.index').'"', false)
        ->assertDontSee(route('admin.users.index'));
});

test('user index actions use the page header action slot', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $developer = User::factory()->create(['role' => UserRole::Developer]);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('<div class="shrink-0">', false)
        ->assertSee('Users Management')
        ->assertSee('href="'.route('admin.users.create').'"', false)
        ->assertSee('Add User')
        ->assertSee('data-icon="user-plus"', false)
        ->assertSee('px-4 py-2.5 text-sm', false);

    $this->actingAs($developer)
        ->get(route('developer.users.index'))
        ->assertOk()
        ->assertSee('<div class="shrink-0">', false)
        ->assertSee('Users Management')
        ->assertSee('href="'.route('developer.users.create').'"', false)
        ->assertSee('Add User')
        ->assertSee('data-icon="user-plus"', false)
        ->assertSee('px-4 py-2.5 text-sm', false);
});

test('user index status alerts have spacing before the users table', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $developer = User::factory()->create(['role' => UserRole::Developer]);

    foreach ([[$admin, 'admin.users.index', 'Workspace users'], [$developer, 'developer.users.index', 'All application users']] as [$actor, $route, $cardTitle]) {
        $this->actingAs($actor)
            ->withSession(['status' => 'User deleted successfully.'])
            ->get(route($route))
            ->assertSee('<div class="space-y-4">', false)
            ->assertSeeInOrder(['role="status"', $cardTitle], false);
    }
});

test('admin and developer user indexes use the shared Flowbite table structure', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $developer = User::factory()->create(['role' => UserRole::Developer]);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('relative overflow-x-auto rounded-base border border-gray-200 shadow-sm', false)
        ->assertSee('bg-gray-50 text-xs uppercase text-gray-700', false)
        ->assertDontSee('border-slate', false)
        ->assertDontSee('bg-indigo', false)
        ->assertDontSee('rounded-xl', false);

    $this->actingAs($developer)
        ->get(route('developer.users.index'))
        ->assertOk()
        ->assertSee('relative overflow-x-auto rounded-base', false)
        ->assertDontSee('relative overflow-x-auto rounded-base border border-gray-200 shadow-sm', false)
        ->assertSee('bg-gray-50 text-xs uppercase text-gray-700', false)
        ->assertDontSee('border-slate', false)
        ->assertDontSee('bg-indigo', false)
        ->assertDontSee('rounded-xl', false);
});

test('admin and developer user indexes use the reference Flowbite table pagination', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    User::factory()->count(16)->create(['role' => UserRole::User]);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('<thead', false)
        ->assertSee('Table pagination', false)
        ->assertSee('Showing', false)
        ->assertSee('font-semibold text-gray-900 dark:text-white">17</span>', false)
        ->assertSee('aria-current="page"', false)
        ->assertSee('Next', false);

    $developer = User::factory()->create(['role' => UserRole::Developer]);
    User::factory()->count(16)->create(['role' => UserRole::User]);

    $this->actingAs($developer)
        ->get(route('developer.users.index'))
        ->assertOk()
        ->assertSee('<thead', false)
        ->assertSee('Table pagination', false)
        ->assertSee('Showing', false)
        ->assertSee('font-semibold text-gray-900 dark:text-white">34</span>', false)
        ->assertSee('aria-current="page"', false)
        ->assertSee('Next', false);
});

test('admin user pages show business roles and exclude developer records', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $businessUser = User::factory()->create([
        'name' => "O'Reilly <script>alert('xss')</script>",
        'email' => 'business@example.com',
        'role' => UserRole::User,
    ]);
    $developer = User::factory()->create([
        'name' => 'Hidden Developer',
        'email' => 'hidden-developer@example.com',
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee($businessUser->email)
        ->assertSee('&lt;script&gt;alert(&#039;xss&#039;)&lt;/script&gt;', false)
        ->assertDontSee("<script>alert('xss')</script>", false)
        ->assertDontSee($developer->name)
        ->assertDontSee($developer->email)
        ->assertDontSee('Developer');
});

test('developer user pages show developer records and role badges', function (): void {
    $developer = User::factory()->create([
        'name' => 'Technical Developer',
        'role' => UserRole::Developer,
    ]);

    $this->actingAs($developer)
        ->get(route('developer.users.index'))
        ->assertOk()
        ->assertSee($developer->name)
        ->assertSee('Developer');
});

test('user role badges keep regular users gray while admins use the accent color', function (): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    User::factory()->create(['role' => UserRole::Admin]);
    User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($developer)
        ->get(route('developer.users.index'))
        ->assertSee('bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300', false)
        ->assertSee('bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300', false);
});

test('developer user pages protect the current developer from self-deletion', function (): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $target = User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($developer)
        ->get(route('developer.users.index'))
        ->assertSee('data-icon="shield-check"', false)
        ->assertSee('aria-label="Protected"', false)
        ->assertDontSee('>Protected<', false)
        ->assertDontSee('action="'.route('developer.users.destroy', $developer).'"', false)
        ->assertSee('action="'.route('developer.users.destroy', $target).'"', false);

    $this->actingAs($developer)
        ->get(route('developer.users.show', $developer))
        ->assertSee('Protected')
        ->assertDontSee('action="'.route('developer.users.destroy', $developer).'"', false);

    $this->actingAs($developer)
        ->get(route('developer.users.edit', $developer))
        ->assertSee('Protected')
        ->assertDontSee('value="DELETE"', false);
});

test('user deletion controls render confirmation modals instead of native confirms', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $adminTarget = User::factory()->create(['role' => UserRole::User]);
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $developerTarget = User::factory()->create(['role' => UserRole::User]);

    foreach ([
        [$admin, 'admin.users.index', 'admin.users.destroy', $adminTarget],
        [$admin, 'admin.users.show', 'admin.users.destroy', $adminTarget],
        [$admin, 'admin.users.edit', 'admin.users.destroy', $adminTarget],
        [$developer, 'developer.users.index', 'developer.users.destroy', $developerTarget],
        [$developer, 'developer.users.show', 'developer.users.destroy', $developerTarget],
        [$developer, 'developer.users.edit', 'developer.users.destroy', $developerTarget],
    ] as [$actor, $route, $destroyRoute, $target]) {
        $this->actingAs($actor)
            ->get(route($route, str_ends_with($route, '.index') ? [] : $target))
            ->assertSee('role="dialog"', false)
            ->assertSee('aria-modal="true"', false)
            ->assertSee('data-icon="circle-alert"', false)
            ->assertSee('Are you sure you want to delete '.$target->name.'?', false)
            ->assertSee('This action cannot be undone.')
            ->assertSee("Yes, I'm sure")
            ->assertSee('No, cancel')
            ->assertSee('action="'.route($destroyRoute, $target).'"', false)
            ->assertDontSee("onsubmit=\"return confirm('Delete this user?')\"", false);
    }
});

test('user details pages use a text edit action in the page header', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $adminTarget = User::factory()->create(['role' => UserRole::User]);
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $developerTarget = User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($admin)
        ->get(route('admin.users.show', $adminTarget))
        ->assertOk()
        ->assertSee('<div class="shrink-0">', false)
        ->assertSee('href="'.route('admin.users.edit', $adminTarget).'"', false)
        ->assertSee('Edit User')
        ->assertSee('data-icon="file-pen"', false)
        ->assertSee('px-4 py-2.5 text-sm', false)
        ->assertDontSee('aria-label="Edit user"', false);

    $this->actingAs($developer)
        ->get(route('developer.users.show', $developerTarget))
        ->assertOk()
        ->assertSee('<div class="shrink-0">', false)
        ->assertSee('href="'.route('developer.users.edit', $developerTarget).'"', false)
        ->assertSee('Edit User')
        ->assertSee('data-icon="file-pen"', false)
        ->assertSee('px-4 py-2.5 text-sm', false)
        ->assertDontSee('aria-label="Edit user"', false);
});

test('user details pages share the overview two-column card grid', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $adminTarget = User::factory()->create(['role' => UserRole::User]);
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $developerTarget = User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($admin)
        ->get(route('admin.users.show', $adminTarget))
        ->assertOk()
        ->assertSee('class="mt-6 grid gap-6 xl:grid-cols-2"', false)
        ->assertSee('User details')
        ->assertSee('Danger zone')
        ->assertSee('Delete user');

    $this->actingAs($admin)
        ->get(route('admin.users.show', $admin))
        ->assertOk()
        ->assertSee('class="mt-6 grid gap-6 xl:grid-cols-2"', false)
        ->assertSee('Account protection')
        ->assertDontSee('Danger zone');

    $this->actingAs($developer)
        ->get(route('developer.users.show', $developerTarget))
        ->assertOk()
        ->assertSee('class="mt-6 grid gap-6 xl:grid-cols-2"', false)
        ->assertSee('User details')
        ->assertSee('Danger zone')
        ->assertSee('Delete user');

    $this->actingAs($developer)
        ->get(route('developer.users.show', $developer))
        ->assertOk()
        ->assertSee('class="mt-6 grid gap-6 xl:grid-cols-2"', false)
        ->assertSee('Account protection')
        ->assertDontSee('Danger zone');
});

test('user management actions render named Lucide icons', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $target = User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('data-icon="user-plus"', false)
        ->assertSee('href="'.route('admin.users.show', $target).'"', false)
        ->assertSee('aria-label="View user"', false)
        ->assertSee('data-icon="eye"', false)
        ->assertSee('data-icon="file-pen"', false)
        ->assertSee('data-icon="trash"', false)
        ->assertSee('data-icon="shield-check"', false)
        ->assertSee('aria-label="Protected"', false)
        ->assertDontSee('>Protected<', false);

    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $developerTarget = User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($developer)
        ->get(route('developer.users.index'))
        ->assertOk()
        ->assertSee('href="'.route('developer.users.show', $developerTarget).'"', false)
        ->assertSee('aria-label="View user"', false)
        ->assertSee('data-icon="eye"', false);

    $this->actingAs($admin)
        ->get(route('admin.users.edit', $target))
        ->assertOk()
        ->assertSee('data-icon="save"', false)
        ->assertSee('data-icon="arrow-left"', false)
        ->assertSee('data-icon="trash"', false)
        ->assertSee('data-icon="file-pen"', false)
        ->assertSee('Delete user');
});

test('admin create and edit forms expose business roles and delete controls', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $target = User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($admin)
        ->get(route('admin.users.create'))
        ->assertOk()
        ->assertSee('name="name"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertSee('value="admin"', false)
        ->assertSee('value="operator"', false)
        ->assertSee('value="user"', false)
        ->assertDontSee('value="developer"', false);

    $this->actingAs($admin)
        ->get(route('admin.users.edit', $target))
        ->assertOk()
        ->assertSee('name="_method" value="PUT"', false)
        ->assertSee('action="'.route('admin.users.destroy', $target).'"', false)
        ->assertSee('value="DELETE"', false);
});

test('user management forms use standard Flowbite inputs and server-side validation', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $adminTarget = User::factory()->create(['role' => UserRole::User]);
    $developerTarget = User::factory()->create(['role' => UserRole::User]);

    foreach ([
        route('admin.users.create'),
        route('admin.users.edit', $adminTarget),
        route('developer.users.create'),
        route('developer.users.edit', $developerTarget),
    ] as $path) {
        $user = str_contains($path, '/admin/') ? $admin : $developer;

        $this->actingAs($user)
            ->get($path)
            ->assertOk()
            ->assertSee('focus:ring-blue-500', false)
            ->assertSee('focus:border-blue-500', false)
            ->assertDontSee('novalidate', false)
            ->assertDontSee('formValidation', false)
            ->assertDontSee('data-validation-field', false)
            ->assertDontSee('data-validation-confirm', false);
    }
});

test('developer forms expose every role including developer', function (): void {
    $developer = User::factory()->create(['role' => UserRole::Developer]);

    $this->actingAs($developer)
        ->get(route('developer.users.create'))
        ->assertOk()
        ->assertSee('value="developer"', false)
        ->assertSee('value="admin"', false)
        ->assertSee('value="operator"', false)
        ->assertSee('value="user"', false);
});

test('user forms render the role select with the shared Flowbite select treatment', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get(route('admin.users.create'))
        ->assertOk()
        ->assertSee('class="block w-full rounded-base border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500', false)
        ->assertDontSee('appearance-none', false);
});

test('edit pages place the danger zone in the second column without empty create placeholders', function (): void {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $adminTarget = User::factory()->create(['role' => UserRole::User]);
    $developer = User::factory()->create(['role' => UserRole::Developer]);
    $developerTarget = User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($admin)
        ->get(route('admin.users.create'))
        ->assertSee('lg:w-1/2', false)
        ->assertDontSee('min-h-[420px]', false)
        ->assertDontSee('Danger zone');

    $this->actingAs($admin)
        ->get(route('admin.users.edit', $adminTarget))
        ->assertSee('lg:grid-cols-2', false)
        ->assertSee('Danger zone')
        ->assertSee('Delete user');

    $this->actingAs($admin)
        ->get(route('admin.users.edit', $admin))
        ->assertSee('lg:grid-cols-2', false)
        ->assertSee('Account protection')
        ->assertSee('Protected')
        ->assertDontSee('Danger zone');

    $this->actingAs($developer)
        ->get(route('developer.users.create'))
        ->assertSee('lg:w-1/2', false)
        ->assertDontSee('min-h-[420px]', false)
        ->assertDontSee('Danger zone');

    $this->actingAs($developer)
        ->get(route('developer.users.edit', $developerTarget))
        ->assertSee('lg:grid-cols-2', false)
        ->assertSee('Danger zone')
        ->assertSee('Delete user');
});
