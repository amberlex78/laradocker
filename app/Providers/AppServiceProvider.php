<?php

namespace App\Providers;

use App\Listeners\RecordLogoutActivity;
use App\Listeners\RecordRememberedLoginActivity;
use App\View\Composers\WorkspaceComposer;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, RecordRememberedLoginActivity::class);
        Event::listen(Logout::class, RecordLogoutActivity::class);
        View::composer('*', WorkspaceComposer::class);
    }
}
