<?php

namespace App\Actions\Fortify;

use App\Enums\LogoutReason;
use App\Models\User;
use App\Services\Auth\UserSessionService;
use App\Validation\AuthValidationRules;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    public function __construct(
        private readonly Factory $validator,
        private readonly AuthValidationRules $rules,
        private readonly UserSessionService $userSessions,
        private readonly Request $request,
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

        DB::transaction(function () use ($user, $validated): void {
            $user->forceFill([
                'password' => $validated['password'],
            ])->save();

            if ($this->request->hasSession()) {
                $this->userSessions->terminateOthers(
                    $user,
                    $this->request->session()->getId(),
                    LogoutReason::PasswordChanged,
                );

                return;
            }

            $this->userSessions->terminateAll($user, LogoutReason::PasswordChanged);
        });
    }
}
