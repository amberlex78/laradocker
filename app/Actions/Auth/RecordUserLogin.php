<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Http\Request;

final class RecordUserLogin
{
    public function __construct(
        private readonly ResolveDeviceInformation $deviceInformation,
    ) {}

    /**
     * Store the latest device information for an authenticated user.
     */
    public function handle(User $user, Request $request): void
    {
        $loginDetails = $this->deviceInformation->fromRequest($request);
        $loggedInAt = now();

        $user->forceFill([
            'last_login_device_type' => $loginDetails['device_type'],
            'last_login_device_model' => $loginDetails['device_model'],
            'last_login_os' => $loginDetails['operating_system'],
            'last_login_browser' => $loginDetails['browser'],
            'last_login_browser_version' => $loginDetails['browser_version'],
            'last_login_ip_address' => $request->ip(),
            'last_login_at' => $loggedInAt,
        ])->save();

        $user->loginHistories()->create([
            ...$loginDetails,
            'ip_address' => $request->ip(),
            'logged_in_at' => $loggedInAt,
        ]);
    }
}
