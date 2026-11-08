<?php

namespace App\Listeners;

use App\Enums\LoginSessionEndReason;
use App\Models\LoginSession;
use Illuminate\Auth\Events\Logout;

class RecordLogout
{
    public function handle(Logout $event): void
    {
        $loginSessionId = session()->get(LoginSession::SESSION_KEY);

        if ($loginSessionId === null) {
            return;
        }

        LoginSession::query()
            ->whereKey($loginSessionId)
            ->whereNull('logged_out_at')
            ->update([
                'logged_out_at' => now(),
                'end_reason' => LoginSessionEndReason::LoggedOut,
            ]);

        session()->forget(LoginSession::SESSION_KEY);
    }
}
