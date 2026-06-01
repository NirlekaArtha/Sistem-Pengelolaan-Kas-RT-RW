<?php

namespace App\Providers\Filament;

use App\Http\Middleware\RedirectToProperPanel;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\RWDashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class RwPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id("rw")
            ->path("rw")
            ->navigationGroups(["Data Master", "Transaksi", "Laporan & Rekap"])
            ->colors([
                "primary" => Color::Amber,
            ])
            ->discoverResources(
                in: app_path("Filament/Rw/Resources"),
                for: "App\Filament\Rw\Resources",
            )
            ->discoverPages(
                in: app_path("Filament/Rw/Pages"),
                for: "App\Filament\Rw\Pages",
            )
            ->pages([RWDashboard::class])
            ->discoverWidgets(
                in: app_path("Filament/Rw/Widgets"),
                for: "App\Filament\Rw\Widgets",
            )
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
                RedirectToProperPanel::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
