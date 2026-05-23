<?php

namespace App\Filament\Warga\Resources\IuranWargas\Widgets;

use App\Models\IuranWarga;
use App\Models\JenisIuranWarga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class IuranWargaOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $warga = $user?->warga;

        if (!$warga) {
            return [];
        }

        $currentPeriode = now()->format('Y-m');

        // Total jenis iuran yang berlaku untuk RT warga ini bulan ini
        $totalJenisIuran = JenisIuranWarga::where('id_rt', $warga->id_rt)->count();

        // Iuran yang sudah dibayar bulan ini
        $sudahDibayar = IuranWarga::where('id_warga', $warga->id)
            ->where('periode', $currentPeriode)
            ->where('status', 'dibayar')
            ->count();

        $totalSudahDibayar = IuranWarga::where('id_warga', $warga->id)
            ->where('status', 'dibayar')
            ->join('jenis_iuran_wargas', 'iuran_wargas.id_jenis_iuran', '=', 'jenis_iuran_wargas.id')
            ->sum('jenis_iuran_wargas.jumlah');

        // Belum bayar bulan ini
        $belumBayar = IuranWarga::where('id_warga', $warga->id)
            ->where('status', 'belum bayar')
            ->count();

        // Tunggakan (status telat) — semua periode
        $tunggakan = IuranWarga::where('id_warga', $warga->id)
            ->where('status', 'telat')
            ->count();


            // 1. Total Nominal Belum Bayar
        $totalBelumBayar = IuranWarga::where('id_warga', $warga->id)
            ->where('status', 'belum bayar')
            // Sesuaikan 'id_jenis_iuran' dengan foreign key yang ada di tabel iuran_warga kamu
            ->join('jenis_iuran_wargas', 'iuran_wargas.id_jenis_iuran', '=', 'jenis_iuran_wargas.id')
            ->sum('jenis_iuran_wargas.jumlah');

        // 2. Total Nominal Tunggakan (Telat)
        $totalTunggakan = IuranWarga::where('id_warga', $warga->id)
            ->where('status', 'telat')
            ->join('jenis_iuran_wargas', 'iuran_wargas.id_jenis_iuran', '=', 'jenis_iuran_wargas.id')
            ->sum('jenis_iuran_wargas.jumlah');

        $sudahLunas = $sudahDibayar >= $totalJenisIuran && $totalJenisIuran > 0;

        return [
            Stat::make('Iuran Dibayar Bulan Ini', "Rp " . number_format($totalSudahDibayar, 0, ",", "."))
                ->description("{$sudahDibayar} dari {$totalJenisIuran} iuran telah dibayar bulan ini")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($sudahLunas ? 'success' : 'primary'),

            Stat::make('Belum Bayar & Tunggakan', "Rp " . number_format($totalBelumBayar + $totalTunggakan, 0, ",", "."))
                ->description("{$belumBayar} belum dibayar dan {$tunggakan} tunggakan")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color(($belumBayar + $tunggakan) > 0 ? 'danger' : 'success'),
        ];
    }
}
