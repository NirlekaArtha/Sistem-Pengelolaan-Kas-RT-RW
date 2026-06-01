<?php

namespace App\Filament\Rw\Widgets;

use App\Models\KasBulananRW;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ChartPengeluaranBulanan extends ChartWidget
{
    protected ?string $heading = "Pengeluaran Bulanan";

    protected ?string $description = "*dalam juta rupiah";

    protected ?string $maxHeight = "300px";

    public ?string $filter = null;

    public function mount(): void
    {
        $this->filter = (string) date("Y");
    }

    protected function getFilters(): ?array
    {
        $currentYear = (int) date("Y");
        return [
            (string) $currentYear => (string) $currentYear,
            (string) ($currentYear - 1) => (string) ($currentYear - 1),
            (string) ($currentYear - 2) => (string) ($currentYear - 2),
        ];
    }

    protected function getData(): array
    {
        $rw = auth()->user()?->rw;
        if (!$rw) {
            return [
                "datasets" => [],
                "labels" => [],
            ];
        }

        $year = $this->filter ?? date("Y");

        $records = KasBulananRW::query()
            ->select("periode", "total_pengeluaran")
            ->where("id_rw", $rw->id)
            ->whereBetween("periode", ["{$year}-01", "{$year}-12"])
            ->orderBy("periode", "asc")
            ->get();

        $processed = $records->map(function ($record) {
            return [
                "label" => Carbon::parse($record->periode)->translatedFormat(
                    "M",
                ),
                "pengeluaran" => (float) $record->total_pengeluaran / 1_000_000,
            ];
        });

        $labels = $processed->pluck("label")->toArray();
        $pengeluaranValues = $processed->pluck("pengeluaran")->toArray();

        return [
            "datasets" => [
                [
                    "label" => "Pengeluaran",
                    "data" => $pengeluaranValues,
                    "borderColor" => "#f43f5e", // Rose red
                    "backgroundColor" => "rgba(244, 63, 94, 0.1)",
                    "fill" => true,
                    "tension" => 0.4,
                ],
            ],
            "labels" => $labels,
        ];
    }

    protected function getType(): string
    {
        return "line";
    }
}
