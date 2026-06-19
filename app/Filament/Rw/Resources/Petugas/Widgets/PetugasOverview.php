<?php

namespace App\Filament\Rw\Resources\Petugas\Widgets;

use App\Models\Petugas;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PetugasOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected int|null|array $columns = 3;

    protected function getStats(): array
    {
        $rw = auth()->user()?->rw;

        $query = Petugas::query();
        if ($rw) {
            $query->where('id_rw', $rw->id);
        }

        $jumlahPetugas = $query->clone()->count();
        $totalGajiPokok = $query->clone()->sum('gaji_pokok');

        return [
            Stat::make('Jumlah Petugas', $jumlahPetugas)
                ->description('Total petugas aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make(
                'Total Gaji Pokok',
                'Rp '.number_format($totalGajiPokok, 0, ',', '.'),
            )
                ->description('Total pengeluaran gaji pokok')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
