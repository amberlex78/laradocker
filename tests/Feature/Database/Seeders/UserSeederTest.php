<?php

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('database seeder creates one account for each configured role', function () {
    $this->seed();

    $expectedUsers = [
        'developer@example.com' => UserRole::Developer,
        'admin@example.com' => UserRole::Admin,
        'operator@example.com' => UserRole::Operator,
        'user@example.com' => UserRole::User,
    ];

    foreach ($expectedUsers as $email => $role) {
        $user = User::where('email', $email)->first();

        expect($user)->not->toBeNull()
            ->and($user?->role)->toBe($role);
    }

    expect(User::count())->toBe(4);
});

test('user seeder can be run again without duplicating users or changing passwords', function () {
    $this->seed(UserSeeder::class);

    $developer = User::where('email', 'developer@example.com')->firstOrFail();
    $passwordHash = $developer->password;

    $this->seed(UserSeeder::class);

    expect(User::count())->toBe(4)
        ->and(User::where('email', 'developer@example.com')->value('password'))->toBe($passwordHash);
});
