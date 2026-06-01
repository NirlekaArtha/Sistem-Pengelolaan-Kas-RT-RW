<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Widgets;

use App\Models\RT;
use App\Models\SetoranRW;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SetoranRWOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $rw = auth()->user()?->rw;

        if (!$rw) {
            return [];
        }

        $currentPeriode = now()->format("Y-m");

        // 1. Jumlah setoran bulan ini (yang berstatus valid)
        $totalSetoranBulanIni = SetoranRW::where("id_rw", $rw->id)
            ->where("periode", $currentPeriode)
            ->where("status_validasi", "valid")
            ->sum("jumlah_setor");

        // 2. Jumlah RT yang menyetor (berstatus valid)
        $totalRt = RT::where("id_rw", $rw->id)->count();
        $rtMenyetor = SetoranRW::where("id_rw", $rw->id)
            ->where("periode", $currentPeriode)
            ->where("status_validasi", "valid")
            ->distinct("id_rt")
            ->count("id_rt");

        // 3. Banyaknya setoran bulan ini yang berstatus pending
        $pendingSetoranBulanIni = SetoranRW::where("id_rw", $rw->id)
            ->where("periode", $currentPeriode)
            ->where("status_validasi", "pending")
            ->count();

        return [
            Stat::make(
                "Jumlah Setoran Bulan Ini",
                "Rp " . number_format($totalSetoranBulanIni, 0, ",", "."),
            )
                ->description("Dari RT bulan ini")
                ->descriptionIcon("heroicon-m-banknotes")
                ->color("success"),
            Stat::make("Jumlah Setoran", "{$rtMenyetor} dari {$totalRt} RT")
                ->description("Telah terverifikasi")
                ->descriptionIcon("heroicon-m-user-group")
                ->color("info"),
            Stat::make("Jumlah Setoran Pending", $pendingSetoranBulanIni)
                ->description("Menunggu validasi")
                ->descriptionIcon("heroicon-m-clock")
                ->color("warning"),
        ];
    }
}
