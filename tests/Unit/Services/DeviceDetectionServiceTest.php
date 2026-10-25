<?php

use App\Services\DeviceDetectionService;

it('detects browser operating system and device from a user agent', function (): void {
    $details = (new DeviceDetectionService)->detect(
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 '
            .'(KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',
    );

    expect($details)->toBe([
        'client' => 'Chrome 149.0',
        'operatingSystem' => 'GNU/Linux',
        'device' => 'Desktop',
    ]);
});

it('detects an iphone safari user agent', function (): void {
    $details = (new DeviceDetectionService)->detect(
        'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) '
            .'AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 '
            .'Mobile/15E148 Safari/604.1',
    );

    expect($details)->toBe([
        'client' => 'Safari 17.0',
        'operatingSystem' => 'iOS',
        'device' => 'Smartphone',
    ]);
});

it('normalizes an unknown user agent', function (): void {
    expect((new DeviceDetectionService)->detect(null))->toBe([
        'client' => 'Unknown',
        'operatingSystem' => 'Unknown',
        'device' => 'Unknown',
    ]);
});
