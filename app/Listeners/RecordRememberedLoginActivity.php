<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\Auth\LoginActivityService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class RecordRememberedLoginActivity
{
    public function __construct(
        private readonly LoginActivityService $loginActivity,
    ) {}

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $request = request();

        if (
            ! $event->user instanceof User
            || ! $request->hasSession()
            || ! Auth::guard($event->guard)->viaRemember()
            || $request->session()->has('login_history_id')
        ) {
            return;
        }

        $this->loginActivity->record($event->user, $request);
    }
}
