<?php

namespace App\Livewire;

use App\Enums\LoginSessionStatus;
use App\Filament\Tables\Columns\DeviceColumn;
use App\Models\LoginSession;
use App\Services\LoginSessionStatusContext;
use App\Services\LoginSessionStatusResolver;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LoginHistoryTable extends TableWidget
{
    private const DISPLAY_DATE_TIME_FORMAT = 'M j, Y H:i:s';

    protected static bool $isDiscovered = false;

    public static function canView(): bool
    {
        return Filament::auth()->check();
    }

    public function table(Table $table): Table
    {
        $context = LoginSessionStatusContext::fromCurrentRequest();
        $resolver = new LoginSessionStatusResolver;

        return $table
            ->heading(null)
            ->description(null)
            ->query(fn (): Builder => LoginSession::query()
                ->where('user_id', Filament::auth()->id()))
            ->columns([
                DeviceColumn::make(),
                TextColumn::make('ip_address')
                    ->label('IP address')
                    ->placeholder('Unknown'),
                TextColumn::make('logged_in_at')
                    ->label('Signed in at')
                    ->dateTime(self::DISPLAY_DATE_TIME_FORMAT),
                TextColumn::make('status')
                    ->label('Status')
                    ->state(fn (LoginSession $record): LoginSessionStatus => $resolver->resolveStatus($record, $context))
                    ->badge()
                    ->tooltip(fn (LoginSession $record): string => $resolver->resolveStatus($record, $context)->getTooltip())
                    ->description(fn (LoginSession $record): ?string => match ($resolver->resolveStatus($record, $context)) {
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
