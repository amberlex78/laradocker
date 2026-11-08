<?php

namespace App\Filament\Tables\Columns;

use App\Models\LoginSession;
use App\Services\DeviceDetectionService;
use Filament\Tables\Columns\TextColumn;

final class DeviceColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('device')
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
            ->wrap();
    }
}
