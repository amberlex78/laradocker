<?php

namespace App\Actions\UserManagement;

use App\Enums\UserRole;
use App\Models\User;

final class UpdateUser
{
    /**
     * Update a user from validated attributes.
     *
     * @param  array{name: string, email: string, password?: string|null, role: UserRole}  $attributes
     */
    public function handle(User $user, array $attributes): User
    {
        $updatedAttributes = [
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'role' => $attributes['role'],
        ];

        if (filled($attributes['password'] ?? null)) {
            $updatedAttributes['password'] = $attributes['password'];
        }

        $user->fill($updatedAttributes);
        $user->save();

        return $user;
    }
}
