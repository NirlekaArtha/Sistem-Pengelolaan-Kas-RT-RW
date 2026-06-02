<?php

namespace App\Filament\Rt\Widgets;

use App\Models\KasRW;
use App\Models\SetoranRW;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Filament\Support\RawJs;

class DoughnutChartPengeluaranRT extends ChartWidget
{
    protected ?string $heading = "Komposisi Pengeluaran";

    protected ?string $description = "Perbandingan setoran RW dan pengeluaran RT";

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

        // Total Setoran RW in the selected month
        $totalSetoranRW = SetoranRW::where("id_rt", $rt->id)
            ->where("periode", $periode)
            ->where("status_validasi", "valid")
            ->sum("jumlah_setor");

        // Total Pengeluaran RT in the selected month
        $pengeluaranHarian = KasRW::where("tipe", "keluar")
            ->whereLike("tanggal", "{$periode}-%")
            ->sum("jumlah");

        // Hitung total gabungan untuk mencari persentase masing-masing
        $totalKeseluruhan = $totalSetoranRW + $pengeluaranHarian;

        // Hitung persentase secara proporsional
        $pctSetoran =
            $totalKeseluruhan > 0
                ? round(($totalSetoranRW / $totalKeseluruhan) * 100, 1)
                : 0;
        $pctPengeluaran =
            $totalKeseluruhan > 0
                ? round(($pengeluaranHarian / $totalKeseluruhan) * 100, 1)
                : 0;

        return [
            "datasets" => [
                [
                    "data" => [
                        round($totalSetoranRW, 2),
                        round($pengeluaranHarian, 2), // MODIFIKASI: Masuk ke elemen data kedua
                    ],
                    "backgroundColor" => [
                        "rgba(239, 68, 68, 0.85)", // Merah - Setoran RW
                        "rgba(245, 158, 11, 0.85)", // Amber/Kuning - Pengeluaran RT
                    ],
                    "borderColor" => [
                        "rgba(239, 68, 68, 1)",
                        "rgba(245, 158, 11, 1)",
                    ],
                    "borderWidth" => 2,
                    "hoverOffset" => 8,
                ],
            ],
            "labels" => [
                "Setoran RW ({$pctSetoran}%)",
                "Pengeluaran Harian ({$pctPengeluaran}%)", // MODIFIKASI: Tambah label pengeluaran
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
                                    return context.label.split(' (')[0] + ': ' + formattedValue;
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
