<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\LogoutResponse;
use App\Models\IuranWarga;
use App\Models\Kasbon;
use App\Models\KasBulananRT;
use App\Models\KasBulananRW;
use App\Models\KasRT;
use App\Models\KasRW;
use App\Models\SetoranRW;
use App\Models\SlipGaji;
use App\Observers\IuranWargaObserver;
use App\Observers\KasbonObserver;
use App\Observers\KasBulananRtObserver;
use App\Observers\KasBulananRwObserver;
use App\Observers\KasRtObserver;
use App\Observers\KasRwObserver;
use App\Observers\SetoranRtObserver;
use App\Observers\SetoranRwObserver;
use App\Observers\SlipGajiObserver;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        \Filament\Auth\Http\Responses\Contracts\LoginResponse::class => LoginResponse::class,
        \Filament\Auth\Http\Responses\Contracts\LogoutResponse::class => LogoutResponse::class,
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
        // ── RW observers ──────────────────────────────────────────────────────
        KasRW::observe(KasRwObserver::class);
        SetoranRW::observe(SetoranRwObserver::class);
        SlipGaji::observe(SlipGajiObserver::class);
        Kasbon::observe(KasbonObserver::class);
        KasBulananRW::observe(KasBulananRwObserver::class);

        // ── RT observers ──────────────────────────────────────────────────────
        KasRT::observe(KasRtObserver::class);
        IuranWarga::observe(IuranWargaObserver::class);
        // SetoranRW also triggers RT recalculation (from RT's perspective)
        SetoranRW::observe(SetoranRtObserver::class);
        KasBulananRT::observe(KasBulananRtObserver::class);

        RedirectIfAuthenticated::redirectUsing(function ($request) {
            $user = auth()->user();
            if ($user) {
                return match ($user->role) {
                    UserRole::RW => UserRole::RW->getPath(),
                    UserRole::RT => UserRole::RT->getPath(),
                    UserRole::WARGA => UserRole::WARGA->getPath(),
                    default => '/',
                };
            }

            return '/';
        });
    }
}
