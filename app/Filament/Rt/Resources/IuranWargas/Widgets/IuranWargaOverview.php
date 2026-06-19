<?php

namespace App\Filament\Rt\Resources\IuranWargas\Widgets;

use App\Enums\IuranWargaStatus;
use App\Models\IuranWarga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class IuranWargaOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $rt = auth()->user()?->rt;
        $periodeBerjalan = now()->format('Y-m');

        $query = IuranWarga::query();
        if ($rt) {
            $query->where('id_rt', $rt->id);
        }

        $jumlahIuranWarga = (clone $query)
            ->where('periode', $periodeBerjalan)
            ->where('status', IuranWargaStatus::DIBAYAR->value)
            ->count();
        $totalIuranWargaBulanIni = (clone $query)
            ->where('periode', $periodeBerjalan)
            ->where('status', IuranWargaStatus::DIBAYAR->value)
            ->with('jenisIuran')
            ->get()
            ->sum(function ($iuran) {
                return $iuran->jenisIuran->jumlah ?? 0;
            });

        $jumlahIuranBelumBayar = (clone $query)
            ->where('periode', $periodeBerjalan)
            ->where('status', IuranWargaStatus::BELUM_BAYAR->value)
            ->count();
        $totalIuranBelumBayar = (clone $query)
            ->where('periode', $periodeBerjalan)
            ->where('status', IuranWargaStatus::BELUM_BAYAR->value)
            ->with('jenisIuran')
            ->get()
            ->sum(function ($iuran) {
                return $iuran->jenisIuran->jumlah ?? 0;
            });

        $jumlahTunggakan = (clone $query)->where('status', IuranWargaStatus::TELAT->value)->count();
        $totalTunggakan = (clone $query)
            ->where('status', IuranWargaStatus::TELAT->value)
            ->with('jenisIuran')
            ->get()
            ->sum(function ($iuran) {
                return $iuran->jenisIuran->jumlah ?? 0;
            });

        return [
            Stat::make(
                'Total Iuran Bulan Ini ',
                'Rp '.number_format($totalIuranWargaBulanIni, 0, ',', '.'),
            )
                ->description(
                    'Dari '.$jumlahIuranWarga.' iuran yang dibayar',
                )
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info'),
            Stat::make(
                'Total Belum Bayar Bulan Ini ',
                'Rp '.number_format($totalIuranBelumBayar, 0, ',', '.'),
            )
                ->description(
                    'Dari '.
                        $jumlahIuranBelumBayar.
                        ' iuran yang belum dibayar',
                )
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            Stat::make(
                'Total Tunggakan',
                'Rp '.number_format($totalTunggakan, 0, ',', '.'),
            )
                ->description('Dari '.$jumlahTunggakan.' tunggakan')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),
        ];
    }
}
