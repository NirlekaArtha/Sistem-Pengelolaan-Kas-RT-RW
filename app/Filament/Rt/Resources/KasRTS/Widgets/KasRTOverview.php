<?php

namespace App\Filament\Rt\Resources\KasRTS\Widgets;

use App\Models\KasRT;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KasRTOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $rt = auth()->user()?->rt;
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $query = KasRT::query();
        if ($rt) {
            $query->where("id_rt", $rt->id);
        }

        $jumlahMasukTahunan = $query
            ->clone()
            ->where("tipe", "masuk")
            ->whereYear("tanggal", $currentYear)
            ->count();

        $totalMasukTahunan = $query
            ->clone()
            ->where("tipe", "masuk")
            ->whereYear("tanggal", $currentYear)
            ->sum("jumlah");

        $jumlahKeluarTahunan = $query
            ->clone()
            ->where("tipe", "keluar")
            ->whereYear("tanggal", $currentYear)
            ->count();

        $totalKeluarTahunan = $query
            ->clone()
            ->where("tipe", "keluar")
            ->whereYear("tanggal", $currentYear)
            ->sum("jumlah");

        $jumlahMasukBulanan = $query
            ->clone()
            ->where("tipe", "masuk")
            ->whereYear("tanggal", $currentYear)
            ->whereMonth("tanggal", $currentMonth)
            ->count();

        $totalMasukBulanan = $query
            ->clone()
            ->where("tipe", "masuk")
            ->whereYear("tanggal", $currentYear)
            ->whereMonth("tanggal", $currentMonth)
            ->sum("jumlah");

        $jumlahKeluarBulanan = $query
            ->clone()
            ->where("tipe", "keluar")
            ->whereYear("tanggal", $currentYear)
            ->whereMonth("tanggal", $currentMonth)
            ->count();

        $totalKeluarBulanan = $query
            ->clone()
            ->where("tipe", "keluar")
            ->whereYear("tanggal", $currentYear)
            ->whereMonth("tanggal", $currentMonth)
            ->sum("jumlah");

        return [
            Stat::make(
                "Total Masuk Tahun ini",
                "Rp " . number_format($totalMasukTahunan, 0, ",", "."),
            )
                ->description("Dari " . $jumlahMasukTahunan . " Kas Masuk")
                ->descriptionIcon("heroicon-m-arrow-trending-up")
                ->color("success"),
            Stat::make(
                "Total Keluar Tahun ini",
                "Rp " . number_format($totalKeluarTahunan, 0, ",", "."),
            )
                ->description("Dari " . $jumlahKeluarTahunan . " Kas keluar")
                ->descriptionIcon("heroicon-m-arrow-trending-down")
                ->color("danger"),
            Stat::make(
                "Total Masuk Bulan ini",
                "Rp " . number_format($totalMasukBulanan, 0, ",", "."),
            )
                ->description("Dari " . $jumlahMasukBulanan . " Kas Masuk")
                ->descriptionIcon("heroicon-m-arrow-trending-up")
                ->color("success"),
            Stat::make(
                "Total Keluar Tahun ini",
                "Rp " . number_format($totalKeluarBulanan, 0, ",", "."),
            )
                ->description("Dari " . $jumlahKeluarBulanan . " Kas keluar")
                ->descriptionIcon("heroicon-m-arrow-trending-down")
                ->color("danger"),
        ];
    }
}
