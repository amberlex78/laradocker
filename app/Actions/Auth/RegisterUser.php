<?php

namespace App\Actions\Auth;

use App\Enums\UserRole;
use App\Models\User;

final class RegisterUser
{
    /**
     * Create a newly registered user from validated attributes.
     *
     * @param  array{name: string, email: string, password: string}  $attributes
     */
    public function handle(array $attributes): User
    {
        return User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
            'role' => UserRole::User,
        ]);
    }
}
