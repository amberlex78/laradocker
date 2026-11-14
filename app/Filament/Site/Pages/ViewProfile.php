<?php

namespace App\Filament\Site\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ViewProfile extends Page
{
    protected static ?string $slug = 'profile';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $navigationLabel = 'My profile';

    protected static ?int $navigationSort = 10;

    public static function getLabel(): string
    {
        return 'My profile';
    }

    public function getHeading(): string
    {
        return 'My profile';
    }

    public function getSubheading(): string
    {
        return 'View your account details and manage your profile settings.';
    }

    public function content(Schema $schema): Schema
    {
        /** @var User $user */
        $user = Filament::auth()->user();

        return $schema
            ->components([
                Section::make($user->name)
                    ->description($user->email)
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name')
                            ->state($user->name),
                        TextEntry::make('email')
                            ->label('Email')
                            ->state($user->email),
                        TextEntry::make('role')
                            ->label('Role')
                            ->badge()
                            ->state($user->role),
                        TextEntry::make('created_at')
                            ->label('Member since')
                            ->dateTime()
                            ->state($user->created_at),
                    ])
                    ->columns(2),
                Actions::make([
                    Action::make('edit')
                        ->label('Edit profile')
                        ->icon(Heroicon::OutlinedPencilSquare)
                        ->url(EditProfile::getUrl()),
                ]),
            ]);
    }
}
