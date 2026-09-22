<?php

namespace App\Actions\Fortify;

use App\Actions\Auth\SetUserPassword;
use App\Models\User;
use App\Validation\AuthValidationRules;
use Illuminate\Contracts\Validation\Factory;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    public function __construct(
        private readonly Factory $validator,
        private readonly AuthValidationRules $rules,
        private readonly SetUserPassword $setUserPassword,
    ) {}

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, mixed>  $input
     */
    public function reset(User $user, array $input): void
    {
        $validated = $this->validator
            ->make($input, $this->rules->passwordReset())
            ->validate();

        $this->setUserPassword->handle($user, $validated['password']);
    }
}
