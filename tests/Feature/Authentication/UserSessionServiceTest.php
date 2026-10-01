<?php

use App\Models\LoginHistory;
use App\Models\User;
use App\Services\Auth\UserSessionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

test('active session status is limited to the requested history session ids', function (): void {
    $user = User::factory()->create();

    foreach (['included-session', 'excluded-session'] as $sessionId) {
        DB::table('sessions')->insert([
            'id' => $sessionId,
            'user_id' => $user->id,
            'ip_address' => null,
            'user_agent' => null,
            'payload' => '{}',
            'last_activity' => now()->timestamp,
        ]);
    }

    $activeSessionIds = app(UserSessionService::class)
        ->activeSessionIdsFor($user, collect(['included-session']));

    expect($activeSessionIds->all())->toBe(['included-session']);
});

test('session queries honor the configured database session connection and table', function (): void {
    config()->set('database.connections.session_testing', [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
        'foreign_key_constraints' => true,
    ]);
    config()->set([
        'session.connection' => 'session_testing',
        'session.table' => 'custom_sessions',
    ]);

    Schema::connection('session_testing')->create('custom_sessions', function (Blueprint $table): void {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });

    try {
        $user = User::factory()->create();

        DB::connection('session_testing')->table('custom_sessions')->insert([
            'id' => 'configured-table-session',
            'user_id' => $user->id,
            'ip_address' => '203.0.113.20',
            'user_agent' => null,
            'payload' => '{}',
            'last_activity' => now()->timestamp,
        ]);
        DB::table('sessions')->insert([
            'id' => 'default-table-session',
            'user_id' => $user->id,
            'ip_address' => '203.0.113.21',
            'user_agent' => null,
            'payload' => '{}',
            'last_activity' => now()->timestamp,
        ]);

        $sessions = app(UserSessionService::class)->activeFor($user, 'missing-current-session');

        expect($sessions->pluck('session_id')->all())->toBe(['configured-table-session']);
    } finally {
        Schema::connection('session_testing')->dropIfExists('custom_sessions');
        DB::purge('session_testing');
    }
});
