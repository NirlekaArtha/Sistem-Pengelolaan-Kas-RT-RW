<?php

namespace App\Providers;

use App\Models\Kasbon;
use App\Models\KasRW;
use App\Models\SetoranRW;
use App\Models\SlipGaji;
use App\Observers\KasbonObserver;
use App\Observers\KasRwObserver;
use App\Observers\SetoranRwObserver;
use App\Observers\SlipGajiObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        \Filament\Auth\Http\Responses\Contracts\LoginResponse::class =>
            \App\Http\Responses\LoginResponse::class,
        LogoutResponse::class => NewLogoutResponse::class,
        \Filament\Auth\Http\Responses\Contracts\LogoutResponse::class =>
            \App\Http\Responses\LogoutResponse::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Registers model observers so that KasBulananRW is automatically
     * recalculated whenever related data (kas harian, setoran RW, slip gaji,
     * or kasbon) is created, updated, or deleted.
     */
    public function boot(): void
    {
        KasRW::observe(KasRwObserver::class);
        SetoranRW::observe(SetoranRwObserver::class);
        SlipGaji::observe(SlipGajiObserver::class);
        Kasbon::observe(KasbonObserver::class);
    }
}
