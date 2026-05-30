<?php

namespace App\Filament\Warga\Resources\JenisIuranWargas\Widgets;

use App\Models\JenisIuranWarga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class JenisIuranWargaOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $warga = auth()->user()?->warga;

        if (!$warga) {
            return [];
        }

        // Total jenis iuran yang berlaku di RT ini
        $totalJenis = JenisIuranWarga::where('id_rt', $warga->id_rt)->count();

        // Total nilai iuran per bulan (sum of semua jenis iuran di RT ini)
        $totalJumlah = JenisIuranWarga::where('id_rt', $warga->id_rt)->sum('jumlah');

        return [
            Stat::make('Total Jenis Iuran', "{$totalJenis} Jenis")
                ->description('Jenis iuran wajib yang terdaftar di RT Anda')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary'),

            Stat::make('Total Kewajiban per Bulan', 'Rp ' . number_format($totalJumlah, 0, ',', '.'))
                ->description('Akumulasi nilai seluruh jenis iuran bulanan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
