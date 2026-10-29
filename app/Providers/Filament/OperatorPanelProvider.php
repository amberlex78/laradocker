<?php

namespace App\Providers\Filament;

use App\Enums\UserRole;
use App\Filament\Widgets\SystemOverview;
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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class OperatorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('operator')
            ->path('operator')
            ->brandLogo(fn (): string => asset('images/laradocker-logo.svg'))
            ->darkModeBrandLogo(fn (): string => asset('images/laradocker-logo-dark.svg'))
            ->brandLogoHeight('2.5rem')
            ->globalSearch(false)
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Slate,
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
                Action::make('admin-panel')
                    ->label('Admin panel')
                    ->url(fn (): ?string => filament()->getPanel('admin')->getUrl())
                    ->icon(Heroicon::OutlinedSquares2x2)
                    ->visible(fn (): bool => in_array(filament()->auth()->user()->role, [
                        UserRole::Admin,
                        UserRole::Developer,
                    ], true)),
                Action::make('public-site')
                    ->label('Public site')
                    ->url('/')
                    ->icon(Heroicon::OutlinedGlobeAlt)
                    ->openUrlInNewTab(),
            ])
            ->discoverResources(in: app_path('Filament/Operator/Resources'), for: 'App\Filament\Operator\Resources')
            ->discoverPages(in: app_path('Filament/Operator/Pages'), for: 'App\Filament\Operator\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Operator/Widgets'), for: 'App\Filament\Operator\Widgets')
            ->widgets([
                SystemOverview::class,
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
