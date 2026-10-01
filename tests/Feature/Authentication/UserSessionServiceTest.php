<?php

use App\Models\LoginHistory;
use App\Models\User;
use App\Services\Auth\UserSessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('active sessions return ten other sessions when the current session is absent', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    foreach (range(1, 10) as $sessionNumber) {
        DB::table('sessions')->insert([
            'id' => 'active-session-'.$sessionNumber,
            'user_id' => $user->id,
            'ip_address' => '198.51.100.'.$sessionNumber,
            'user_agent' => 'Mozilla/5.0 Chrome/130.0.0.0',
            'payload' => '{}',
            'last_activity' => now()->subMinutes($sessionNumber)->timestamp,
        ]);
    }

    DB::table('sessions')->insert([
        'id' => 'another-users-session',
        'user_id' => $otherUser->id,
        'ip_address' => '203.0.113.7',
        'user_agent' => 'Mozilla/5.0 Chrome/130.0.0.0',
        'payload' => '{}',
        'last_activity' => now()->timestamp,
    ]);

    $sessions = app(UserSessionService::class)->activeFor($user, 'missing-current-session');

    expect($sessions)->toHaveCount(10)
        ->and($sessions->pluck('is_current')->filter()->all())->toBe([])
        ->and($sessions->pluck('ip_address'))->not->toContain('203.0.113.7');
});

test('active sessions use the stored login detection snapshot when available', function (): void {
    $user = User::factory()->create();

    DB::table('sessions')->insert([
        'id' => 'tablet-login-session',
        'user_id' => $user->id,
        'ip_address' => '203.0.113.7',
        'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/148.0.0.0 Safari/537.36',
        'payload' => '{}',
        'last_activity' => now()->timestamp,
    ]);

    LoginHistory::create([
        'user_id' => $user->id,
        'session_id' => 'tablet-login-session',
        'logged_in_at' => now()->subMinute(),
        'device_type' => 'tablet',
        'device_model' => 'M6 Pro',
        'operating_system' => 'GNU/Linux',
        'browser' => 'Quetta',
        'browser_version' => '148.0',
    ]);

    $session = app(UserSessionService::class)
        ->activeFor($user, 'missing-current-session')
        ->sole();

    expect($session['device_type'])->toBe('tablet')
        ->and($session['device_model'])->toBe('M6 Pro')
        ->and($session['operating_system'])->toBe('GNU/Linux')
        ->and($session['browser'])->toBe('Quetta')
        ->and($session['browser_version'])->toBe('148.0');
});
