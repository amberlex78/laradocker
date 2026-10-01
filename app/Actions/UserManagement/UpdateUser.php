<?php

namespace App\Actions\UserManagement;

use App\Enums\LogoutReason;
use App\Enums\UserRole;
use App\Models\User;
use App\Services\Auth\UserSessionService;
use Illuminate\Support\Facades\DB;

final class UpdateUser
{
    public function __construct(
        private readonly UserSessionService $userSessions,
    ) {}

    /**
     * Update a user from validated attributes.
     *
     * @param  array{name: string, email: string, password?: string|null, role: UserRole}  $attributes
     */
    public function handle(User $user, array $attributes): User
    {
        return DB::transaction(function () use ($user, $attributes): User {
            $updatedAttributes = [
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'role' => $attributes['role'],
            ];
            $passwordChanged = filled($attributes['password'] ?? null);

            if ($passwordChanged) {
                $updatedAttributes['password'] = $attributes['password'];
            }

            $user->fill($updatedAttributes);
            $user->save();

            if ($passwordChanged) {
                $this->userSessions->terminateAll($user, LogoutReason::PasswordChanged);
            }

            return $user;
        });
    }
}
