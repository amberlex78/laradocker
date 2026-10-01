<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class LoginActivityService
{
    public function __construct(
        private readonly DeviceDetectionService $deviceDetection,
    ) {}

    /**
     * Record the latest login details and append a login-history record atomically.
     *
     * The user snapshot and history entry must describe the same successful login.
     */
    public function record(User $user, Request $request): void
    {
        $loginDetails = $this->deviceDetection->fromRequest($request);
        $loggedInAt = now();
        $ipAddress = $request->ip();
        $sessionId = $request->hasSession() ? $request->session()->getId() : null;

        $loginHistoryId = DB::transaction(function () use ($user, $loginDetails, $loggedInAt, $ipAddress, $sessionId): int {
            $user->forceFill([
                'last_login_device_type' => $loginDetails['device_type'],
                'last_login_device_model' => $loginDetails['device_model'],
                'last_login_os' => $loginDetails['operating_system'],
                'last_login_browser' => $loginDetails['browser'],
                'last_login_browser_version' => $loginDetails['browser_version'],
                'last_login_ip_address' => $ipAddress,
                'last_login_at' => $loggedInAt,
            ])->save();

            $loginHistory = $user->loginHistories()->create([
                ...$loginDetails,
                'ip_address' => $ipAddress,
                'session_id' => $sessionId,
                'logged_in_at' => $loggedInAt,
            ]);

            return (int) $loginHistory->getKey();
        });

        if ($request->hasSession()) {
            $request->session()->put('login_history_id', $loginHistoryId);
        }
    }

    /**
     * Record an explicit logout for the authenticated session.
     */
    public function recordLogout(User $user, Request $request): void
    {
        $loginHistoryId = $request->session()->pull('login_history_id');
        $loginHistoryQuery = $user->loginHistories()
            ->whereNull('logged_out_at');

        if ($loginHistoryId !== null) {
            $loginHistoryQuery->whereKey($loginHistoryId);
        } else {
            $loginHistoryQuery->where('session_id', $request->session()->getId());
        }

        $loginHistoryQuery->update(['logged_out_at' => now()]);
    }
}
