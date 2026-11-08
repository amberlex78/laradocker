<?php

namespace App\Livewire;

use App\Enums\LoginSessionStatus;
use App\Models\LoginSession;
use App\Services\DeviceDetectionService;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LoginHistory extends TableWidget
{
    private const DISPLAY_DATE_TIME_FORMAT = 'M j, Y H:i:s';

    protected static bool $isDiscovered = false;

    public static function canView(): bool
    {
        return Filament::auth()->check();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(null)
            ->description(null)
            ->query(fn (): Builder => LoginSession::query()
                ->where('user_id', Filament::auth()->id()))
            ->columns([
                TextColumn::make('device')
                    ->label('Device')
                    ->state(function (
                        LoginSession $record,
                        DeviceDetectionService $deviceDetectionService,
                    ): string {
                        $details = $deviceDetectionService->detect($record->user_agent);

                        return $details['client'].' / '.$details['operatingSystem'];
                    })
                    ->description(fn (
                        LoginSession $record,
                        DeviceDetectionService $deviceDetectionService,
                    ): string => $deviceDetectionService->detect($record->user_agent)['device'])
                    ->wrap(),
                TextColumn::make('ip_address')
                    ->label('IP address')
                    ->placeholder('Unknown'),
                TextColumn::make('logged_in_at')
                    ->label('Signed in at')
                    ->dateTime(self::DISPLAY_DATE_TIME_FORMAT),
                TextColumn::make('status')
                    ->label('Status')
                    ->state(fn (LoginSession $record): LoginSessionStatus => $record->resolveStatus())
                    ->badge()
                    ->tooltip(fn (LoginSession $record): string => $record->resolveStatus()->getTooltip())
                    ->description(fn (LoginSession $record): ?string => match ($record->resolveStatus()) {
                        LoginSessionStatus::Terminated, LoginSessionStatus::SignedOut => $record->logged_out_at?->format(self::DISPLAY_DATE_TIME_FORMAT),
                        LoginSessionStatus::Expired => $record->last_seen_at->format(self::DISPLAY_DATE_TIME_FORMAT),
                        default => null,
                    }),
            ])
            ->defaultSort('logged_in_at', 'desc')
            ->paginationMode(PaginationMode::Default)
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->recordUrl(null)
            ->emptyStateHeading('No sign-ins yet');
    }
}
