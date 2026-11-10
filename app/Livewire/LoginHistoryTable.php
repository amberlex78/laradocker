<?php

namespace App\Livewire;

use App\Enums\LoginSessionEndReason;
use App\Enums\LoginSessionStatus;
use App\Filament\Tables\Columns\DeviceColumn;
use App\Models\LoginSession;
use App\Services\LoginSessionStatusContext;
use App\Services\LoginSessionStatusResolver;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LoginHistoryTable extends TableWidget
{
    protected static bool $isDiscovered = false;

    public static function canView(): bool
    {
        return Filament::auth()->check();
    }

    public function table(Table $table): Table
    {
        $context = LoginSessionStatusContext::fromCurrentRequest();
        $resolver = new LoginSessionStatusResolver;
        $dateTimeFormat = config(
            'date_formats.date_time.'.app()->getLocale(),
            config('date_formats.date_time.'.config('date_formats.default_locale')),
        );

        return $table
            ->heading(null)
            ->description(null)
            ->query(fn (): Builder => LoginSession::query()
                ->where('user_id', Filament::auth()->id())
                ->where(function (Builder $query) use ($context): void {
                    $query
                        ->where('end_reason', LoginSessionEndReason::Terminated->value)
                        ->orWhereNotNull('logged_out_at')
                        ->orWhere(function (Builder $query) use ($context): void {
                            $query
                                ->whereNull('logged_out_at')
                                ->where('last_seen_at', '<', $context->inactivityThreshold);
                        });
                }))
            ->filters([
                SelectFilter::make('device')
                    ->label('Device')
                    ->options(fn (): array => LoginSession::query()
                        ->where('user_id', Filament::auth()->id())
                        ->whereNotNull('device')
                        ->distinct()
                        ->orderBy('device')
                        ->pluck('device', 'device')
                        ->all()),
                SelectFilter::make('operating_system')
                    ->label('Operating system')
                    ->options(fn (): array => LoginSession::query()
                        ->where('user_id', Filament::auth()->id())
                        ->whereNotNull('operating_system')
                        ->distinct()
                        ->orderBy('operating_system')
                        ->pluck('operating_system', 'operating_system')
                        ->all()),
                SelectFilter::make('status')
                    ->options([
                        LoginSessionStatus::Expired->value => LoginSessionStatus::Expired->getLabel(),
                        LoginSessionStatus::SignedOut->value => LoginSessionStatus::SignedOut->getLabel(),
                        LoginSessionStatus::Terminated->value => LoginSessionStatus::Terminated->getLabel(),
                    ])
                    ->query(function (Builder $query, array $data) use ($context): void {
                        match ($data['value'] ?? null) {
                            LoginSessionStatus::Expired->value => $query
                                ->where(function (Builder $query): void {
                                    $query
                                        ->whereNull('end_reason')
                                        ->orWhere('end_reason', '!=', LoginSessionEndReason::Terminated->value);
                                })
                                ->whereNull('logged_out_at')
                                ->where('last_seen_at', '<', $context->inactivityThreshold),
                            LoginSessionStatus::SignedOut->value => $query
                                ->whereNotNull('logged_out_at')
                                ->where(function (Builder $query): void {
                                    $query
                                        ->whereNull('end_reason')
                                        ->orWhere('end_reason', '!=', LoginSessionEndReason::Terminated->value);
                                }),
                            LoginSessionStatus::Terminated->value => $query
                                ->where('end_reason', LoginSessionEndReason::Terminated->value),
                            default => null,
                        };
                    }),
            ])
            ->columns([
                DeviceColumn::make(),
                TextColumn::make('ip_address')
                    ->label('IP address')
                    ->placeholder('Unknown')
                    ->sortable(),
                TextColumn::make('logged_in_at')
                    ->label('Signed in at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->state(fn (LoginSession $record): LoginSessionStatus => $resolver->resolveStatus($record, $context))
                    ->badge()
                    ->sortable(query: function (Builder $query, string $direction): void {
                        $direction = $direction === 'desc' ? 'desc' : 'asc';

                        $query->orderByRaw("COALESCE(logged_out_at, last_seen_at) {$direction}");
                    })
                    ->tooltip(fn (LoginSessionStatus $state): string => $state->getTooltip())
                    ->description(function (LoginSession $record, LoginSessionStatus $state) use ($dateTimeFormat): ?string {
                        return match ($state) {
                            LoginSessionStatus::Terminated,
                            LoginSessionStatus::SignedOut => $record->logged_out_at?->translatedFormat($dateTimeFormat),
                            LoginSessionStatus::Expired => $record->last_seen_at->translatedFormat($dateTimeFormat),
                            default => null,
                        };
                    }),
            ])
            ->defaultSort('logged_in_at', 'desc')
            ->paginationMode(PaginationMode::Default)
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(5)
            ->recordUrl(null)
            ->emptyStateHeading('No sign-ins yet');
    }
}
