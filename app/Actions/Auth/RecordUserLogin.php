<?php

namespace App\Actions\Auth;

use App\Models\User;
use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use Illuminate\Http\Request;

final class RecordUserLogin
{
    /**
     * Store the latest device information for an authenticated user.
     */
    public function handle(User $user, Request $request): void
    {
        $detector = new DeviceDetector(
            $request->userAgent() ?? '',
            ClientHints::factory($this->clientHintHeaders($request)),
        );
        $detector->parse();

        $deviceType = $this->resolveDeviceType($detector->getDeviceName(), $request);
        $loggedInAt = now();
        $loginDetails = [
            'device_type' => $deviceType,
            'device_model' => $this->resolveDeviceModel($detector, $request, $deviceType),
            'operating_system' => $this->nullableDetectionValue($detector->getOs('name')),
            'browser' => $this->nullableDetectionValue($detector->getClient('name')),
            'browser_version' => $this->nullableDetectionValue($detector->getClient('version')),
            'ip_address' => $request->ip(),
        ];

        $user->forceFill([
            'last_login_device_type' => $deviceType,
            'last_login_device_model' => $loginDetails['device_model'],
            'last_login_os' => $loginDetails['operating_system'],
            'last_login_browser' => $loginDetails['browser'],
            'last_login_browser_version' => $loginDetails['browser_version'],
            'last_login_ip_address' => $loginDetails['ip_address'],
            'last_login_at' => $loggedInAt,
        ])->save();

        $user->loginHistories()->create([
            ...$loginDetails,
            'logged_in_at' => $loggedInAt,
        ]);
    }

    private function resolveDeviceType(string $detectedDeviceType, Request $request): string
    {
        if ($detectedDeviceType === 'desktop'
            && $request->string('device_type_hint')->toString() === 'tablet') {
            return 'tablet';
        }

        return $detectedDeviceType ?: 'unknown';
    }

    private function resolveDeviceModel(DeviceDetector $detector, Request $request, string $deviceType): ?string
    {
        $model = $detector->getModel();

        if ($model === '' && $deviceType === 'tablet') {
            $model = $request->string('device_model_hint')->toString();
        }

        return $this->nullableDetectionValue($model);
    }

    /**
     * Normalize Symfony's header arrays for Device Detector's Client Hints factory.
     *
     * @return array<string, mixed>
     */
    private function clientHintHeaders(Request $request): array
    {
        $headers = $request->headers->all();

        foreach ($headers as $name => $values) {
            if (is_array($values) && count($values) === 1) {
                $headers[$name] = $values[0];
            }
        }

        return array_merge($request->server->all(), $headers);
    }

    private function nullableDetectionValue(mixed $value): ?string
    {
        if (! is_string($value) || $value === '' || $value === DeviceDetector::UNKNOWN) {
            return null;
        }

        return $value;
    }
}
