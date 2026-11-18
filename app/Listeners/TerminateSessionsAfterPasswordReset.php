<?php

namespace App\Listeners;

use App\Enums\LoginSessionEndReason;
use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;

class TerminateSessionsAfterPasswordReset
{
    public function handle(PasswordReset $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        LoginSession::query()
            ->whereBelongsTo($event->user)
            ->whereNull('logged_out_at')
            ->update([
                'logged_out_at' => now(),
                'end_reason' => LoginSessionEndReason::Terminated,
            ]);

        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))
            ->table((string) config('session.table'))
            ->where('user_id', $event->user->getAuthIdentifier())
            ->delete();
    }
}
