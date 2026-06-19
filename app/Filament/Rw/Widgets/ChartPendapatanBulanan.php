<?php

namespace App\Filament\Rw\Widgets;

use App\Models\KasBulananRW;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ChartPendapatanBulanan extends ChartWidget
{
    protected ?string $heading = 'Pendapatan & Laba Bersih Bulanan';

    protected ?string $description = '*dalam juta rupiah';

    protected ?string $maxHeight = '300px';

    public ?string $filter = null;

    public function mount(): void
    {
        $this->filter = (string) date('Y');
    }

    protected function getFilters(): ?array
    {
        $years = KasBulananRW::query()
            ->selectRaw('LEFT(periode, 4) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($years)) {
            $years = [date('Y')];
        }

        return array_combine($years, $years);
    }

    protected function getData(): array
    {
        $rw = auth()->user()?->rw;
        if (! $rw) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $year = $this->filter ?? date('Y');

        $records = KasBulananRW::query()
            ->select('periode', 'total_pendapatan', 'total_pendapatan_bersih')
            ->where('id_rw', $rw->id)
            ->whereBetween('periode', ["{$year}-01", "{$year}-12"])
            ->orderBy('periode', 'asc')
            ->get();

        $processed = $records->map(function ($record) {
            return [
                'label' => Carbon::parse($record->periode)->translatedFormat(
                    'F',
                ),
                'pendapatan' => (float) $record->total_pendapatan / 1_000_000,
                'laba' => (float) $record->total_pendapatan_bersih / 1_000_000,
            ];
        });

        $labels = $processed->pluck('label')->toArray();
        $pendapatanValues = $processed->pluck('pendapatan')->toArray();
        $labaValues = $processed->pluck('laba')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $pendapatanValues,
                    'borderColor' => '#10b981', // Emerald green
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Laba Bersih',
                    'data' => $labaValues,
                    'borderColor' => '#06b6d4', // Cyan
                    'backgroundColor' => 'rgba(6, 180, 212, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(
            <<<'JS'
            {
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return Math.round(value) + ' jt';
                            },
                            stepSize: 10,
                        },
                    },
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const jutaValue = context.parsed.y;
                                const rupiah = jutaValue * 1_000_000;
                                const formatted = new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0,
                                }).format(rupiah);
                                return context.dataset.label + ': ' + formatted;
                            },
                        },
                    },
                },
            }
            JS
            ,
        );
    }

    protected function getType(): string
    {
        return 'line';
    }
}
