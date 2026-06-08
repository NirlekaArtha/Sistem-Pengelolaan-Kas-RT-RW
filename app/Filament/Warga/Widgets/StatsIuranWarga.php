<?php

namespace App\Filament\Warga\Widgets;

use App\Models\IuranWarga;
use App\Models\JenisIuranWarga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsIuranWarga extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected int|null|array $columns = [
        'sm' => 2,
        'xl' => 4,
    ];

    protected function getStats(): array
    {
        $user = auth()->user();
        $warga = $user?->warga;

        if (!$warga) {
            return [];
        }

        // ── Jenis Iuran yang berlaku untuk RT warga ini ─────────────────────
        $jenisIuranList = JenisIuranWarga::where('id_rt', $warga->id_rt)->get();
        $totalJenisIuran = $jenisIuranList->count();

        // ── Nominal wajib bayar per bulan (total semua jenis iuran) ─────────
        $nominalPerBulan = $jenisIuranList->sum('jumlah');

        // ── Iuran bulan ini ──────────────────────────────────────────────────
        $currentPeriode = now()->format('Y-m');

        $dibayarBulanIni = IuranWarga::where('id_warga', $warga->id)
            ->where('periode', $currentPeriode)
            ->where('status', 'dibayar')
            ->count();

        $sudahLunas = $totalJenisIuran > 0 && $dibayarBulanIni >= $totalJenisIuran;

        // ── Gabungan: tunggakan (telat) + belum bayar ────────────────────────
        $countTunggakan  = IuranWarga::where('id_warga', $warga->id)->where('status', 'telat')->count();
        $countBelumBayar = IuranWarga::where('id_warga', $warga->id)->where('status', 'belum bayar')->count();

        $totalKewajiban = IuranWarga::where('id_warga', $warga->id)
            ->whereIn('status', ['telat', 'belum bayar'])
            ->join('jenis_iuran_wargas', 'iuran_wargas.id_jenis_iuran', '=', 'jenis_iuran_wargas.id')
            ->sum('jenis_iuran_wargas.jumlah');

        $totalKewajiban = (float) $totalKewajiban;
        $adaKewajiban   = ($countTunggakan + $countBelumBayar) > 0;

        // ── Nama jenis iuran untuk deskripsi ────────────────────────────────
        $namaJenisIuran = $jenisIuranList->pluck('jenis_iuran')->implode(', ');
        $deskripsiJenis = $totalJenisIuran > 0
            ? ($namaJenisIuran ?: "{$totalJenisIuran} jenis iuran aktif")
            : 'Belum ada iuran';

        return [
            Stat::make('Jenis Iuran Aktif', $totalJenisIuran . ' Jenis')
                ->description($deskripsiJenis)
                ->descriptionIcon('heroicon-m-queue-list')
                ->color('info'),

            Stat::make('Nominal Iuran / Bulan', 'Rp ' . number_format($nominalPerBulan, 0, ',', '.'))
                ->description('Total kewajiban iuran setiap bulan')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Iuran Bulan Ini', "{$dibayarBulanIni} dari {$totalJenisIuran} Dibayar")
                ->description($sudahLunas ? 'Semua iuran bulan ini lunas!' : 'Belum semua iuran terbayar')
                ->descriptionIcon($sudahLunas ? 'heroicon-m-check-circle' : 'heroicon-m-clock')
                ->color($sudahLunas ? 'success' : ($dibayarBulanIni > 0 ? 'warning' : 'danger')),

            Stat::make('Tunggakan & Belum Bayar', 'Rp ' . number_format($totalKewajiban, 0, ',', '.'))
                ->description("{$countTunggakan} telat · {$countBelumBayar} belum dibayar")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($adaKewajiban ? 'danger' : 'success'),
        ];
    }
}
