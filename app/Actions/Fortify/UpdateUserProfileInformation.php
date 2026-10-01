<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Validation\AuthValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Validation\Factory;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    public function __construct(
        private readonly Factory $validator,
        private readonly AuthValidationRules $rules,
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

        $attributes = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($attributes['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $user->forceFill([
                ...$attributes,
                'email_verified_at' => null,
            ])->save();

            $user->sendEmailVerificationNotification();

            return;
        }

        $user->forceFill($attributes)->save();
    }
}
