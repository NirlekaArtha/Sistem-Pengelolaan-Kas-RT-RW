<?php

namespace App\Filament\Rw\Widgets;

use App\Enums\KasTipe;
use App\Models\KasRW;
use App\Models\SetoranRW;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DoughnutChartPendapatan extends ChartWidget
{
    protected ?string $heading = 'Komposisi Pendapatan';

    protected ?string $description = 'Perbandingan pendapatan harian dan setoran RW';

    protected ?string $maxHeight = '320px';

    public ?string $filter = null;

    public function mount(): void
    {
        $this->filter = Carbon::now()->format('Y-m');
    }

    protected function getFilters(): ?array
    {
        $filters = [];
        $now = Carbon::now();
        for ($i = 0; $i < 6; $i++) {
            $date = $now->copy()->subMonths($i);
            $key = $date->format('Y-m');
            $label = $date->translatedFormat('F Y');
            $filters[$key] = $label;
        }

        return $filters;
    }

    protected function getData(): array
    {
        $rw = auth()->user()?->rw;
        if (! $rw) {
            return ['datasets' => [], 'labels' => []];
        }

        $periode = $this->filter ?? Carbon::now()->format('Y-m');
        $year = Carbon::parse($periode.'-01')->year;
        $month = Carbon::parse($periode.'-01')->month;

        // 1. Pendapatan Harian (KasRW tipe = masuk) in the selected month
        $totalPendapatanHarian = KasRW::where('id_rw', $rw->id)
            ->where('tipe', KasTipe::MASUK->value)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->sum('jumlah');

        // 2. Setoran RW in the selected month (by periode field Y-m format)
        $totalSetoranRW = SetoranRW::where('id_rw', $rw->id)
            ->where('periode', $periode)
            ->sum('jumlah_setor');

        $totalPendapatanHarian = (float) $totalPendapatanHarian;
        $totalSetoranRW = (float) $totalSetoranRW;

        $grandTotal = $totalPendapatanHarian + $totalSetoranRW;

        $pctHarian =
            $grandTotal > 0
                ? round(($totalPendapatanHarian / $grandTotal) * 100, 1)
                : 0;
        $pctSetoran =
            $grandTotal > 0
                ? round(($totalSetoranRW / $grandTotal) * 100, 1)
                : 0;

        return [
            'datasets' => [
                [
                    'data' => [
                        round($totalPendapatanHarian, 2),
                        round($totalSetoranRW, 2),
                    ],
                    'backgroundColor' => [
                        'rgba(16, 185, 129, 0.85)', // Emerald - Pendapatan Harian
                        'rgba(6, 182, 212, 0.85)', // Cyan - Setoran RW
                    ],
                    'borderColor' => [
                        'rgba(16, 185, 129, 1)',
                        'rgba(6, 182, 212, 1)',
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 8,
                ],
            ],
            'labels' => [
                "Pendapatan Harian ({$pctHarian}%)",
                "Setoran RW ({$pctSetoran}%)",
            ],
        ];
    }

    protected function getOptions(): array|RawJs|null
    {
        return RawJs::make(
            <<<'JS'
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
        return 'doughnut';
    }
}
