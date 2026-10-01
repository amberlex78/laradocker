<?php

use App\Services\Auth\DeviceDetectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('device detection resolves an iPhone user agent', function (): void {
    $details = app(DeviceDetectionService::class)->fromUserAgent(
        'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
    );

    expect($details['device_type'])->toBe('smartphone')
        ->and($details['operating_system'])->toBe('iOS')
        ->and($details['browser'])->toBe('Mobile Safari')
        ->and($details['browser_version'])->toBe('17.0');
});

test('device detection normalizes an empty user agent to unknown values', function (): void {
    $details = app(DeviceDetectionService::class)->fromUserAgent('');

    expect($details['device_type'])->toBe('unknown')
        ->and($details['device_model'])->toBeNull()
        ->and($details['operating_system'])->toBeNull()
        ->and($details['browser'])->toBeNull()
        ->and($details['browser_version'])->toBeNull();
});
