<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Auth\UserSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountSessionController extends Controller
{
    /**
     * Terminate all of the authenticated user's sessions except the current one.
     */
    public function __invoke(Request $request, UserSessionService $userSessions): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $userSessions->terminateOthers($user, $request->session()->getId());

        return to_route('account')->with('status', 'active-sessions-terminated');
    }
}
