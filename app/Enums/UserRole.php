<?php

namespace App\Enums;

enum UserRole: string
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
}
