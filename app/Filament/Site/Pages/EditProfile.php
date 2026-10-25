<?php

namespace App\Filament\Site\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
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
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 2,
                ])
                    ->schema([
                        Section::make('Profile information')
                            ->description('Update your profile information and email address.')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'sm' => 2,
                                ])->schema([
                                    $this->getNameFormComponent(),
                                    $this->getEmailFormComponent(),
                                ]),
                            ]),
                        Section::make('Update password')
                            ->description('Use a strong, unique password to keep your account secure.')
                            ->schema([
                                $this->getCurrentPasswordFormComponent(),
                                Grid::make([
                                    'default' => 1,
                                    'sm' => 2,
                                ])->schema([
                                    $this->getPasswordFormComponent()
                                        ->columnSpan(fn (Get $get): int => filled($get('password')) ? 1 : 2),
                                    $this->getPasswordConfirmationFormComponent(),
                                ]),
                            ]),
                    ]),
            ]);
    }
}
