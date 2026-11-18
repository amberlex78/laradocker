<?php

namespace App\Actions\Auth;

use App\Enums\LoginSessionEndReason;
use Filament\Facades\Filament;
use Illuminate\Auth\SessionGuard;
use LogicException;

class TerminateOtherSessions
{
    public function __construct(private TerminateUserSessions $terminateUserSessions) {}

    public function handle(string $currentPassword): void
    {
        $guard = Filament::auth();

        if (! $guard instanceof SessionGuard) {
            throw new LogicException('The active Filament guard must be a session guard.');
        }

        $user = $guard->user();

        if ($user === null) {
            return;
        }

        $guard->logoutOtherDevices($currentPassword);

        session()->put([
            'password_hash_'.Filament::getAuthGuard() => $user->getAuthPassword(),
        ]);

        $this->terminateUserSessions->handle(
            $user,
            LoginSessionEndReason::ManuallyTerminated,
            preserveCurrentSession: true,
        );
    }
}
