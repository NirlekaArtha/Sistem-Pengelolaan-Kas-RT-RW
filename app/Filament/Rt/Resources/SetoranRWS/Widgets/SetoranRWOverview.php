<?php

namespace App\Filament\Rt\Resources\SetoranRWS\Widgets; // Sesuaikan namespace dengan struktur folder RT Anda

use App\Models\SetoranRW;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SetoranRWOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Mengambil data RT dari user yang sedang login
        $rt = auth()->user()?->rt;

        if (!$rt) {
            return [];
        }

        $currentPeriode = now()->format("Y-m");

        // 1. Total dana yang sudah disetor oleh RT ini dan statusnya VALID bulan ini
        $totalSetoranValid = SetoranRW::where("id_rt", $rt->id)
            ->where("periode", $currentPeriode)
            ->where("status_validasi", "valid")
            ->sum("jumlah_setor");

        // 2. Total dana yang masih PENDING (sudah dikirim RT tapi belum divalidasi RW) bulan ini
        $totalSetoranPending = SetoranRW::where("id_rt", $rt->id)
            ->where("periode", $currentPeriode)
            ->where("status_validasi", "pending")
            ->sum("jumlah_setor");

        // 3. Total seluruh riwayat setoran RT ini yang sudah VALID (akumulasi semua bulan)
        // Ini berguna agar RT tahu total kontribusi yang sudah masuk ke RW sejauh ini
        $totalSemuaSetoranValid = SetoranRW::where("id_rt", $rt->id)
            ->where("status_validasi", "valid")
            ->sum("jumlah_setor");

        return [
            Stat::make(
                "Setoran Berhasil (Bulan Ini)",
                "Rp " . number_format($totalSetoranValid, 0, ",", "."),
            )
                ->description("Telah divalidasi oleh RW")
                ->descriptionIcon("heroicon-m-check-circle")
                ->color("success"),

            Stat::make(
                "Total Setoran Pending",
                "Rp " . number_format($totalSetoranPending, 0, ",", "."),
            )
                ->description("Menunggu konfirmasi RW")
                ->descriptionIcon("heroicon-m-clock")
                ->color("warning"),

            Stat::make(
                "Total Kontribusi Masuk",
                "Rp " . number_format($totalSemuaSetoranValid, 0, ",", "."),
            )
                ->description("Akumulasi seluruh setoran valid")
                ->descriptionIcon("heroicon-m-banknotes")
                ->color("primary"),
        ];
    }
}
