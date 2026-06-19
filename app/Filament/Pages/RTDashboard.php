<?php

namespace App\Filament\Pages;

use App\Filament\Rt\Widgets\ChartPendapatanBulananRT;
use App\Filament\Rt\Widgets\ChartPengeluaranBulananRT;
use App\Filament\Rt\Widgets\DoughnutChartPendapatanRT;
use App\Filament\Rt\Widgets\DoughnutChartPengeluaranRT;
use App\Filament\Rt\Widgets\StatsKeseluruhanRT;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class RTDashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected int|null|array $columns = 2;

    public function getHeading(): string|null|Htmlable
    {
        return 'Selamat Datang '.(auth()->user()?->rt?->nama ?? auth()->user()?->name);
    }

    public function getSubheading(): string|Htmlable|null
    {
        $nomorRt = auth()->user()?->rt?->nomor_rt;

        return $nomorRt
            ? 'Berikut adalah overview keuangan dan kas RT '.$nomorRt
            : 'Berikut adalah overview keuangan dan kas RT Anda';
    }

    public function getWidgets(): array
    {
        return [
            StatsKeseluruhanRT::class,
            ChartPendapatanBulananRT::class,
            ChartPengeluaranBulananRT::class,
            DoughnutChartPengeluaranRT::class,
            DoughnutChartPendapatanRT::class,
        ];
    }
}
