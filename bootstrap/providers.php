<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\RtPanelProvider;
use App\Providers\Filament\RwPanelProvider;
use App\Providers\Filament\WargaPanelProvider;

return [
    AppServiceProvider::class,
    RtPanelProvider::class,
    RwPanelProvider::class,
    WargaPanelProvider::class,
];
