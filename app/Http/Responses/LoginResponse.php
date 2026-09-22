<?php

namespace App\Http\Responses;

use App\Actions\Auth\ResolveUserLandingRoute;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    public function __construct(private readonly ResolveUserLandingRoute $landingRoute) {}

    /**
     * Create an HTTP response that redirects the authenticated user to their area.
     *
     * @param  mixed  $request
     */
    public function toResponse($request): RedirectResponse
    {
        return redirect()->route($this->landingRoute->handle($request->user()));
    }
}
