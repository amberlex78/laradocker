<?php

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('a user has a relationship to their login history records', function (): void {
    $user = User::factory()->create();

    $history = LoginHistory::create([
        'user_id' => $user->id,
        'logged_in_at' => '2026-09-30 12:34:56',
        'device_type' => 'desktop',
    ]);

    expect($user->loginHistories()->sole()->is($history))->toBeTrue();
});

test('login history is the only persistence source for login device snapshots', function (): void {
    expect(Schema::hasColumns('users', [
        'last_login_device_type',
        'last_login_device_model',
        'last_login_os',
        'last_login_browser',
        'last_login_browser_version',
        'last_login_ip_address',
        'last_login_at',
    ]))->toBeFalse();
});

test('the snapshot removal migration preserves legacy data and restores the latest snapshot', function (): void {
    $migration = require database_path('migrations/2026_10_01_165336_remove_last_login_columns_from_users_table.php');
    $legacyOnlyUser = User::factory()->create();
    $existingHistoryUser = User::factory()->create();

    LoginHistory::create([
        'user_id' => $existingHistoryUser->id,
        'logged_in_at' => '2026-09-30 12:00:00',
        'ip_address' => '203.0.113.10',
        'device_type' => 'desktop',
        'browser' => 'Firefox',
    ]);

    try {
        $migration->down();

        DB::table('users')
            ->where('id', $legacyOnlyUser->id)
            ->update([
                'last_login_device_type' => 'tablet',
                'last_login_device_model' => 'Legacy Tablet',
                'last_login_os' => 'Android',
                'last_login_browser' => 'Chrome',
                'last_login_browser_version' => '130.0',
                'last_login_ip_address' => '203.0.113.11',
                'last_login_at' => '2026-09-30 13:00:00',
            ]);

        $migration->up();

        $legacyHistory = $legacyOnlyUser->loginHistories()->sole();

        expect($legacyHistory->device_type)->toBe('tablet')
            ->and($legacyHistory->device_model)->toBe('Legacy Tablet')
            ->and($legacyHistory->ip_address)->toBe('203.0.113.11')
            ->and($existingHistoryUser->loginHistories()->count())->toBe(1);

        LoginHistory::create([
            'user_id' => $legacyOnlyUser->id,
            'logged_in_at' => '2026-10-01 14:00:00',
            'ip_address' => '203.0.113.12',
            'device_type' => 'smartphone',
            'device_model' => 'New Phone',
            'operating_system' => 'Android',
            'browser' => 'Chrome Mobile',
            'browser_version' => '131.0',
        ]);

        $migration->down();

        $restoredSnapshot = DB::table('users')->find($legacyOnlyUser->id);

        expect($restoredSnapshot->last_login_device_type)->toBe('smartphone')
            ->and($restoredSnapshot->last_login_device_model)->toBe('New Phone')
            ->and($restoredSnapshot->last_login_ip_address)->toBe('203.0.113.12')
            ->and($restoredSnapshot->last_login_at)->toBe('2026-10-01 14:00:00');
    } finally {
        if (Schema::hasColumn('users', 'last_login_at')) {
            $migration->up();
        }
    }
});
