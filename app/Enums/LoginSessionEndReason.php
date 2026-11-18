<?php

namespace App\Enums;

enum LoginSessionEndReason: string
{
    case LoggedOut = 'logged_out';
    case ManuallyTerminated = 'manually_terminated';
    case PasswordReset = 'password_reset';
    case PasswordChanged = 'password_changed';

    public function isTermination(): bool
    {
        return $this !== self::LoggedOut;
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
