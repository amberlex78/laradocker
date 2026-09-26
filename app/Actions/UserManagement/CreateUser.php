<?php

namespace App\Actions\UserManagement;

use App\Enums\UserRole;
use App\Models\User;

final class CreateUser
{
    /**
     * Create a user from validated attributes.
     *
     * @param  array{name: string, email: string, password: string, role: UserRole}  $attributes
     */
    public function handle(array $attributes): User
    {
        return User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
            'role' => $attributes['role'],
        ]);
    }
}
