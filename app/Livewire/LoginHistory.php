<?php

namespace App\Livewire;

use App\Enums\LoginSessionEndReason;
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
    private const DATE_TIME_FORMAT = 'M j, Y H:i:s';

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
                    ->dateTime(self::DATE_TIME_FORMAT),
                TextColumn::make('status')
                    ->label('Status')
                    ->state(fn (LoginSession $record): string => self::statusOf($record))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Terminated' => 'danger',
                        default => 'gray',
                    })
                    ->tooltip(fn (LoginSession $record): string => match (self::statusOf($record)) {
                        'Active' => 'This session is still signed in.',
                        'Signed out' => 'You signed out of this session.',
                        'Terminated' => 'Ended remotely with "Terminate other sessions".',
                        default => 'The session timed out due to inactivity. Shown time is the last activity.',
                    })
                    ->description(fn (LoginSession $record): ?string => match (self::statusOf($record)) {
                        'Terminated', 'Signed out' => $record->logged_out_at?->format(self::DATE_TIME_FORMAT),
                        'Expired' => $record->last_seen_at->format(self::DATE_TIME_FORMAT),
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

    private static function statusOf(LoginSession $record): string
    {
        return match (true) {
            $record->end_reason === LoginSessionEndReason::Terminated => 'Terminated',
            $record->logged_out_at !== null => 'Signed out',
            $record->last_seen_at->lt(now()->subMinutes((int) config('session.lifetime'))) => 'Expired',
            default => 'Active',
        };
    }
}
