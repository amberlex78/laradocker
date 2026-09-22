<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;

final class UpdateUserProfile
{
    /**
     * Update a user's profile from validated attributes.
     *
     * @param  array{name: string, email: string}  $attributes
     */
    public function handle(User $user, array $attributes): void
    {
        if ($attributes['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $attributes);

            return;
        }

        $user->forceFill($attributes)->save();
    }

    /**
     * Update a verified user's profile and request a new email verification.
     *
     * @param  array{name: string, email: string}  $attributes
     */
    private function updateVerifiedUser(User $user, array $attributes): void
    {
        $user->forceFill([
            ...$attributes,
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
