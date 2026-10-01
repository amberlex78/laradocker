<?php

namespace App\Actions\Fortify;

use App\Enums\LogoutReason;
use App\Models\User;
use App\Services\Auth\UserSessionService;
use App\Validation\AuthValidationRules;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    public function __construct(
        private readonly Factory $validator,
        private readonly AuthValidationRules $rules,
        private readonly UserSessionService $userSessions,
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

        DB::transaction(function () use ($user, $validated): void {
            $user->forceFill([
                'password' => $validated['password'],
            ])->save();
            $this->userSessions->terminateAll($user, LogoutReason::PasswordReset);
        });
    }
}
