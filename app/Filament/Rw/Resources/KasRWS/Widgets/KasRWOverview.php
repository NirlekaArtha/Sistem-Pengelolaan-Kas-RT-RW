<?php

namespace App\Filament\Rw\Resources\KasRWS\Widgets;

use App\Models\KasRW;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KasRWOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $rw = auth()->user()?->rw;

        $query = KasRW::query();
        if ($rw) {
            $query->where('id_rw', $rw->id);
        }

        $totalMasuk = $query->clone()->where('tipe', 'masuk')->sum('jumlah');
        $totalKeluar = $query->clone()->where('tipe', 'keluar')->sum('jumlah');

        return [
            Stat::make('Total Masuk', 'Rp ' . number_format($totalMasuk, 0, ',', '.'))
                ->description('Total akumulasi kas masuk')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Keluar', 'Rp ' . number_format($totalKeluar, 0, ',', '.'))
                ->description('Total akumulasi kas keluar')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
