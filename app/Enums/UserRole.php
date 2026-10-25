<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasColor, HasLabel
{
    case User = 'user';
    case Operator = 'operator';
    case Admin = 'admin';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): array
    {
        return match ($this) {
            self::User => Color::Green,
            self::Operator => Color::Blue,
            self::Admin => Color::Amber,
        };
    }

    public function canAccessAdminPanel(): bool
    {
        return $this === self::Admin;
    }

    public function canAccessOperatorPanel(): bool
    {
        return in_array($this, [self::Operator, self::Admin], true);
    }
}
