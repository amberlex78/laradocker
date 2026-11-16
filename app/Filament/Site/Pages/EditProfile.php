<?php

namespace App\Filament\Site\Pages;

use App\Enums\UserRole;
use BackedEnum;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class EditProfile extends BaseEditProfile
{
    protected static ?string $slug = 'profile/edit';

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
        return 'Update your profile details and keep your password secure.';
    }

    public function getMaxContentWidth(): Width
    {
        return Width::ThreeExtraLarge;
    }

    public function form(Schema $schema): Schema
    {
        $user = filament()->auth()->user();

        return $schema
            ->components([
                Section::make($user->name)
                    ->description($user->email)
                    ->columns(1)
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->schema([
                        $this->getNameFormComponent(),
                        ...(in_array($user->role, [UserRole::Admin, UserRole::Developer], true)
                            ? [$this->getEmailFormComponent()]
                            : []),
                        $this->getCurrentPasswordFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->inlineLabel(),
            ]);
    }
}
