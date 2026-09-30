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

        $user->forceFill([
            'last_login_device_type' => $deviceType,
            'last_login_device_model' => $this->resolveDeviceModel($detector, $request, $deviceType),
            'last_login_os' => $this->nullableDetectionValue($detector->getOs('name')),
            'last_login_browser' => $this->nullableDetectionValue($detector->getClient('name')),
            'last_login_browser_version' => $this->nullableDetectionValue($detector->getClient('version')),
            'last_login_ip_address' => $request->ip(),
            'last_login_at' => now(),
        ])->save();
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
