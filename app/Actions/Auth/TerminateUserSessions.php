<?php

namespace App\Actions\Auth;

use App\Enums\LoginSessionEndReason;
use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TerminateUserSessions
{
    public function handle(User $user, LoginSessionEndReason $reason, bool $preserveCurrentSession = false): void
    {
        $currentLoginSessionId = $preserveCurrentSession
            ? session()->get(LoginSession::SESSION_KEY)
            : null;
        $currentSessionId = $preserveCurrentSession ? session()->getId() : null;

        LoginSession::query()
            ->whereBelongsTo($user)
            ->active()
            ->when($currentLoginSessionId !== null, fn ($query) => $query->whereKeyNot($currentLoginSessionId))
            ->when($currentSessionId !== null, fn ($query) => $query
                ->where(fn ($query) => $query
                    ->whereNull('session_id')
                    ->orWhere('session_id', '!=', $currentSessionId)))
            ->update([
                'logged_out_at' => now(),
                'end_reason' => $reason,
            ]);

        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))
            ->table((string) config('session.table'))
            ->where('user_id', $user->getAuthIdentifier())
            ->when($currentSessionId !== null, fn ($query) => $query->where('id', '!=', $currentSessionId))
            ->delete();
    }
}
