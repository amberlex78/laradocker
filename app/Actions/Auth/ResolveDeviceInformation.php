<?php

namespace App\Actions\Auth;

use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use Illuminate\Http\Request;

final class ResolveDeviceInformation
{
    /**
     * Resolve device information from an authenticated request.
     *
     * @return array{device_type: string, device_model: ?string, operating_system: ?string, browser: ?string, browser_version: ?string}
     */
    public function fromRequest(Request $request): array
    {
        return $this->resolve(
            $request->userAgent(),
            $this->clientHintHeaders($request),
            $request->string('device_type_hint')->toString(),
            $request->string('device_model_hint')->toString(),
        );
    }

    /**
     * Resolve device information from the User-Agent stored in a database session.
     *
     * @return array{device_type: string, device_model: ?string, operating_system: ?string, browser: ?string, browser_version: ?string}
     */
    public function fromUserAgent(?string $userAgent): array
    {
        return $this->resolve($userAgent, [], null, null);
    }

    /**
     * @param  array<string, mixed>  $clientHintHeaders
     * @return array{device_type: string, device_model: ?string, operating_system: ?string, browser: ?string, browser_version: ?string}
     */
    private function resolve(?string $userAgent, array $clientHintHeaders, ?string $deviceTypeHint, ?string $deviceModelHint): array
    {
        $detector = new DeviceDetector(
            $userAgent ?? '',
            ClientHints::factory($clientHintHeaders),
        );
        $detector->parse();

        $deviceType = $this->resolveDeviceType($detector->getDeviceName(), $deviceTypeHint);

        return [
            'device_type' => $deviceType,
            'device_model' => $this->resolveDeviceModel($detector, $deviceModelHint, $deviceType),
            'operating_system' => $this->nullableDetectionValue($detector->getOs('name')),
            'browser' => $this->nullableDetectionValue($detector->getClient('name')),
            'browser_version' => $this->nullableDetectionValue($detector->getClient('version')),
        ];
    }

    private function resolveDeviceType(string $detectedDeviceType, ?string $deviceTypeHint): string
    {
        if ($detectedDeviceType === 'desktop' && $deviceTypeHint === 'tablet') {
            return 'tablet';
        }

        return $detectedDeviceType ?: 'unknown';
    }

    private function resolveDeviceModel(DeviceDetector $detector, ?string $deviceModelHint, string $deviceType): ?string
    {
        $model = $detector->getModel();

        if ($model === '' && $deviceType === 'tablet') {
            $model = $deviceModelHint ?? '';
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
