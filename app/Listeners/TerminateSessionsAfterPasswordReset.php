<?php

namespace App\Listeners;

use App\Actions\Auth\TerminateUserSessions;
use App\Enums\LoginSessionEndReason;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;

class TerminateSessionsAfterPasswordReset
{
    public function __construct(private TerminateUserSessions $terminateUserSessions) {}

    public function handle(PasswordReset $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $this->terminateUserSessions->handle($event->user, LoginSessionEndReason::PasswordReset);
    }
}
