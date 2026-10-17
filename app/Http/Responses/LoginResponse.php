<?php

namespace App\Http\Responses;

use App\Services\Auth\LoginActivityService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    public function __construct(
        private readonly LoginActivityService $loginActivity,
    ) {}

    /**
     * Create an HTTP response that redirects the authenticated user to their area.
     *
     * @param  Request  $request
     */
    public function toResponse($request): RedirectResponse
    {
        $user = $request->user();

        $this->loginActivity->record($user, $request);

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return redirect()->intended(route('verification.notice'));
        }

        return redirect()->intended(route($user->role->landingRoute()));
    }
}
