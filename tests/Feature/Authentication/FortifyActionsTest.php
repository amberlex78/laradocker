<?php

use App\Enums\LogoutReason;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

uses(RefreshDatabase::class);

test('profile updates validate and update the profile through the Fortify contract', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'old@example.com',
        'email_verified_at' => now(),
    ]);

    app(UpdatesUserProfileInformation::class)->update($user, [
        'name' => 'Updated User',
        'email' => 'new@example.com',
    ]);

    expect($user->fresh()->name)->toBe('Updated User')
        ->and($user->fresh()->email)->toBe('new@example.com')
        ->and($user->fresh()->email_verified_at)->toBeNull();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('authenticated password updates validate the current password', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    app(UpdatesUserPasswords::class)->update($user, [
        'current_password' => 'password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

test('password reset stores a validated new password', function () {
    $user = User::factory()->create();

    app(ResetsUserPasswords::class)->reset($user, [
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

test('password reset revokes every session and records the reason', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    DB::table('sessions')->insert([
        [
            'id' => 'password-reset-session-one',
            'user_id' => $user->id,
            'ip_address' => '203.0.113.7',
            'user_agent' => 'First browser',
            'payload' => '{}',
            'last_activity' => now()->timestamp,
        ],
        [
            'id' => 'password-reset-session-two',
            'user_id' => $user->id,
            'ip_address' => '203.0.113.8',
            'user_agent' => 'Second browser',
            'payload' => '{}',
            'last_activity' => now()->timestamp,
        ],
        [
            'id' => 'password-reset-unrelated-session',
            'user_id' => $otherUser->id,
            'ip_address' => '203.0.113.9',
            'user_agent' => 'Unrelated browser',
            'payload' => '{}',
            'last_activity' => now()->timestamp,
        ],
    ]);

    $histories = collect([
        LoginHistory::create([
            'user_id' => $user->id,
            'session_id' => 'password-reset-session-one',
            'logged_in_at' => now()->subHours(2),
        ]),
        LoginHistory::create([
            'user_id' => $user->id,
            'session_id' => 'password-reset-session-two',
            'logged_in_at' => now()->subHour(),
        ]),
    ]);

    app(ResetsUserPasswords::class)->reset($user, [
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
    $this->assertDatabaseHas('sessions', [
        'id' => 'password-reset-unrelated-session',
        'user_id' => $otherUser->id,
    ]);

    $histories->each(function (LoginHistory $history): void {
        expect($history->fresh()->logged_out_at)->not->toBeNull()
            ->and($history->fresh()->logout_reason)->toBe(LogoutReason::PasswordReset);
    });
});
