<?php

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
