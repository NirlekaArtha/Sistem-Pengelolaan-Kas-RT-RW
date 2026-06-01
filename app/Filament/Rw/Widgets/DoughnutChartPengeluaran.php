<?php

namespace App\Filament\Rw\Widgets;

use App\Models\Kasbon;
use App\Models\KasRW;
use App\Models\Petugas;
use App\Models\SlipGaji;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Filament\Support\RawJs;

class DoughnutChartPengeluaran extends ChartWidget
{
    protected ?string $heading = "Komposisi Pengeluaran";

    protected ?string $description = "Perbandingan gaji, kasbon, dan pengeluaran harian";

    protected ?string $maxHeight = "320px";

    public ?string $filter = null;

    public function mount(): void
    {
        $this->filter = Carbon::now()->format("Y-m");
    }

    protected function getFilters(): ?array
    {
        $filters = [];
        $now = Carbon::now();
        for ($i = 0; $i < 6; $i++) {
            $date = $now->copy()->subMonths($i);
            $key = $date->format("Y-m");
            $label = $date->translatedFormat("F Y");
            $filters[$key] = $label;
        }
        return $filters;
    }

    protected function getData(): array
    {
        $rw = auth()->user()?->rw;
        if (!$rw) {
            return ["datasets" => [], "labels" => []];
        }

        $periode = $this->filter ?? Carbon::now()->format("Y-m");
        $year = Carbon::parse($periode . "-01")->year;
        $month = Carbon::parse($periode . "-01")->month;

        // Get all petugas IDs for this RW
        $petugasIds = Petugas::where("id_rw", $rw->id)->pluck("id");

        // 1. Total Gaji (SlipGaji) in the selected month
        $totalGaji = SlipGaji::whereIn("id_petugas", $petugasIds)
            ->whereYear("tanggal", $year)
            ->whereMonth("tanggal", $month)
            ->sum("total");

        // 2. Total Kasbon in the selected month
        $totalKasbon = Kasbon::whereIn("id_petugas", $petugasIds)
            ->whereYear("tanggal", $year)
            ->whereMonth("tanggal", $month)
            ->sum("jumlah");

        // 3. Total Pengeluaran Harian (KasRW tipe = keluar) in the selected month
        $totalPengeluaranHarian = KasRW::where("id_rw", $rw->id)
            ->where("tipe", "keluar")
            ->whereYear("tanggal", $year)
            ->whereMonth("tanggal", $month)
            ->sum("jumlah");

        $totalGaji = (float) $totalGaji;
        $totalKasbon = (float) $totalKasbon;
        $totalPengeluaranHarian = (float) $totalPengeluaranHarian;

        $grandTotal = $totalGaji + $totalKasbon + $totalPengeluaranHarian;

        $pctGaji =
            $grandTotal > 0 ? round(($totalGaji / $grandTotal) * 100, 1) : 0;
        $pctKasbon =
            $grandTotal > 0 ? round(($totalKasbon / $grandTotal) * 100, 1) : 0;
        $pctHarian =
            $grandTotal > 0
                ? round(($totalPengeluaranHarian / $grandTotal) * 100, 1)
                : 0;

        return [
            "datasets" => [
                [
                    "data" => [
                        round($totalGaji, 2),
                        round($totalKasbon, 2),
                        round($totalPengeluaranHarian, 2),
                    ],
                    "backgroundColor" => [
                        "rgba(239, 68, 68, 0.85)", // Red - Gaji
                        "rgba(249, 115, 22, 0.85)", // Orange - Kasbon
                        "rgba(234, 179, 8, 0.85)", // Yellow - Pengeluaran Harian
                    ],
                    "borderColor" => [
                        "rgba(239, 68, 68, 1)",
                        "rgba(249, 115, 22, 1)",
                        "rgba(234, 179, 8, 1)",
                    ],
                    "borderWidth" => 2,
                    "hoverOffset" => 8,
                ],
            ],
            "labels" => [
                "Gaji ({$pctGaji}%)",
                "Kasbon ({$pctKasbon}%)",
                "Pengeluaran Harian ({$pctHarian}%)",
            ],
        ];
    }

    protected function getOptions(): array|RawJs|null
    {
        return RawJs::make(
            <<<JS
                {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                // Menghilangkan kotak warna kecil (color box) di depan teks jika diinginkan (opsional)
                                displayColors: false,

                                label: function(context) {
                                    let value = context.raw || 0;

                                    // Format angka murni ke Rupiah
                                    let formattedValue = new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 2
                                    }).format(value);

                                    // Hanya mengembalikan nilai Rupiah saja murni
                                    return formattedValue;
                                }
                            }
                        }
                    }
                }
            JS
            ,
        );
    }

    protected function getType(): string
    {
        return "doughnut";
    }
}
