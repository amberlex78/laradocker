<?php

namespace App\Http\Middleware;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;

class RedirectGuestsToSiteLogin extends Authenticate
{
    protected function redirectTo(mixed $request): ?string
    {
        return Filament::getPanel('site')->getLoginUrl();
    }
}
