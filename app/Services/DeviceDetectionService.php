<?php

namespace App\Services;

use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use Illuminate\Support\Str;

final class DeviceDetectionService
{
    /**
     * @param  array<string, mixed>  $headers
     * @return array{client: string, operatingSystem: string, device: string}
     */
    public function detect(?string $userAgent, array $headers = []): array
    {
        $detector = new DeviceDetector(
            $userAgent ?? '',
            ClientHints::factory($headers),
        );
        $detector->parse();

        $clientName = $detector->getClient('name');
        $clientName = $clientName === 'Mobile Safari' ? 'Safari' : $clientName;
        $client = $this->normalize($clientName, 'Unknown');
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
