<?php

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
