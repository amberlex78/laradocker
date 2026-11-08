<?php

namespace App\Listeners;

use App\Actions\Auth\EndLoginSession;
use Illuminate\Auth\Events\Logout;

class RecordLogout
{
    public function __construct(private EndLoginSession $endLoginSession) {}

    public function handle(Logout $event): void
    {
        $this->endLoginSession->handle();
    }
}
