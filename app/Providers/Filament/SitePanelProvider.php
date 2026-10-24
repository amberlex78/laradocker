<?php

namespace App\Providers\Filament;

use App\Enums\UserRole;
use App\Filament\Site\Pages\EditProfile;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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

class SitePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('site')
            ->path('')
            ->login()
            ->profile(EditProfile::class, isSimple: false)
            ->navigation(false)
            ->colors([
                'primary' => Color::Green,
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
                    ->visible(fn (): bool => filament()->auth()->user()->role === UserRole::Admin),
                Action::make('operator-panel')
                    ->label('Operator panel')
                    ->url(fn (): ?string => filament()->getPanel('operator')->getUrl())
                    ->icon(Heroicon::OutlinedSquares2x2)
                    ->visible(fn (): bool => in_array(filament()->auth()->user()->role, [
                        UserRole::Admin,
                        UserRole::Operator,
                    ], true)),
                Action::make('public-site')
                    ->label('Public site')
                    ->url('/')
                    ->icon(Heroicon::OutlinedGlobeAlt),
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
                Authenticate::class,
            ]);
    }
}
