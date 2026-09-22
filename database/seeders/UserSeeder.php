<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $passwordHash = Hash::make(config('seeders.default_password'));

        /**
         * @var array<int, array{name: string, email: string, role: UserRole}> $users
         */
        $users = [
            [
                'name' => 'Developer',
                'email' => 'developer@example.com',
                'role' => UserRole::Developer,
            ],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'role' => UserRole::Admin,
            ],
            [
                'name' => 'Operator',
                'email' => 'operator@example.com',
                'role' => UserRole::Operator,
            ],
            [
                'name' => 'User',
                'email' => 'user@example.com',
                'role' => UserRole::User,
            ],
        ];

        foreach ($users as $attributes) {
            $user = User::firstOrNew([
                'email' => $attributes['email'],
            ]);

            $user->fill([
                'name' => $attributes['name'],
                'role' => $attributes['role'],
                'email_verified_at' => now(),
            ]);

            if (! $user->exists) {
                $user->password = $passwordHash;
            }

            $user->save();
        }
    }
}
