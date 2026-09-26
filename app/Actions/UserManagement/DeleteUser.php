<?php

namespace App\Actions\UserManagement;

use App\Models\User;

final class DeleteUser
{
    public function handle(User $user): void
    {
        $user->delete();
    }
}
