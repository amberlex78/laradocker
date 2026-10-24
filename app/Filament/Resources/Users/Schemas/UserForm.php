<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->autocomplete('new-password')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->rule(Password::defaults())
                            ->maxLength(255),
                        Select::make('role')
                            ->required()
                            ->native(false)
                            ->enum(UserRole::class)
                            ->options([
                                UserRole::User->value => UserRole::User->getLabel(),
                                UserRole::Operator->value => UserRole::Operator->getLabel(),
                            ])
                            ->rules([
                                Rule::in([
                                    UserRole::User->value,
                                    UserRole::Operator->value,
                                ]),
                            ]),
                    ]),
            ]);
    }
}
