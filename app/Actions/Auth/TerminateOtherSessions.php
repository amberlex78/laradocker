<?php

namespace App\Actions\Auth;

use App\Enums\LoginSessionEndReason;
use App\Models\LoginSession;
use Filament\Facades\Filament;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\DB;
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

        $currentLoginSessionId = session()->get(LoginSession::SESSION_KEY);

        LoginSession::query()
            ->whereBelongsTo($user)
            ->whereNull('logged_out_at')
            ->when($currentLoginSessionId !== null, fn ($query) => $query->whereKeyNot($currentLoginSessionId))
            ->where(fn ($query) => $query
                ->whereNull('session_id')
                ->orWhere('session_id', '!=', session()->getId()))
            ->update([
                'logged_out_at' => now(),
                'end_reason' => LoginSessionEndReason::Terminated,
            ]);

        DB::connection(config('session.connection'))
            ->table((string) config('session.table'))
            ->where('user_id', $user->getAuthIdentifier())
            ->where('id', '!=', session()->getId())
            ->delete();
    }
}
