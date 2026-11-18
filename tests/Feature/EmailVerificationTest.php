<?php

use App\Enums\UserRole;
use App\Models\User;
use Filament\Auth\Notifications\VerifyEmail;
use Filament\Auth\Pages\Register;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('sends a verification email when a user registers', function (): void {
    Notification::fake();
    Filament::setCurrentPanel('site');

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'password' => 'password123',
            'passwordConfirmation' => 'password123',
        ])
        ->call('register')
        ->assertRedirect('/');

    $user = User::query()->where('email', 'ada@example.com')->sole();

    expect($user->hasVerifiedEmail())->toBeFalse();
    Notification::assertSentTo($user, VerifyEmail::class);
});

it('redirects an unverified user to the verification prompt', function (): void {
    $user = User::factory()->unverified()->create();

    actingAs($user)
        ->get('/profile')
        ->assertRedirect('/email-verification/prompt');
});

it('requires email verification in privileged panels', function (UserRole $role, string $path): void {
    $user = User::factory()->unverified()->create(['role' => $role]);

    actingAs($user)
        ->get($path)
        ->assertRedirect("{$path}/email-verification/prompt");
})->with([
    'operator panel' => [UserRole::Operator, '/operator'],
    'admin panel' => [UserRole::Admin, '/admin'],
]);

it('allows a user to verify their email through a signed link', function (): void {
    $user = User::factory()->unverified()->create();
    Filament::setCurrentPanel('site');
    actingAs($user);

    $response = get(Filament::getVerifyEmailUrl($user));

    $response->assertRedirect('/');
    expect($user->refresh()->hasVerifiedEmail())->toBeTrue();
});
