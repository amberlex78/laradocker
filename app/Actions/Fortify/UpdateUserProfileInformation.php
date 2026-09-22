<?php

namespace App\Actions\Fortify;

use App\Actions\Auth\UpdateUserProfile;
use App\Models\User;
use App\Validation\AuthValidationRules;
use Illuminate\Contracts\Validation\Factory;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    public function __construct(
        private readonly Factory $validator,
        private readonly AuthValidationRules $rules,
        private readonly UpdateUserProfile $updateUserProfile,
    ) {}

    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        $validated = $this->validator
            ->make($input, $this->rules->profile($user))
            ->validateWithBag('updateProfileInformation');

        $this->updateUserProfile->handle($user, [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
    }
}
