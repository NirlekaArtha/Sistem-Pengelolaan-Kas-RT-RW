<?php

namespace App\Filament\Rw\Widgets;

use App\Models\KasBulananRW;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ChartPendapatanBulanan extends ChartWidget
{
    protected ?string $heading = "Pendapatan & Laba Bersih Bulanan";

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
            ->select("periode", "total_pendapatan", "total_pendapatan_bersih")
            ->where("id_rw", $rw->id)
            ->whereBetween("periode", ["{$year}-01", "{$year}-12"])
            ->orderBy("periode", "asc")
            ->get();

        $processed = $records->map(function ($record) {
            return [
                "label" => Carbon::parse($record->periode)->translatedFormat(
                    "M",
                ),
                "pendapatan" => (float) $record->total_pendapatan / 1_000_000,
                "laba" => (float) $record->total_pendapatan_bersih / 1_000_000,
            ];
        });

        $labels = $processed->pluck("label")->toArray();
        $pendapatanValues = $processed->pluck("pendapatan")->toArray();
        $labaValues = $processed->pluck("laba")->toArray();

        return [
            "datasets" => [
                [
                    "label" => "Pendapatan",
                    "data" => $pendapatanValues,
                    "borderColor" => "#10b981", // Emerald green
                    "backgroundColor" => "rgba(16, 185, 129, 0.1)",
                    "fill" => true,
                    "tension" => 0.4,
                ],
                [
                    "label" => "Laba Bersih",
                    "data" => $labaValues,
                    "borderColor" => "#06b6d4", // Cyan
                    "backgroundColor" => "rgba(6, 180, 212, 0.1)",
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
