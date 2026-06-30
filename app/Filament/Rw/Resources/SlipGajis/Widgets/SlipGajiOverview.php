<?php

namespace App\Filament\Rw\Resources\SlipGajis\Widgets;

use App\Enums\SlipGajiStatus;
use App\Models\Petugas;
use App\Models\SlipGaji;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SlipGajiOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $rw = auth()->user()?->rw;
        if (! $rw) {
            return [];
        }
        $currentPeriode = Carbon::now()->format('Y-m');

        // 1. Jumlah gaji bulan ini
        $totalGajiBulanIni = SlipGaji::whereHas('petugas', function (
            $query,
        ) use ($rw) {
            $query->where('id_rw', $rw->id);
        })
            ->where('periode', $currentPeriode)
            ->where('status', SlipGajiStatus::TELAH_DIBAYAR->value)
            ->sum('total');
        // 2. Jumlah petugas yang sudah digaji (e.g. 3 dari 9 Petugas)
        $totalPetugas = Petugas::where('id_rw', $rw->id)->count();
        $petugasSudahDigaji = SlipGaji::whereHas('petugas', function (
            $query,
        ) use ($rw) {
            $query->where('id_rw', $rw->id);
        })
            ->where('periode', $currentPeriode)
            ->where('status', SlipGajiStatus::TELAH_DIBAYAR->value)
            ->distinct('id_petugas')
            ->count('id_petugas');

        return [
            Stat::make(
                'Jumlah Gaji Bulan Ini',
                'Rp '.number_format($totalGajiBulanIni, 0, ',', '.'),
            )
                ->description('Total gaji petugas telah dibayar bulan ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make(
                'Petugas Sudah Gaji',
                "{$petugasSudahDigaji} dari {$totalPetugas} Petugas",
            )
                ->description('Petugas dengan slip gaji telah dibayar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
