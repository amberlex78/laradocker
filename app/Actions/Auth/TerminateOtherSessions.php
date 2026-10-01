<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final class TerminateOtherSessions
{
    /**
     * Remove every database session for the user except the current session.
     */
    public function handle(User $user, string $currentSessionId): void
    {
        DB::table('sessions')
            ->where('user_id', $user->getKey())
            ->where('id', '<>', $currentSessionId)
            ->delete();
    }
}
