<?php

namespace App\Filament\Tables\Columns;

use App\Models\LoginSession;
use Filament\Tables\Columns\TextColumn;

final class DeviceColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('client')
            ->label('Device')
            ->state(fn (LoginSession $record): string => ($record->client ?? 'Unknown')
                .' / '.($record->operating_system ?? 'Unknown'))
            ->description(fn (LoginSession $record): string => $record->device ?? 'Unknown')
            ->wrap();
    }
}
