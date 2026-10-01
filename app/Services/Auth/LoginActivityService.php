<?php

namespace App\Services\Auth;

use App\Enums\LogoutReason;
use App\Models\User;
use Illuminate\Http\Request;

final class LoginActivityService
{
    public function __construct(
        private readonly DeviceDetectionService $deviceDetection,
    ) {}

    /**
     * Store the successful login snapshot and associate it with the current session.
     */
    public function record(User $user, Request $request): void
    {
        $loginDetails = $this->deviceDetection->fromRequest($request);
        $loggedInAt = now();
        $ipAddress = $request->ip();
        $sessionId = $request->hasSession() ? $request->session()->getId() : null;

        $loginHistory = $user->loginHistories()->create([
            ...$loginDetails,
            'ip_address' => $ipAddress,
            'session_id' => $sessionId,
            'logged_in_at' => $loggedInAt,
        ]);

        if ($request->hasSession()) {
            $request->session()->put('login_history_id', $loginHistory->getKey());
        }
    }

    /**
     * Record an explicit logout for the authenticated session.
     */
    public function recordLogout(User $user, Request $request, LogoutReason $reason = LogoutReason::Logout): void
    {
        $loginHistoryId = $request->session()->pull('login_history_id');
        $loginHistoryQuery = $user->loginHistories()
            ->whereNull('logged_out_at');

        if ($loginHistoryId !== null) {
            $loginHistoryQuery->whereKey($loginHistoryId);
        } else {
            $loginHistoryQuery->where('session_id', $request->session()->getId());
        }

        $loginHistoryQuery->update([
            'logged_out_at' => now(),
            'logout_reason' => $reason->value,
        ]);
    }
}
