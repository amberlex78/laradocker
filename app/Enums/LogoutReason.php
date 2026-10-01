<?php

namespace App\Enums;

enum LogoutReason: string
{
    case Logout = 'logout';
    case Terminated = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::Logout => 'Logged out',
            self::Terminated => 'Terminated',
        };
    }
}
