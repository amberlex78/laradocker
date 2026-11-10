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
        $detect = static function (
            LoginSession $record,
            DeviceDetectionService $deviceDetectionService,
        ) use (&$detectedDevices): array {
            $cacheKey = $record->getKey();

            if (isset($detectedDevices[$cacheKey])) {
                return $detectedDevices[$cacheKey];
            }

            if ($record->client !== null
                && $record->operating_system !== null
                && $record->device !== null) {
                return $detectedDevices[$cacheKey] = [
                    'client' => $record->client,
                    'operatingSystem' => $record->operating_system,
                    'device' => $record->device,
                ];
            }

            return $detectedDevices[$cacheKey] = $deviceDetectionService->detect($record->user_agent);
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
            ->description(function (
                LoginSession $record,
                DeviceDetectionService $deviceDetectionService,
            ) use ($detect): string {
                return $detect($record, $deviceDetectionService)['device'];
            })
            ->wrap();
    }
}
