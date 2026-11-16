<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->filters([
                SelectFilter::make('role')
                    ->label('Role')
                    ->placeholder('All')
                    ->options(function (): array {
                        $currentUser = Filament::auth()->user();

                        if ($currentUser?->role === UserRole::Developer) {
                            return [
                                UserRole::User->value => UserRole::User->getLabel(),
                                UserRole::Operator->value => UserRole::Operator->getLabel(),
                                UserRole::Admin->value => UserRole::Admin->getLabel(),
                                UserRole::Developer->value => UserRole::Developer->getLabel(),
                            ];
                        }

                        return [
                            UserRole::User->value => UserRole::User->getLabel(),
                            UserRole::Operator->value => UserRole::Operator->getLabel(),
                            UserRole::Admin->value => UserRole::Admin->getLabel(),
                        ];
                    }),
                TernaryFilter::make('email_verified_at')
                    ->label('Verified')
                    ->nullable()
                    ->placeholder('All')
                    ->trueLabel('Verified')
                    ->falseLabel('Unverified'),
            ])
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->sortable()
                    ->state(fn (User $record): string => $record->email_verified_at ? 'Verified' : 'Unverified')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Verified' ? 'success' : 'gray'),
                TextColumn::make('role')
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', direction: 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
