<?php

namespace App\Actions\UserManagement;

use App\Enums\LogoutReason;
use App\Models\User;
use App\Services\Auth\UserSessionService;
use Illuminate\Support\Facades\DB;

final class DeleteUser
{
    public function __construct(
        private readonly UserSessionService $userSessions,
    ) {}

    public function handle(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $this->userSessions->terminateAll($user, LogoutReason::Terminated);
            $user->delete();
        });
    }
}
