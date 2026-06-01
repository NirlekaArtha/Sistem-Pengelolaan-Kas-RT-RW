<?php

namespace App\Filament\Rw\Resources\Kasbons\Widgets;

use App\Models\Kasbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KasbonOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $rw = auth()->user()?->rw;

        $query = Kasbon::query();
        if ($rw) {
            $query->whereHas("petugas", function ($q) use ($rw) {
                $q->where("id_rw", $rw->id);
            });
        }

        $jumlahRiwayatKasbon = $query
            ->clone()
            ->whereMonth("tanggal", now()->month)
            ->whereYear("tanggal", now()->year)
            ->count();
        $totalNominalKasbonBulanIni = $query
            ->clone()
            ->whereMonth("tanggal", now()->month)
            ->whereYear("tanggal", now()->year)
            ->sum("jumlah");

        return [
            Stat::make("Jumlah Riwayat Kasbon bulan ini", $jumlahRiwayatKasbon)
                ->description("Total riwayat kasbon petugas")
                ->descriptionIcon("heroicon-m-clipboard-document-list")
                ->color("info"),
            Stat::make(
                "Nominal Kasbon Bulan Ini",
                "Rp " . number_format($totalNominalKasbonBulanIni, 0, ",", "."),
            )
                ->description("Total kasbon pada bulan berjalan")
                ->descriptionIcon("heroicon-m-banknotes")
                ->color("success"),
        ];
    }
}
