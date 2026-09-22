<?php

namespace App\Actions\Fortify;

use App\Actions\Auth\RegisterUser;
use App\Models\User;
use App\Validation\AuthValidationRules;
use Illuminate\Contracts\Validation\Factory;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function __construct(
        private readonly Factory $validator,
        private readonly AuthValidationRules $rules,
        private readonly RegisterUser $registerUser,
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

        return $this->registerUser->handle([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);
    }
}
