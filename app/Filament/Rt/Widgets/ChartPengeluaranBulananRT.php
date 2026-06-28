<?php

namespace App\Filament\Rt\Widgets;

use App\Enums\KasTipe;
use App\Enums\SetoranStatusValidasi;
use App\Models\KasBulananRT;
use App\Models\KasRT;
use App\Models\SetoranRW;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ChartPengeluaranBulananRT extends ChartWidget
{
    protected ?string $heading = 'Pengeluaran Bulanan';

    protected ?string $description = '*dalam juta rupiah';

    protected ?string $maxHeight = '300px';

    public ?string $filter = null;

    public function mount(): void
    {
        $this->filter = (string) date('Y');
    }

    protected function getFilters(): ?array
    {
        $rt = auth()->user()?->rt;
        if (! $rt) {
            return [date('Y') => date('Y')];
        }

        $years = KasBulananRT::query()
            ->where('id_rt', $rt->id)
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
        $rt = auth()->user()?->rt;
        if (! $rt) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $year = $this->filter ?? date('Y');

        $records = KasBulananRT::query()
            ->where('id_rt', $rt->id)
            ->whereBetween('periode', ["{$year}-01", "{$year}-12"])
            ->orderBy('periode', 'asc')
            ->get();

        $processed = $records->map(function ($record) use ($rt) {
            // 1. Ambil total Setoran RW untuk periode/bulan ini
            $totalSetoranRW = SetoranRW::where('id_rt', $rt->id)
                ->where('periode', $record->periode)
                ->where('status_validasi', SetoranStatusValidasi::VALID->value)
                ->sum('jumlah_setor');

            $totalPengeluaranHarian = KasRT::where('id_rt', $rt->id)
                ->where('tipe', KasTipe::KELUAR->value)
                ->whereLike('tanggal', "{$record->periode}-%")
                ->sum('jumlah');

            $totalPengeluaranGabungan =
                $totalSetoranRW + $totalPengeluaranHarian;

            return [
                'label' => Carbon::parse($record->periode)->translatedFormat(
                    'F',
                ),
                'pengeluaran' => (float) $totalPengeluaranGabungan / 1_000_000,
            ];
        });

        $labels = $processed->pluck('label')->toArray();
        $pengeluaranValues = $processed->pluck('pengeluaran')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Pengeluaran',
                    'data' => $pengeluaranValues,
                    'borderColor' => '#f43f5e',
                    'backgroundColor' => 'rgba(244, 63, 94, 0.1)',
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
