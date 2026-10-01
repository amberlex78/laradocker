<?php

use App\Models\User;
use App\Services\Auth\LoginActivityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

uses(RefreshDatabase::class);

test('recording login activity stores a device snapshot in login history', function (): void {
    $user = User::factory()->create();
    $request = Request::create(
        '/login',
        'POST',
        [],
        [],
        [],
        ['REMOTE_ADDR' => '203.0.113.7'],
    );
    $request->headers->set(
        'User-Agent',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
    );

    app(LoginActivityService::class)->record($user, $request);

    $history = $user->loginHistories()->sole();

    expect($history->device_type)->toBe('smartphone')
        ->and($history->operating_system)->toBe('iOS')
        ->and($history->browser)->toBe('Mobile Safari')
        ->and($history->browser_version)->toBe('17.0')
        ->and($history->ip_address)->toBe('203.0.113.7')
        ->and($history->logged_in_at)->not->toBeNull()
        ->and($history->session_id)->toBeNull();
});
