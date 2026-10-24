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
                            ->required()
                            ->enum(UserRole::class)
                            ->options(fn (string $operation, ?User $record): array => self::roleOptions($operation, $record))
                            ->disabled(function (string $operation, ?User $record): bool {
                                $actor = Filament::auth()->user();

                                return $operation === 'edit'
                                    && $actor instanceof User
                                    && $actor->role === UserRole::Admin
                                    && $record?->id === $actor->id;
                            })
                            ->native(false)
                            ->rules(fn (string $operation, ?User $record): array => [
                                Rule::in(array_keys(self::roleOptions($operation, $record))),
                            ]),
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

        $roles = collect(UserRole::cases())
            ->filter(fn (UserRole $role): bool => $actor->role->canManage($role));

        if ($operation === 'edit' && $record?->id === $actor->id) {
            $roles->push($record->role);
        }

        return $roles
            ->unique()
            ->mapWithKeys(fn (UserRole $role): array => [$role->value => $role->getLabel()])
            ->all();
    }
}
