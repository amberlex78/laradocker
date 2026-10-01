<?php

namespace App\Enums;

enum LogoutReason: string
{
    case Logout = 'logout';
    case PasswordChanged = 'password_changed';
    case PasswordReset = 'password_reset';
    case Terminated = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::Logout => 'Logged out',
            self::PasswordChanged => 'Password changed',
            self::PasswordReset => 'Password reset',
            self::Terminated => 'Terminated',
        };
    }
}
