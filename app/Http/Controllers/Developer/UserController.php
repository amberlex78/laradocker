<?php

namespace App\Http\Controllers\Developer;

use App\Actions\UserManagement\CreateUser;
use App\Actions\UserManagement\DeleteUser;
use App\Actions\UserManagement\UpdateUser;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Developer\StoreUserRequest;
use App\Http\Requests\Developer\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('developer.users.index', [
            'users' => User::query()->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('developer.users.create', [
            'availableRoles' => UserRole::cases(),
        ]);
    }

    public function store(StoreUserRequest $request, CreateUser $createUser): RedirectResponse
    {
        $attributes = $request->validated();
        $role = UserRole::from($attributes['role']);

        Gate::authorize('create', [User::class, $role]);

        $createUser->handle([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
            'role' => $role,
        ]);

        return to_route('developer.users.index')->with('status', 'User created successfully.');
    }

    public function show(int|string $user): View
    {
        $target = $this->resolveUser($user);

        Gate::authorize('view', $target);

        return view('developer.users.show', ['user' => $target]);
    }

    public function edit(int|string $user): View
    {
        $target = $this->resolveUser($user);

        Gate::authorize('update', $target);

        return view('developer.users.edit', [
            'user' => $target,
            'availableRoles' => UserRole::cases(),
        ]);
    }

    public function update(UpdateUserRequest $request, int|string $user, UpdateUser $updateUser): RedirectResponse
    {
        $target = $this->resolveUser($user);

        Gate::authorize('update', $target);

        $attributes = $request->validated();
        $role = UserRole::from($attributes['role']);

        if ($target->role !== $role) {
            Gate::authorize('changeRole', [$target, $role]);
        }

        $updateUser->handle($target, [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => $attributes['password'] ?? null,
            'role' => $role,
        ]);

        return to_route('developer.users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(int|string $user, DeleteUser $deleteUser): RedirectResponse
    {
        $target = $this->resolveUser($user);

        Gate::authorize('delete', $target);
        $deleteUser->handle($target);

        return to_route('developer.users.index')->with('status', 'User deleted successfully.');
    }

    private function resolveUser(int|string $user): User
    {
        return User::query()->findOrFail($user);
    }
}
