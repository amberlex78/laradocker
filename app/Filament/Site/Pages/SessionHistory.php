<?php

namespace App\Filament\Site\Pages;

use App\Livewire\LoginHistoryTable;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SessionHistory extends Page
{
    protected static ?string $slug = 'history';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Session history';

    protected static ?int $navigationSort = 30;

    protected static ?string $title = 'Session history';

    public function getSubheading(): string
    {
        return 'Review your login sessions, including active, signed-out, expired, and terminated sessions.';
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Livewire::make(LoginHistoryTable::class),
            ]);
    }
}
