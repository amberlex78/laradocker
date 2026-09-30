<?php

namespace App\Actions\Auth;

use App\Models\User;
use DeviceDetector\DeviceDetector;
use Illuminate\Http\Request;

final class RecordUserLogin
{
    /**
     * Store the latest device information for an authenticated user.
     */
    public function handle(User $user, Request $request): void
    {
        $detector = new DeviceDetector($request->userAgent() ?? '');
        $detector->parse();

        $user->forceFill([
            'last_login_device_type' => $detector->getDeviceName() ?: 'unknown',
            'last_login_os' => $this->nullableDetectionValue($detector->getOs('name')),
            'last_login_browser' => $this->nullableDetectionValue($detector->getClient('name')),
            'last_login_at' => now(),
        ])->save();
    }

    private function nullableDetectionValue(mixed $value): ?string
    {
        if (! is_string($value) || $value === '' || $value === DeviceDetector::UNKNOWN) {
            return null;
        }

        return $value;
    }
}
