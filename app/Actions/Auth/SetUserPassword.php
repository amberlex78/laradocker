<?php

namespace App\Actions\Auth;

use App\Models\User;

final class SetUserPassword
{
    public function handle(User $user, string $password): void
    {
        $user->forceFill([
            'password' => $password,
        ])->save();
    }
}
