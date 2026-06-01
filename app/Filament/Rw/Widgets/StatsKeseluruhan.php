<?php

namespace App\Filament\Rw\Widgets;

use App\Models\KasBulananRW;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsKeseluruhan extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = "full";
    protected int|null|array $columns = [
        'sm' => 2,
        'xl' => 4,
    ];

    protected function getStats(): array
    {
        $rw = auth()->user()?->rw;
        if (!$rw) {
            return [];
        }

        // Get the latest month that has records
        $latest = KasBulananRW::query()
            ->where("id_rw", $rw->id)
            ->orderBy("periode", "desc")
            ->first();

        if (!$latest) {
            return [
                Stat::make("Pendapatan", "Rp 0")
                    ->description("Belum ada data")
                    ->color("gray"),
                Stat::make("Laba Bersih", "Rp 0")
                    ->description("Belum ada data")
                    ->color("gray"),
                Stat::make("Pengeluaran", "Rp 0")
                    ->description("Belum ada data")
                    ->color("gray"),
            ];
        }

        // Parse latest periode
        $latestCarbon = Carbon::parse($latest->periode . "-01");
        $monthLabel = $latestCarbon->translatedFormat("F Y");

        // Fetch previous month
        $prevPeriod = $latestCarbon->copy()->subMonth()->format("Y-m");
        $prev = KasBulananRW::query()
            ->where("id_rw", $rw->id)
            ->where("periode", $prevPeriod)
            ->first();

        // 1. Pendapatan Stat
        $currentPendapatan = (float) $latest->total_pendapatan;
        $formattedPendapatan =
            "Rp " . number_format($currentPendapatan, 0, ",", ".");

        $pendapatanStat = Stat::make(
            "Pendapatan ({$monthLabel})",
            $formattedPendapatan,
        );

        if ($prev) {
            $prevPendapatan = (float) $prev->total_pendapatan;
            if ($prevPendapatan > 0) {
                $percentageChange =
                    (($currentPendapatan - $prevPendapatan) / $prevPendapatan) *
                    100;
                $formattedPercentage = number_format(
                    abs($percentageChange),
                    1,
                    ",",
                    ".",
                );

                if ($percentageChange > 0) {
                    $pendapatanStat
                        ->description(
                            "Naik {$formattedPercentage}% dari bulan lalu",
                        )
                        ->descriptionIcon("heroicon-m-arrow-trending-up")
                        ->color("success");
                } elseif ($percentageChange < 0) {
                    $pendapatanStat
                        ->description(
                            "Turun {$formattedPercentage}% dari bulan lalu",
                        )
                        ->descriptionIcon("heroicon-m-arrow-trending-down")
                        ->color("danger");
                } else {
                    $pendapatanStat
                        ->description("Stabil dibanding bulan lalu")
                        ->descriptionIcon("heroicon-m-minus")
                        ->color("gray");
                }
            } else {
                $pendapatanStat
                    ->description("Mulai mencatat pendapatan")
                    ->descriptionIcon("heroicon-m-arrow-trending-up")
                    ->color("success");
            }
        } else {
            $pendapatanStat
                ->description("Bulan pertama dengan data")
                ->descriptionIcon("heroicon-m-information-circle")
                ->color("info");
        }

        // 2. Laba Bersih Stat
        $currentLaba = (float) $latest->total_pendapatan_bersih;
        $formattedLaba = "Rp " . number_format($currentLaba, 0, ",", ".");

        $labaStat = Stat::make("Laba Bersih ({$monthLabel})", $formattedLaba);

        if ($currentLaba >= 0) {
            $labaStat
                ->description("Surplus Keuangan")
                ->descriptionIcon("heroicon-m-check-circle")
                ->color("success");
        } else {
            $labaStat
                ->description("Defisit Keuangan")
                ->descriptionIcon("heroicon-m-x-circle")
                ->color("danger");
        }

        // 3. Pengeluaran Stat
        $currentPengeluaran = (float) $latest->total_pengeluaran;
        $formattedPengeluaran =
            "Rp " . number_format($currentPengeluaran, 0, ",", ".");

        $pengeluaranStat = Stat::make(
            "Pengeluaran ({$monthLabel})",
            $formattedPengeluaran,
        )
            ->description("Total pengeluaran bulan ini")
            ->descriptionIcon("heroicon-m-arrow-trending-down")
            ->color("danger");
        
        $kasBulanIni = (float) $latest->saldo_akhir;
        $formattedKasBulanIni = "Rp " . number_format($kasBulanIni, 0, ",", ".");
            
        $kasBulanIniStat = Stat::make("Kas Bulan Ini ({$monthLabel})", $formattedKasBulanIni)->description("Saldo akhir kas bulan ini")->descriptionIcon("heroicon-m-currency-dollar")->color("info");

        return [$pendapatanStat, $labaStat, $pengeluaranStat, $kasBulanIniStat];
    }
}
