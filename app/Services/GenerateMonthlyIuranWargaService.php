<?php

namespace App\Services;

use App\Enums\IuranWargaStatus;
use App\Models\IuranWarga;
use App\Models\RT;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyIuranWargaService
{
    /**
     * @return array{marked_late: int, created_unpaid: int, period: string, previous_period: string}
     */
    public static function run(?CarbonInterface $runDate = null): array
    {
        $runDate = $runDate
            ? Carbon::instance($runDate)->startOfDay()
            : now()->startOfDay();

        $currentPeriod = $runDate->format('Y-m');
        $previousPeriod = $runDate->copy()->subMonth()->format('Y-m');

        return DB::transaction(function () use (
            $currentPeriod,
            $previousPeriod,
        ): array {
            $markedLate = IuranWarga::query()
                ->where('periode', $previousPeriod)
                ->where('status', IuranWargaStatus::BELUM_BAYAR)
                ->update(['status' => IuranWargaStatus::TELAT]);

            $rowsToInsert = [];
            $timestamp = now();

            RT::query()
                ->with([
                    'wargas:id,id_rt',
                    'jenisIuranWargas:id,id_rt',
                ])
                ->get(['id'])
                ->each(function (RT $rt) use (
                    $currentPeriod,
                    $timestamp,
                    &$rowsToInsert,
                ): void {
                    if ($rt->wargas->isEmpty() || $rt->jenisIuranWargas->isEmpty()) {
                        return;
                    }

                    foreach ($rt->wargas as $warga) {
                        foreach ($rt->jenisIuranWargas as $jenisIuran) {
                            $rowsToInsert[] = [
                                'id_rt' => $rt->id,
                                'id_warga' => $warga->id,
                                'id_jenis_iuran' => $jenisIuran->id,
                                'periode' => $currentPeriod,
                                'tanggal_bayar' => null,
                                'status' => IuranWargaStatus::BELUM_BAYAR->value,
                                'created_at' => $timestamp,
                                'updated_at' => $timestamp,
                            ];
                        }
                    }
                });

            $createdUnpaid = 0;

            foreach (array_chunk($rowsToInsert, 500) as $chunk) {
                $createdUnpaid += DB::table('iuran_wargas')->insertOrIgnore($chunk);
            }

            return [
                'marked_late' => $markedLate,
                'created_unpaid' => $createdUnpaid,
                'period' => $currentPeriod,
                'previous_period' => $previousPeriod,
            ];
        });
    }
}
