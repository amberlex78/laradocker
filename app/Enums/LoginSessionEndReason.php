<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum LoginSessionEndReason: string implements HasLabel
{
    case LoggedOut = 'logged_out';
    case ManuallyTerminated = 'manually_terminated';
    case PasswordReset = 'password_reset';
    case PasswordChanged = 'password_changed';

    public function isTermination(): bool
    {
        return $this !== self::LoggedOut;
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::LoggedOut => 'Signed out',
            self::ManuallyTerminated => 'Manually terminated',
            self::PasswordReset => 'Password reset',
            self::PasswordChanged => 'Password changed',
        };
    }

    /**
     * @return list<string>
     */
    public static function terminationValues(): array
    {
        return array_values(array_map(
            static fn (self $reason): string => $reason->value,
            array_filter(self::cases(), static fn (self $reason): bool => $reason->isTermination()),
        ));
    }
}
