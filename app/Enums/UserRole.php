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
    case Developer = 'developer';

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
            self::Developer => Color::Purple,
        };
    }

    public function canAccessAdminPanel(): bool
    {
        return in_array($this, [self::Admin, self::Developer], true);
    }

    public function canAccessOperatorPanel(): bool
    {
        return in_array($this, [self::Operator, self::Admin, self::Developer], true);
    }
}
