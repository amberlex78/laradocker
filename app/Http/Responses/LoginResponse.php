<?php

namespace App\Http\Responses;

use App\Actions\Auth\ResolveUserLandingRoute;
use App\Services\Auth\LoginActivityService;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    public function __construct(
        private readonly ResolveUserLandingRoute $landingRoute,
        private readonly LoginActivityService $loginActivity,
    ) {}

    /**
     * Create an HTTP response that redirects the authenticated user to their area.
     *
     * @param  Request  $request
     */
    public function toResponse($request): RedirectResponse
    {
        $this->loginActivity->record($request->user(), $request);

        return redirect()->route($this->landingRoute->handle($request->user()));
    }
}
