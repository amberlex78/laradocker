<?php

namespace App\Actions\Fortify;

use App\Enums\UserRole;
use App\Models\User;
use App\Validation\AuthValidationRules;
use Illuminate\Contracts\Validation\Factory;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function __construct(
        private readonly Factory $validator,
        private readonly AuthValidationRules $rules,
    ) {}

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, mixed>  $input
     */
    public function create(array $input): User
    {
        $validated = $this->validator
            ->make($input, $this->rules->registration())
            ->validate();

        return User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => UserRole::User,
        ]);
    }
}
