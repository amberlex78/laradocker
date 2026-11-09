<?php

namespace App\Livewire;

use App\Actions\Auth\TerminateOtherSessions;
use App\Enums\LoginSessionStatus;
use App\Filament\Tables\Columns\DeviceColumn;
use App\Models\LoginSession;
use App\Services\LoginSessionStatusContext;
use App\Services\LoginSessionStatusResolver;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ActiveSessionsTable extends TableWidget
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

        return $table
            ->heading(null)
            ->description(null)
            ->query(fn (): Builder => LoginSession::query()
                ->where('user_id', Filament::auth()->id())
                ->active())
            ->headerActions([
                Action::make('terminateOtherSessions')
                    ->label('Terminate other sessions')
                    ->color('danger')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->modalWidth(Width::Small)
                    ->schema([
                        TextInput::make('currentPassword')
                            ->label('Current password')
                            ->password()
                            ->revealable()
                            ->autocomplete('current-password')
                            ->placeholder('Confirm your current password')
                            ->currentPassword(guard: Filament::getAuthGuard())
                            ->required(),
                    ])
                    ->action(function (array $data, TerminateOtherSessions $terminateOtherSessions): void {
                        $terminateOtherSessions->handle($data['currentPassword']);

                        Notification::make()
                            ->title('Other sessions terminated')
                            ->success()
                            ->send();
                    }),
            ])
            ->columns([
                DeviceColumn::make(),
                TextColumn::make('current')
                    ->label('')
                    ->state(fn (LoginSession $record): ?LoginSessionStatus => $resolver->resolveStatus($record, $context) === LoginSessionStatus::Current
                        ? LoginSessionStatus::Current
                        : null)
                    ->badge(),
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
