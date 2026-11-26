<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Test User',
                'email' => 'user@example.com',
                'role' => UserRole::User,
            ],
            [
                'name' => 'Test Operator',
                'email' => 'operator@example.com',
                'role' => UserRole::Operator,
            ],
            [
                'name' => 'Test Admin',
                'email' => 'admin@example.com',
                'role' => UserRole::Admin,
            ],
            [
                'name' => 'Test Developer',
                'email' => 'developer@example.com',
                'role' => UserRole::Developer,
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::firstOrNew(['email' => $account['email']]);

            $user->fill([
                'name' => $account['name'],
                'role' => $account['role'],
            ]);

            if (! $user->exists) {
                $user->password = config('app.seeder_default_password');
            }

            $user->email_verified_at = Carbon::now();
            $user->save();
        }
    }
}
