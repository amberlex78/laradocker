<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Auth\UserSessionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Render account settings with login history and active sessions.
     */
    public function __invoke(Request $request, UserSessionService $userSessions): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('account.index', [
            'user' => $user,
            'activeSessions' => $userSessions->activeFor(
                $user,
                $request->session()->getId(),
            ),
            'loginHistories' => $user->loginHistories()
                ->orderByDesc('logged_in_at')
                ->paginate(10),
        ]);
    }
}
