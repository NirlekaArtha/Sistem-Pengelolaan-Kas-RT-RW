<?php

namespace App\Providers;

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
     */
    public function boot(): void
    {
        //
    }
}
