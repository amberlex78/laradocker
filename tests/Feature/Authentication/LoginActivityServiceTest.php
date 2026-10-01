<?php

use App\Models\User;
use App\Services\Auth\LoginActivityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

uses(RefreshDatabase::class);

test('recording login activity stores latest device details and login history', function (): void {
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

    $user = $user->fresh();
    $history = $user->loginHistories()->sole();

    expect($user->last_login_device_type)->toBe('smartphone')
        ->and($user->last_login_os)->toBe('iOS')
        ->and($user->last_login_browser)->toBe('Mobile Safari')
        ->and($user->last_login_browser_version)->toBe('17.0')
        ->and($user->last_login_ip_address)->toBe('203.0.113.7')
        ->and($user->last_login_at)->not->toBeNull()
        ->and($history->device_type)->toBe('smartphone')
        ->and($history->ip_address)->toBe('203.0.113.7')
        ->and($history->logged_in_at)->not->toBeNull()
        ->and($history->session_id)->toBeNull();
});
