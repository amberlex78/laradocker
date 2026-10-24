<?php

namespace App\Filament\Site\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Arr;

class EditProfile extends BaseEditProfile
{
    public static function getLabel(): string
    {
        return 'My profile';
    }

    public function getMaxContentWidth(): Width
    {
        return Width::FourExtraLarge;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        $this->getFormContentComponent(),
                    ]),
                ...Arr::wrap($this->getMultiFactorAuthenticationContentComponent()),
            ]);
    }
}
