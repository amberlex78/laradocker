<?php

namespace App\Filament\Site\Pages;

use App\Livewire\ActiveSessions as ActiveSessionsWidget;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ActiveSessions extends Page
{
    protected static ?string $slug = 'sessions';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static ?string $navigationLabel = 'Active sessions';

    protected static ?int $navigationSort = 20;

    protected static ?string $title = 'Active sessions';

    protected string $view = 'filament.site.pages.active-sessions';

    public function getSubheading(): string
    {
        return 'Devices currently signed in to your account.';
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Livewire::make(ActiveSessionsWidget::class),
            ]);
    }
}
