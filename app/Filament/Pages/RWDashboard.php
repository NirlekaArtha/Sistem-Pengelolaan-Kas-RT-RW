<?php

namespace App\Filament\Pages;

use App\Filament\Rw\Widgets\ChartPendapatanBulanan;
use App\Filament\Rw\Widgets\ChartPengeluaranBulanan;
use App\Filament\Rw\Widgets\DoughnutChartPendapatan;
use App\Filament\Rw\Widgets\DoughnutChartPengeluaran;
use App\Filament\Rw\Widgets\StatsKeseluruhan;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class RWDashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected int|null|array $columns = 2;

    public function getHeading(): string|null|Htmlable
    {
        return 'Selamat Datang '.auth()->user()?->rw?->nama;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Berikut adalah overview keuangan dan kas RW '.
            auth()->user()?->rw?->nomor_rw;
    }

    public function getWidgets(): array
    {
        return [
            StatsKeseluruhan::class,
            ChartPendapatanBulanan::class,
            ChartPengeluaranBulanan::class,
            DoughnutChartPengeluaran::class,
            DoughnutChartPendapatan::class,
        ];
    }
}
