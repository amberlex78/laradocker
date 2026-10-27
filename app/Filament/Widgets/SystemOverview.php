<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected ?string $pollingInterval = null;

    /**
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        $isDeveloper = Filament::auth()->user()?->role === UserRole::Developer;

        $query = User::query()->toBase();

        if (! $isDeveloper) {
            $query->where('role', '!=', UserRole::Developer->value);
        }

        $counts = $query
            ->selectRaw('COUNT(*) as total')
            ->selectRaw(
                'SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as administrators',
                [UserRole::Admin->value],
            )
            ->selectRaw(
                'SUM(CASE WHEN role = ? THEN 1 ELSE 0 END) as operators',
                [UserRole::Operator->value],
            )
            ->first();

        [$system, $environment] = array_pad(explode(' / ', $this->getSystemLabel(), 2), 2, null);

        return [
            Stat::make('Total users', (int) ($counts->total ?? 0))
                ->icon(Heroicon::OutlinedUserGroup)
                ->description('In the system')
                ->descriptionColor(Color::Green),
            Stat::make('Administrators', (int) ($counts->administrators ?? 0))
                ->icon(Heroicon::OutlinedShieldCheck)
                ->description('Admin panel access')
                ->descriptionColor(Color::Amber),
            Stat::make('Operators', (int) ($counts->operators ?? 0))
                ->icon(Heroicon::OutlinedCpuChip)
                ->description('Operator panel access')
                ->descriptionColor(Color::Blue),
            Stat::make('Your system', $system)
                ->icon(Heroicon::OutlinedComputerDesktop)
                ->description($environment)
                ->descriptionColor(Color::Purple),
        ];
    }

    private function getSystemLabel(): string
    {
        $userAgent = request()->userAgent() ?? '';

        return implode(' / ', [
            $this->detectOperatingSystem($userAgent),
            $this->detectDeviceType($userAgent),
            $this->detectBrowser($userAgent),
        ]);
    }

    private function detectOperatingSystem(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'iPhone'), str_contains($userAgent, 'iPod') => 'iOS',
            str_contains($userAgent, 'iPad') => 'iPadOS',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Macintosh'), str_contains($userAgent, 'Mac OS X') => 'macOS',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => 'Unknown OS',
        };
    }

    private function detectDeviceType(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'iPad'),
            str_contains($userAgent, 'Tablet'),
            str_contains($userAgent, 'Android') && ! str_contains($userAgent, 'Mobile') => 'Tablet',
            str_contains($userAgent, 'Mobile'),
            str_contains($userAgent, 'iPhone'),
            str_contains($userAgent, 'iPod') => 'Mobile',
            default => 'Desktop',
        };
    }

    private function detectBrowser(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Edg/') => 'Edge',
            str_contains($userAgent, 'OPR/') => 'Opera',
            str_contains($userAgent, 'Chrome/') => 'Chrome',
            str_contains($userAgent, 'Firefox/') => 'Firefox',
            str_contains($userAgent, 'Safari/') => 'Safari',
            default => 'Unknown browser',
        };
    }
}
