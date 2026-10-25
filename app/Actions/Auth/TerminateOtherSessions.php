<?php

namespace App\Actions\Auth;

use App\Models\UserSession;
use Filament\Facades\Filament;
use Illuminate\Auth\SessionGuard;
use LogicException;

class TerminateOtherSessions
{
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

        UserSession::query()
            ->where('user_id', $user->getAuthIdentifier())
            ->whereKeyNot(session()->getId())
            ->delete();
    }
}
