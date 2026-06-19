<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ProfilePage;
use App\Filament\Pages\RTDashboard;
use App\Http\Middleware\RedirectToProperPanel;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class RtPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('rt')
            ->path('rt')
            ->navigationGroups(['Data Master', 'Transaksi', 'Laporan & Rekap'])
            ->colors([
                'primary' => '#2563EB',
            ])
            ->viteTheme('resources/css/filament/theme.css')
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('Profil')
                    ->icon('heroicon-o-user')
                    ->url(fn (): string => ProfilePage::getUrl()),
            ])
            ->discoverResources(
                in: app_path('Filament/Rt/Resources'),
                for: "App\Filament\Rt\Resources",
            )
            ->discoverPages(
                in: app_path('Filament/Rt/Pages'),
                for: "App\Filament\Rt\Pages",
            )
            ->pages([RTDashboard::class, ProfilePage::class])
            ->discoverWidgets(
                in: app_path('Filament/Rt/Widgets'),
                for: "App\Filament\Rt\Widgets",
            )
            ->widgets([AccountWidget::class])
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
