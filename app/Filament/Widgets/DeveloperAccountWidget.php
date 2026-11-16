<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use Filament\Facades\Filament;
use Filament\Widgets\AccountWidget;

class DeveloperAccountWidget extends AccountWidget
{
    /**
     * @var view-string
     */
    protected string $view = 'filament.widgets.developer-account-widget';

    public static function canView(): bool
    {
        return Filament::auth()->user()?->role === UserRole::Developer;
    }
}
