<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

it('uses the configured default password for new accounts', function () {
    config(['app.seeder_default_password' => 'configured-seeder-password']);

    $this->seed();

    $user = User::query()->where('email', 'user@example.com')->firstOrFail();

    expect(Hash::check('configured-seeder-password', $user->password))->toBeTrue();
});

it('preserves existing account passwords when rerun', function () {
    $this->seed();

    $user = User::query()->where('email', 'user@example.com')->firstOrFail();
    $user->password = 'changed-in-profile';
    $user->save();

    $this->seed();

    expect(Hash::check('changed-in-profile', $user->refresh()->password))->toBeTrue();
});
