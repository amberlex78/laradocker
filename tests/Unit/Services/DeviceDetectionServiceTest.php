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

it('detects Quetta from browser client hints', function (): void {
    $details = (new DeviceDetectionService)->detect(
        'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 '
            .'(KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36',
        [
            'HTTP_SEC_CH_UA' => '"Chromium";v="148", "Quetta";v="148", "Not/A)Brand";v="99"',
            'HTTP_SEC_CH_UA_MOBILE' => '?1',
            'HTTP_SEC_CH_UA_PLATFORM' => '"Android"',
        ],
    );

    expect($details)->toBe([
        'client' => 'Quetta 148.0',
        'operatingSystem' => 'Android',
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
