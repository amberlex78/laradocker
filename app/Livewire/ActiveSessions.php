<?php

namespace App\Livewire;

use App\Models\LoginSession;
use App\Services\DeviceDetectionService;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ActiveSessions extends TableWidget
{
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
                ->where('user_id', Filament::auth()->id())
                ->active())
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
                TextColumn::make('current')
                    ->label('')
                    ->state(fn (LoginSession $record): ?string => $record->isCurrent()
                        ? 'Current'
                        : null)
                    ->badge()
                    ->color('success'),
                TextColumn::make('ip_address')
                    ->label('IP address')
                    ->placeholder('Unknown'),
                TextColumn::make('last_seen_at')
                    ->label('Last activity')
                    ->state(fn (LoginSession $record): mixed => $record->last_seen_at)
                    ->dateTime(),
            ])
            ->defaultSort('last_seen_at', 'desc')
            ->paginated(false)
            ->recordUrl(null)
            ->emptyStateHeading('No active sessions');
    }
}
