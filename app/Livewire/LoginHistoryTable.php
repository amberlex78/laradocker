<?php

namespace App\Livewire;

use App\Enums\LoginSessionEndReason;
use App\Enums\LoginSessionStatus;
use App\Filament\Tables\Columns\DeviceColumn;
use App\Models\LoginSession;
use App\Services\LoginSessionStatusContext;
use App\Services\LoginSessionStatusResolver;
use Carbon\CarbonInterface;
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
        $terminationReasonValues = LoginSessionEndReason::terminationValues();
        $statusDate = static function (LoginSession $record) use ($context, $resolver): ?CarbonInterface {
            return match ($resolver->resolveStatus($record, $context)) {
                LoginSessionStatus::Terminated,
                LoginSessionStatus::SignedOut => $record->logged_out_at,
                LoginSessionStatus::Expired => $record->expires_at,
                default => null,
            };
        };

        return $table
            ->heading(null)
            ->description(null)
            ->query(fn (): Builder => LoginSession::query()
                ->where('user_id', Filament::auth()->id())
                ->where(function (Builder $query) use ($context, $terminationReasonValues): void {
                    $query
                        ->whereIn('end_reason', $terminationReasonValues)
                        ->orWhereNotNull('logged_out_at')
                        ->orWhere(function (Builder $query) use ($context): void {
                            $query
                                ->whereNull('logged_out_at')
                                ->where('expires_at', '<', $context->currentTime);
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
                SelectFilter::make('device_model')
                    ->label('Device model')
                    ->options(fn (): array => LoginSession::query()
                        ->where('user_id', Filament::auth()->id())
                        ->whereNotNull('device_model')
                        ->distinct()
                        ->orderBy('device_model')
                        ->pluck('device_model', 'device_model')
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
                    ->query(function (Builder $query, array $data) use ($context, $terminationReasonValues): void {
                        match ($data['value'] ?? null) {
                            LoginSessionStatus::Expired->value => $query
                                ->where(function (Builder $query) use ($terminationReasonValues): void {
                                    $query
                                        ->whereNull('end_reason')
                                        ->orWhereNotIn('end_reason', $terminationReasonValues);
                                })
                                ->whereNull('logged_out_at')
                                ->where('expires_at', '<', $context->currentTime),
                            LoginSessionStatus::SignedOut->value => $query
                                ->whereNotNull('logged_out_at')
                                ->where(function (Builder $query) use ($terminationReasonValues): void {
                                    $query
                                        ->whereNull('end_reason')
                                        ->orWhereNotIn('end_reason', $terminationReasonValues);
                                }),
                            LoginSessionStatus::Terminated->value => $query
                                ->whereIn('end_reason', $terminationReasonValues),
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
                TextColumn::make('status_date')
                    ->label('Ended at')
                    ->state($statusDate)
                    ->dateTime()
                    ->tooltip(function (LoginSession $record) use ($context, $resolver): ?string {
                        return $resolver->resolveStatus($record, $context) === LoginSessionStatus::Expired
                            ? 'This is the last activity before the session expired.'
                            : null;
                    })
                    ->sortable(query: function (Builder $query, string $direction): void {
                        $direction = $direction === 'desc' ? 'desc' : 'asc';

                        $query->orderByRaw("COALESCE(logged_out_at, expires_at) {$direction}");
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->state(fn (LoginSession $record): LoginSessionStatus => $resolver->resolveStatus($record, $context))
                    ->badge()
                    ->description(fn (LoginSession $record, LoginSessionStatus $state): ?string => $state === LoginSessionStatus::Terminated
                        ? $record->end_reason?->getLabel()
                        : null),
            ])
            ->defaultSort('logged_in_at', 'desc')
            ->paginationMode(PaginationMode::Default)
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->recordUrl(null)
            ->emptyStateHeading('No sign-ins yet');
    }
}
