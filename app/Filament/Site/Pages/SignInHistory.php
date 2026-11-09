<?php

namespace App\Filament\Site\Pages;

use App\Livewire\LoginHistory;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SignInHistory extends Page
{
    protected static ?string $slug = 'history';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Session history';

    protected static ?int $navigationSort = 30;

    protected static ?string $title = 'Session history';

    protected string $view = 'filament.site.pages.sign-in-history';

    public function getSubheading(): string
    {
        return 'Review your login sessions, including active, signed-out, expired, and terminated sessions.';
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Livewire::make(LoginHistory::class),
            ]);
    }
}
