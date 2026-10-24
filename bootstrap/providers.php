<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\OperatorPanelProvider;
use App\Providers\Filament\SitePanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    OperatorPanelProvider::class,
    SitePanelProvider::class,
];
