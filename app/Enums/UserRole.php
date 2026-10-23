<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;

enum UserRole: string implements HasColor
{
    case User = 'user';
    case Operator = 'operator';
    case Admin = 'admin';
    case Developer = 'developer';

    /**
     * Get the available role options for forms.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $role) {
            $options[$role->value] = ucfirst($role->value);
        }

        return $options;
    }

    public function getColor(): string
    {
        return match ($this) {
            self::User => 'gray',
            self::Operator => 'info',
            self::Admin => 'danger',
            self::Developer => 'success',
        };
    }
}
