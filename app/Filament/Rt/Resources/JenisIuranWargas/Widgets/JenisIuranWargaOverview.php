<?php

namespace App\Filament\Rt\Resources\JenisIuranWargas\Widgets;

use App\Models\JenisIuranWarga;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class JenisIuranWargaOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = "full";

    protected function getStats(): array
    {
        $rt = auth()->user()?->rt;

        $query = JenisIuranWarga::query();
        if ($rt) {
            $query->where("id_rt", $rt->id);
        }

        $jumlahJenisIuranWarga = $query->count();
        $jumlahIuranPerBulan = $query->sum("jumlah");

        return [
            Stat::make("Jumlah Jenis Iuran Warga", $jumlahJenisIuranWarga)
                ->description("Total Iuran Aktif")
                ->descriptionIcon("heroicon-o-document-text")
                ->color("info"),
            Stat::make(
                "Total Iuran",
                "Rp " . number_format($jumlahIuranPerBulan, 0, ",", "."),
            )
                ->description("Nominal Iuran Perbulan")
                ->descriptionIcon("heroicon-o-banknotes")
                ->color("success"),
        ];
    }
}
