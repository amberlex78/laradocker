<?php

namespace App\Listeners;

use App\Actions\Auth\StartLoginSession;
use Illuminate\Auth\Events\Login;

class RecordLogin
{
    public function __construct(private StartLoginSession $startLoginSession) {}

    public function handle(Login $event): void
    {
        $this->startLoginSession->handle($event->user, $event->guard, request());
    }
}
