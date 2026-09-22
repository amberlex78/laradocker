<?php

namespace App\Actions\Fortify;

use App\Actions\Auth\SetUserPassword;
use App\Models\User;
use App\Validation\AuthValidationRules;
use Illuminate\Contracts\Validation\Factory;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    public function __construct(
        private readonly Factory $validator,
        private readonly AuthValidationRules $rules,
        private readonly SetUserPassword $setUserPassword,
    ) {}

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        $validated = $this->validator
            ->make(
                $input,
                $this->rules->passwordUpdate(),
                $this->rules->passwordUpdateMessages(),
            )
            ->validateWithBag('updatePassword');

        $this->setUserPassword->handle($user, $validated['password']);
    }
}
