<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\Auth\LoginActivityService;
use Illuminate\Auth\Events\Logout;

class RecordLogoutActivity
{
    public function __construct(
        private readonly LoginActivityService $loginActivity,
    ) {}

    /**
     * Record the explicit logout for the session before it is invalidated.
     */
    public function handle(Logout $event): void
    {
        $request = request();

        if (! $event->user instanceof User || ! $request->hasSession()) {
            return;
        }

        $this->loginActivity->recordLogout($event->user, $request);
    }
}
