<?php

namespace App\Filament\Rt\Widgets;

use App\Models\KasRT;
use App\Models\IuranWarga;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Filament\Support\RawJs;

class DoughnutChartPendapatanRT extends ChartWidget
{
    protected ?string $heading = "Komposisi Pendapatan";

    protected ?string $description = "Perbandingan pendapatan harian dan iuran warga";

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
        $rt = auth()->user()?->rt;
        if (!$rt) {
            return ["datasets" => [], "labels" => []];
        }

        $periode = $this->filter ?? Carbon::now()->format("Y-m");
        $year = Carbon::parse($periode . "-01")->year;
        $month = Carbon::parse($periode . "-01")->month;

        // 1. Pendapatan Harian (KasRT tipe = masuk) in the selected month
        $totalPendapatanHarian = KasRT::where("id_rt", $rt->id)
            ->where("tipe", "masuk")
            ->whereYear("tanggal", $year)
            ->whereMonth("tanggal", $month)
            ->sum("jumlah");

        // 2. Pendapatan Iuran Warga in the selected month
        $totalIuranWarga = IuranWarga::join(
            "jenis_iuran_wargas",
            "iuran_wargas.id_jenis_iuran",
            "=",
            "jenis_iuran_wargas.id",
        )
            ->where("iuran_wargas.id_rt", $rt->id)
            ->whereYear("iuran_wargas.tanggal_bayar", $year)
            ->whereMonth("iuran_wargas.tanggal_bayar", $month)
            ->sum("jenis_iuran_wargas.jumlah");

        $totalPendapatanHarian = (float) $totalPendapatanHarian;
        $totalIuranWarga = (float) $totalIuranWarga;

        $grandTotal = $totalPendapatanHarian + $totalIuranWarga;

        $pctHarian =
            $grandTotal > 0
                ? round(($totalPendapatanHarian / $grandTotal) * 100, 1)
                : 0;
        $pctIuran =
            $grandTotal > 0
                ? round(($totalIuranWarga / $grandTotal) * 100, 1)
                : 0;

        return [
            "datasets" => [
                [
                    "data" => [
                        round($totalPendapatanHarian, 2),
                        round($totalIuranWarga, 2),
                    ],
                    "backgroundColor" => [
                        "rgba(16, 185, 129, 0.85)", // Emerald - Pendapatan Harian
                        "rgba(6, 182, 212, 0.85)", // Cyan - Iuran Warga
                    ],
                    "borderColor" => [
                        "rgba(16, 185, 129, 1)",
                        "rgba(6, 182, 212, 1)",
                    ],
                    "borderWidth" => 2,
                    "hoverOffset" => 8,
                ],
            ],
            "labels" => [
                "Pendapatan Harian ({$pctHarian}%)",
                "Iuran Warga ({$pctIuran}%)",
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
                                displayColors: false,
                                label: function(context) {
                                    let value = context.raw || 0;
                                    let formattedValue = new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 2
                                    }).format(value);
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
