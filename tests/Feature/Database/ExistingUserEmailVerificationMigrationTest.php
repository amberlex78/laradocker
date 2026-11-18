<?php

use App\Models\User;

it('marks existing users as verified when email verification is introduced', function (): void {
    $user = User::factory()->unverified()->create();
    $migrationFiles = glob(database_path('migrations/*_verify_existing_user_email_addresses.php'));

    expect($migrationFiles)->toHaveCount(1);

    $migration = require $migrationFiles[0];
    $migration->up();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});
