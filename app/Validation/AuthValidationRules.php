<?php

namespace App\Validation;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

final class AuthValidationRules
{
    /**
     * @return array<string, array<int, mixed|string>>
     */
    public function registration(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->password(),
        ];
    }

    /**
     * @return array<string, array<int, mixed|string>>
     */
    public function profile(User $user): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user),
            ],
        ];
    }

    /**
     * @return array<string, array<int, mixed|string>>
     */
    public function passwordUpdate(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->password(),
        ];
    }

    /**
     * @return array<string, array<int, mixed|string>>
     */
    public function passwordReset(): array
    {
        return [
            'password' => $this->password(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function passwordUpdateMessages(): array
    {
        return [
            'current_password.current_password' => __('The provided password does not match your current password.'),
        ];
    }

    /**
     * @return array<int, mixed|string>
     */
    private function password(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
    }
}
