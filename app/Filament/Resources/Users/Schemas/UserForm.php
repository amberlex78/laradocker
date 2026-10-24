<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Facades\Filament;
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
                            ->options(fn (string $operation, ?User $record): array => self::roleOptions($operation, $record))
                            ->required()
                            ->rules(fn (string $operation, ?User $record): array => [
                                Rule::enum(UserRole::class),
                                Rule::in(array_keys(self::roleOptions($operation, $record))),
                            ])
                            ->native(false)
                            ->default(UserRole::User->value),
                    ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    private static function roleOptions(string $operation, ?User $record): array
    {
        $actor = Filament::auth()->user();

        if (! $actor instanceof User) {
            return [];
        }

        $roles = $actor->role->assignableRoles();

        if ($operation === 'edit' && $actor->role === UserRole::Admin && $record?->role === UserRole::Admin) {
            $roles[] = UserRole::Admin;
        }

        return collect($roles)
            ->unique()
            ->mapWithKeys(fn (UserRole $role): array => [$role->value => $role->getLabel()])
            ->all();
    }
}
