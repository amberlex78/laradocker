<?php

namespace App\Providers\Filament;

use App\Http\Middleware\RedirectGuestsToSiteLogin;
use Filament\Actions\Action;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->globalSearch(false)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                'profile' => fn (Action $action): Action => $action
                    ->label(fn (): string => filament()->getUserName(filament()->auth()->user()))
                    ->url(fn (): ?string => filament()->getPanel('site')->getProfileUrl())
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->sort(-2),
                Action::make('email')
                    ->label(fn (): string => filament()->auth()->user()->email)
                    ->disabled()
                    ->sort(-1),
                Action::make('operator-panel')
                    ->label('Operator panel')
                    ->url(fn (): ?string => filament()->getPanel('operator')->getUrl())
                    ->icon(Heroicon::OutlinedSquares2x2),
                Action::make('public-site')
                    ->label('Public site')
                    ->url('/')
                    ->icon(Heroicon::OutlinedGlobeAlt)
                    ->openUrlInNewTab(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                RedirectGuestsToSiteLogin::class,
            ]);
    }
}
