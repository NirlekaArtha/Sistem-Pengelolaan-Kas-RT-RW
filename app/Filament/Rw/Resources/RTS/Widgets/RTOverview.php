<?php

namespace App\Filament\Rw\Resources\RTS\Widgets;

use App\Models\RT;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RTOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $rw = auth()->user()?->rw;

        $query = RT::query();
        if ($rw) {
            $query->where('id_rw', $rw->id);
        }

        $jumlahRT = $query->count();

        return [
            Stat::make('Jumlah RT', $jumlahRT)
                ->description('Total Rukun Tetangga aktif')
                ->descriptionIcon('heroicon-m-home')
                ->color('primary'),
        ];
    }
}
