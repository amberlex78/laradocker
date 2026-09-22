<?php

namespace App\Actions\Auth;

use App\Enums\UserRole;
use App\Models\User;

final class ResolveUserLandingRoute
{
    public function handle(User $user): string
    {
        return match ($user->role) {
            UserRole::Developer => 'developer.dashboard',
            UserRole::Admin, UserRole::Operator => 'admin.dashboard',
            UserRole::User => 'account',
        };
    }
}
