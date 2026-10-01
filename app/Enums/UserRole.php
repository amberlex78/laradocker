<?php

namespace App\Enums;

enum UserRole: string
{
    case Developer = 'developer';
    case Admin = 'admin';
    case Operator = 'operator';
    case User = 'user';

    public function landingRoute(): string
    {
        return $this->workspace()['route'] ?? 'account';
    }

    /**
     * @return array{route: string, name: string, label: string, initial: string}|null
     */
    public function workspace(?string $area = null): ?array
    {
        if ($area === 'admin' && $this === self::Developer) {
            return self::Admin->workspace();
        }

        if ($area === 'developer') {
            return self::Developer->workspace();
        }

        return match ($this) {
            self::Developer => [
                'route' => 'developer.dashboard',
                'name' => 'Developer',
                'label' => 'Developer workspace',
                'initial' => 'D',
            ],
            self::Admin => [
                'route' => 'admin.dashboard',
                'name' => 'Admin',
                'label' => 'Admin workspace',
                'initial' => 'A',
            ],
            self::Operator => [
                'route' => 'admin.dashboard',
                'name' => 'Operator',
                'label' => 'Operator workspace',
                'initial' => 'O',
            ],
            self::User => null,
        };
    }
}
