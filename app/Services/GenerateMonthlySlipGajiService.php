<?php

namespace App\Services;

use App\Enums\SlipGajiStatus;
use App\Models\Petugas;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateMonthlySlipGajiService
{
    /**
     * @return array{created_unpaid: int, period: string, slip_date: string}
     */
    public static function run(?CarbonInterface $runDate = null): array
    {
        $runDate = $runDate
            ? Carbon::instance($runDate)->startOfDay()
            : now()->startOfDay();

        $periodDate = $runDate->copy()->addMonth();
        $period = $periodDate->format('Y-m');
        $slipDate = $periodDate->copy()->day(25)->toDateString();

        return DB::transaction(function () use ($period, $slipDate): array {
            $timestamp = now();

            $rowsToInsert = Petugas::query()
                ->whereDoesntHave(
                    'slipGajis',
                    fn ($query) => $query->whereDate('tanggal', $slipDate),
                )
                ->get(['id', 'gaji_pokok'])
                ->map(fn (Petugas $petugas): array => [
                    'id_petugas' => $petugas->id,
                    'total' => $petugas->gaji_pokok,
                    'tanggal' => $slipDate,
                    'status' => SlipGajiStatus::BELUM_DIBAYAR->value,
                    'file_path' => null,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ])
                ->all();

            $createdUnpaid = 0;

            foreach (array_chunk($rowsToInsert, 500) as $chunk) {
                DB::table('slip_gajis')->insert($chunk);
                $createdUnpaid += count($chunk);
            }

            return [
                'created_unpaid' => $createdUnpaid,
                'period' => $period,
                'slip_date' => $slipDate,
            ];
        });
    }
}
