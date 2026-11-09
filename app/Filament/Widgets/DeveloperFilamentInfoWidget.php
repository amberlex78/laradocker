<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use Filament\Facades\Filament;
use Filament\Widgets\FilamentInfoWidget;

class DeveloperFilamentInfoWidget extends FilamentInfoWidget
{
    public static function canView(): bool
    {
        return Filament::auth()->user()?->role === UserRole::Developer;
    }
}
