<?php

namespace App\Http\Responses;

use App\Enums\UserRole;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that redirects the authenticated user to their area.
     *
     * @param  mixed  $request
     */
    public function toResponse($request): RedirectResponse
    {
        $path = match ($request->user()->role) {
            UserRole::Developer => '/developer',
            UserRole::Admin, UserRole::Operator => '/admin',
            UserRole::User => '/account',
        };

        return redirect()->to($path);
    }
}
