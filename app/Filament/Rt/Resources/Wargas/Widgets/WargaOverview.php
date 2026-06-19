<?php

namespace App\Filament\Rt\Resources\Wargas\Widgets;

use App\Models\Warga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WargaOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $rt = auth()->user()?->rt;

        $query = Warga::query();
        if ($rt) {
            $query->where('id_rt', $rt->id);
        }

        $jumlahWarga = $query->count();

        return [
            Stat::make('Jumlah Warga', $jumlahWarga)
                ->description('Total KK Aktif')
                ->descriptionIcon('heroicon-m-home')
                ->color('primary'),
        ];
    }
}
