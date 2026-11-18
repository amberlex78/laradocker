<?php

use App\Enums\UserRole;
use App\Models\User;
use Filament\Auth\Pages\Register;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

use function Pest\Laravel\get;

it('shows registration and password reset links on the login page', function (): void {
    $response = get('/login');

    $response
        ->assertOk()
        ->assertSee(route('filament.site.auth.register'), false)
        ->assertSee(route('filament.site.auth.password-reset.request'), false);
});

it('renders the registration page', function (): void {
    get('/register')
        ->assertOk()
        ->assertSee('Sign up')
        ->assertSee('Name')
        ->assertSee('Email address')
        ->assertSee('Password')
        ->assertSee('Confirm password');
});

it('renders the password reset request page', function (): void {
    get('/password-reset/request')
        ->assertOk()
        ->assertSee('Forgot password')
        ->assertSee('Email');
});

it('registers a user with the default role and signs them in', function (): void {
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

    expect($user->name)->toBe('Ada Lovelace')
        ->and($user->role)->toBe(UserRole::User)
        ->and(Hash::check('password123', $user->password))->toBeTrue()
        ->and(auth()->id())->toBe($user->getKey());
});

it('does not register a user when the password confirmation is invalid', function (): void {
    Filament::setCurrentPanel('site');

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'password' => 'password123',
            'passwordConfirmation' => 'different-password',
        ])
        ->call('register')
        ->assertHasFormErrors(['password' => 'same'])
        ->assertNoRedirect();

    expect(User::query()->where('email', 'ada@example.com')->exists())->toBeFalse();
});

it('does not register a user with an existing email address', function (): void {
    $existingUser = User::factory()->create([
        'email' => 'ada@example.com',
    ]);

    Filament::setCurrentPanel('site');

    Livewire::test(Register::class)
        ->fillForm([
            'name' => 'Ada Lovelace',
            'email' => $existingUser->email,
            'password' => 'password123',
            'passwordConfirmation' => 'password123',
        ])
        ->call('register')
        ->assertHasFormErrors(['email' => 'unique'])
        ->assertNoRedirect();

    expect(User::query()->where('email', $existingUser->email)->count())->toBe(1);
});
