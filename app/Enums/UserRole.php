<?php

namespace App\Enums;

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

    public function getColor(): string
    {
        return match ($this) {
            self::User => 'gray',
            self::Operator => 'info',
            self::Admin => 'danger',
        };
    }

    public function canAccessAdminPanel(): bool
    {
        return $this === self::Admin;
    }

    public function canManage(self $targetRole): bool
    {
        return $this === self::Admin
            && in_array($targetRole, [self::User, self::Operator], true);
    }
}
