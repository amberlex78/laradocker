<?php

namespace App\Http\Responses;

use App\Services\Auth\LoginActivityService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Symfony\Component\HttpFoundation\Response;

class RegisterResponse implements RegisterResponseContract
{
    public function __construct(
        private readonly LoginActivityService $loginActivity,
    ) {}

    /**
     * Create the response after registration and record its authenticated session.
     *
     * @param  Request  $request
     */
    public function toResponse($request): Response
    {
        $user = $request->user();

        $this->loginActivity->record($user, $request);

        return $request->wantsJson()
            ? new JsonResponse('', 201)
            : ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()
                ? redirect()->route('verification.notice')
                : redirect()->route($user->role->landingRoute()));
    }
}
