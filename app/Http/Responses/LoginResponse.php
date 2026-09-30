<?php

namespace App\Http\Responses;

use App\Actions\Auth\RecordUserLogin;
use App\Actions\Auth\ResolveUserLandingRoute;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    public function __construct(
        private readonly ResolveUserLandingRoute $landingRoute,
        private readonly RecordUserLogin $recordUserLogin,
    ) {}

    /**
     * Create an HTTP response that redirects the authenticated user to their area.
     *
     * @param  Request  $request
     */
    public function toResponse($request): RedirectResponse
    {
        $this->recordUserLogin->handle($request->user(), $request);

        return redirect()->route($this->landingRoute->handle($request->user()));
    }
}
