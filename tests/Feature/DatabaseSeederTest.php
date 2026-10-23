<?php

use App\Enums\UserRole;
use App\Models\User;

it('seeds one test account for each user role', function () {
    $this->seed();

    $rolesByEmail = User::query()
        ->get()
        ->mapWithKeys(fn (User $user): array => [$user->email => $user->role->value])
        ->all();

    expect(User::count())->toBe(4)
        ->and($rolesByEmail)->toBe([
            'user@example.com' => UserRole::User->value,
            'operator@example.com' => UserRole::Operator->value,
            'admin@example.com' => UserRole::Admin->value,
            'developer@example.com' => UserRole::Developer->value,
        ]);
});
