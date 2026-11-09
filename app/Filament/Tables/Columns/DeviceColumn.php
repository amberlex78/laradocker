<?php

namespace App\Filament\Tables\Columns;

use App\Models\LoginSession;
use App\Services\DeviceDetectionService;
use Filament\Tables\Columns\TextColumn;

final class DeviceColumn
{
    public static function make(): TextColumn
    {
        $detectedDevices = [];
        $detect = function (
            LoginSession $record,
            DeviceDetectionService $deviceDetectionService,
        ) use (&$detectedDevices): array {
            $userAgent = $record->user_agent;
            $cacheKey = $userAgent ?? '';

            return $detectedDevices[$cacheKey] ??= $deviceDetectionService->detect($userAgent);
        };

        return TextColumn::make('device')
            ->label('Device')
            ->state(function (
                LoginSession $record,
                DeviceDetectionService $deviceDetectionService,
            ) use ($detect): string {
                $details = $detect($record, $deviceDetectionService);

                return $details['client'].' / '.$details['operatingSystem'];
            })
            ->description(fn (
                LoginSession $record,
                DeviceDetectionService $deviceDetectionService,
            ): string => $detect($record, $deviceDetectionService)['device'])
            ->wrap();
    }
}
