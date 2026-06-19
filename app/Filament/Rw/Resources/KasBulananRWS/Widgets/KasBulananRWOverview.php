<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Widgets;

use App\Models\KasBulananRW;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KasBulananRWOverview extends BaseWidget
{
    // protected static ?string $pollingInterval = '15s';
    protected function getStats(): array
    {
        $rw = auth()->user()?->rw;
        $currentYear = date('Y');

        $query = KasBulananRW::query();
        if ($rw) {
            $query->where('id_rw', $rw->id);
        }

        $query->where('periode', 'like', "{$currentYear}-%");

        $totalPendapatan = $query->clone()->sum('total_pendapatan');
        $totalPengeluaran = $query->clone()->sum('total_pengeluaran');
        $totalPendapatanBersih = $query->clone()->sum('total_pendapatan_bersih');

        return [
            Stat::make('Total Pendapatan '.$currentYear, 'Rp '.number_format($totalPendapatan, 0, ',', '.'))
                ->description('Total pendapatan tahun ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Pengeluaran '.$currentYear, 'Rp '.number_format($totalPengeluaran, 0, ',', '.'))
                ->description('Total pengeluaran tahun ini')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make('Pendapatan Bersih '.$currentYear, 'Rp '.number_format($totalPendapatanBersih, 0, ',', '.'))
                ->description('Total pendapatan bersih tahun ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),
        ];
    }
}
