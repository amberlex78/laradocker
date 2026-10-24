<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => 'password',
                    'role' => $account['role'],
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
