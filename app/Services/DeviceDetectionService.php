<?php

namespace App\Services;

use DeviceDetector\DeviceDetector;
use Illuminate\Support\Str;

final class DeviceDetectionService
{
    /**
     * @return array{client: string, operatingSystem: string, device: string}
     */
    public function detect(?string $userAgent): array
    {
        $detector = new DeviceDetector($userAgent ?? '');
        $detector->parse();

        $client = $this->normalize($detector->getClient('family'), 'Unknown');
        $version = $this->normalize($detector->getClient('version'), '');
        $operatingSystem = $this->normalize($detector->getOs('name'), 'Unknown');
        $device = $this->normalize($detector->getDeviceName(), 'Unknown');

        return [
            'client' => trim($client.' '.$version),
            'operatingSystem' => $operatingSystem,
            'device' => Str::title($device),
        ];
    }

    private function normalize(mixed $value, string $fallback): string
    {
        return is_string($value)
            && $value !== ''
            && $value !== DeviceDetector::UNKNOWN
            ? $value
            : $fallback;
    }
}
