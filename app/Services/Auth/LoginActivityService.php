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

        DB::transaction(function () use ($user, $loginDetails, $loggedInAt, $ipAddress): void {
            $user->forceFill([
                'last_login_device_type' => $loginDetails['device_type'],
                'last_login_device_model' => $loginDetails['device_model'],
                'last_login_os' => $loginDetails['operating_system'],
                'last_login_browser' => $loginDetails['browser'],
                'last_login_browser_version' => $loginDetails['browser_version'],
                'last_login_ip_address' => $ipAddress,
                'last_login_at' => $loggedInAt,
            ])->save();

            $user->loginHistories()->create([
                ...$loginDetails,
                'ip_address' => $ipAddress,
                'logged_in_at' => $loggedInAt,
            ]);
        });
    }
}
