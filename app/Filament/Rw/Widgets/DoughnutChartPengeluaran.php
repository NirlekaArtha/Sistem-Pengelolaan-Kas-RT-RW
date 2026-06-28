<?php

namespace App\Filament\Rw\Widgets;

use App\Services\KasBulananRwService;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DoughnutChartPengeluaran extends ChartWidget
{
    protected ?string $heading = 'Komposisi Pengeluaran';

    protected ?string $description = 'Perbandingan gaji, kasbon, dan pengeluaran harian';

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
        $totals = KasBulananRwService::calculateTotals(
            $rw->id,
            $periode,
        );

        $totalGaji = (float) $totals['total_pengeluaran_gaji_petugas'];
        $totalKasbon = (float) $totals['total_kasbon_petugas'];
        $totalPengeluaranHarian = (float) $totals['total_pengeluaran_kas_harian'];

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
            'datasets' => [
                [
                    'data' => [
                        round($totalGaji, 2),
                        round($totalKasbon, 2),
                        round($totalPengeluaranHarian, 2),
                    ],
                    'backgroundColor' => [
                        'rgba(239, 68, 68, 0.85)', // Red - Gaji
                        'rgba(249, 115, 22, 0.85)', // Orange - Kasbon
                        'rgba(234, 179, 8, 0.85)', // Yellow - Pengeluaran Harian
                    ],
                    'borderColor' => [
                        'rgba(239, 68, 68, 1)',
                        'rgba(249, 115, 22, 1)',
                        'rgba(234, 179, 8, 1)',
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 8,
                ],
            ],
            'labels' => [
                "Gaji ({$pctGaji}%)",
                "Kasbon ({$pctKasbon}%)",
                "Pengeluaran Harian ({$pctHarian}%)",
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
