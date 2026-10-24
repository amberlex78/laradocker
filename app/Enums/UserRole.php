<?php

namespace App\Enums;

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

    public function getColor(): string
    {
        return match ($this) {
            self::User => 'gray',
            self::Operator => 'info',
            self::Admin => 'danger',
            self::Developer => 'success',
        };
    }

    public function canAccessAdminPanel(): bool
    {
        return in_array($this, [self::Admin, self::Developer], true);
    }

    /**
     * Get role options visible to an actor.
     *
     * @return array<string, string>
     */
    public static function options(?self $actorRole = null): array
    {
        $roles = match ($actorRole) {
            null, self::Developer => self::cases(),
            self::Admin => [self::User, self::Operator, self::Admin],
            default => [],
        };

        return collect($roles)
            ->mapWithKeys(fn (self $role): array => [$role->value => $role->getLabel()])
            ->all();
    }

    public function canManage(self $targetRole): bool
    {
        return $this === self::Developer
            || ($this === self::Admin && in_array($targetRole, [self::User, self::Operator], true));
    }
}
