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
        $activeSessions = $userSessions->activeFor(
            $user,
            $request->session()->getId(),
        );
        $loginHistories = $user->loginHistories()
            ->orderByDesc('logged_in_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('account.index', [
            'user' => $user,
            'latestLoginHistory' => $user->loginHistories()
                ->orderByDesc('logged_in_at')
                ->orderByDesc('id')
                ->first(),
            'activeSessions' => $activeSessions,
            'activeSessionIds' => $userSessions->activeSessionIdsFor(
                $user,
                $loginHistories->getCollection()->pluck('session_id')->filter(),
            ),
            'currentLoginHistoryId' => $request->session()->get('login_history_id'),
            'loginHistories' => $loginHistories,
        ]);
    }
}
