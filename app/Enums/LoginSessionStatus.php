<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LoginSessionStatus: string implements HasColor, HasLabel
{
    case Current = 'current';
    case Active = 'active';
    case Expired = 'expired';
    case SignedOut = 'signed_out';
    case Terminated = 'terminated';

    public function getLabel(): string
    {
        return match ($this) {
            self::Current => 'Current session',
            self::Active => 'Active',
            self::Expired => 'Expired',
            self::SignedOut => 'Signed out',
            self::Terminated => 'Terminated',
        };
    }

    public function getColor(): array
    {
        return match ($this) {
            self::Current, self::Active => Color::Green,
            self::Terminated => Color::Red,
            self::Expired => Color::Amber,
            self::SignedOut => Color::Gray,
        };
    }
}
