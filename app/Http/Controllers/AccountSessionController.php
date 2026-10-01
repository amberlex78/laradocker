<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Auth\UserSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountSessionController extends Controller
{
    /**
     * Terminate all of the authenticated user's sessions except the current one.
     */
    public function __invoke(Request $request, UserSessionService $userSessions): RedirectResponse
    {
        $validated = $request->validateWithBag('terminateSessions', [
            'current_password' => ['required', 'current_password:web'],
        ]);

        /** @var User $user */
        $user = $request->user();

        DB::transaction(function () use ($request, $user, $userSessions, $validated): void {
            Auth::logoutOtherDevices($validated['current_password']);
            $userSessions->terminateOthers($user, $request->session()->getId());
        });

        return to_route('account')->with('status', 'active-sessions-terminated');
    }
}
