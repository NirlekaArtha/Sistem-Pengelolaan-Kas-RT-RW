<?php

namespace App\Filament\Rt\Resources\KasBulananRTS\Widgets;

use App\Models\KasBulananRT;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KasBulananRTOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $rt = auth()->user()?->rt;
        $currentYear = date('Y');

        $query = KasBulananRT::query();
        if ($rt) {
            $query->where('id_rt', $rt->id);
        }

        $query->where('periode', 'like', "{$currentYear}-%");

        $totalPendapatan = $query->clone()->sum('total_pendapatan');
        $totalPengeluaran = $query->clone()->sum('total_pengeluaran');
        $totalPendapatanBersih = $query
            ->clone()
            ->sum('total_pendapatan_bersih');

        return [
            Stat::make(
                'Total Pendapatan '.$currentYear,
                'Rp '.number_format($totalPendapatan, 0, ',', '.'),
            )
                ->description('Total pendapatan tahun ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make(
                'Total Pengeluaran '.$currentYear,
                'Rp '.number_format($totalPengeluaran, 0, ',', '.'),
            )
                ->description('Total pengeluaran tahun ini')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make(
                'Pendapatan Bersih '.$currentYear,
                'Rp '.number_format($totalPendapatanBersih, 0, ',', '.'),
            )
                ->description('Total pendapatan bersih tahun ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),
        ];
    }
}
