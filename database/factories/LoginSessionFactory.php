<?php

namespace Database\Factories;

use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoginSession>
 */
class LoginSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'guard' => 'web',
            'session_id' => fake()->sha1(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'logged_in_at' => now(),
            'last_seen_at' => now(),
            'logged_out_at' => null,
            'end_reason' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (): array => [
            'last_seen_at' => now()->subMinutes((int) config('session.lifetime') + 1),
        ]);
    }

    public function ended(): static
    {
        return $this->state(fn (): array => [
            'logged_out_at' => now(),
            'end_reason' => 'logged_out',
        ]);
    }
}
