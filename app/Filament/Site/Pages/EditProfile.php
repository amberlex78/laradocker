<?php

namespace App\Filament\Site\Pages;

use App\Livewire\ActiveSessions;
use App\Livewire\LoginHistory;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;

class EditProfile extends BaseEditProfile
{
    public static function getLabel(): string
    {
        return 'My profile';
    }

    public function getHeading(): string
    {
        return 'Manage your account';
    }

    public function getSubheading(): string
    {
        return 'Update your profile details and keep your password secure.';
    }

    public function getMaxContentWidth(): Width
    {
        return Width::SevenExtraLarge;
    }

    public function defaultForm(Schema $schema): Schema
    {
        return parent::defaultForm($schema)
            ->inlineLabel(false);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
                ...Arr::wrap($this->getMultiFactorAuthenticationContentComponent()),
                Section::make('Active sessions')
                    ->description('Devices currently signed in to your account.')
                    ->icon(Heroicon::OutlinedComputerDesktop)
                    ->schema([
                        Livewire::make(ActiveSessions::class),
                    ]),
                Section::make('Sign-in history')
                    ->description('Recent successful sign-ins to your account.')
                    ->icon(Heroicon::OutlinedClock)
                    ->schema([
                        Livewire::make(LoginHistory::class),
                    ]),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account information')
                    ->description('Update your profile information and password.')
                    ->columns(2)
                    ->schema([
                        Fieldset::make('Profile information')
                            ->columns(1)
                            ->schema([
                                $this->getNameFormComponent(),
                                $this->getEmailFormComponent(),
                            ]),
                        Fieldset::make('Password')
                            ->columns(1)
                            ->schema([
                                $this->getCurrentPasswordFormComponent(),
                                $this->getPasswordFormComponent(),
                                $this->getPasswordConfirmationFormComponent(),
                            ]),
                    ]),
            ]);
    }
}
