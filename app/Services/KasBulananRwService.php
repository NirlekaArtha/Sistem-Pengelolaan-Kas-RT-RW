<?php

namespace App\Services;

use App\Models\Kasbon;
use App\Models\KasBulananRW;
use App\Models\KasRW;
use App\Models\Petugas;
use App\Models\SetoranRW;
use App\Models\SlipGaji;
use Illuminate\Support\Carbon;

class KasBulananRwService
{
    /**
     * Recalculate KasBulananRW totals for a specific RW and period.
     *
     * Mirrors the manual "recalculate" action in KasBulananRWSTable.
     * Uses saveQuietly() to prevent re-triggering observer chains.
     *
     * @param  int    $rwId    The RW's primary key
     * @param  string $periode Period in YYYY-MM format
     */
    public static function recalculate(int $rwId, string $periode): void
    {
        $record = KasBulananRW::where("id_rw", $rwId)
            ->where("periode", $periode)
            ->first();

        if (!$record) {
            // No kas bulanan record exists for this period — nothing to update.
            return;
        }

        // ── Pendapatan ───────────────────────────────────────────────────────
        $totalPendapatanKasHarian = KasRW::where("id_rw", $rwId)
            ->where("tipe", "masuk")
            ->where("tanggal", "like", "{$periode}-%")
            ->sum("jumlah");

        $totalPemasukanSetoranRT = SetoranRW::where("id_rw", $rwId)
            ->where("periode", $periode)
            ->where("status_validasi", "valid")
            ->sum("jumlah_setor");

        // ── Pengeluaran ──────────────────────────────────────────────────────
        $totalPengeluaranKasHarian = KasRW::where("id_rw", $rwId)
            ->where("tipe", "keluar")
            ->where("tanggal", "like", "{$periode}-%")
            ->sum("jumlah");

        $totalPengeluaranGajiPetugas = Petugas::all()
            ->where("id_rw", $rwId)
            ->sum("gaji_pokok");

        // ── Write back ───────────────────────────────────────────────────────
        $record->total_pendapatan =
            $totalPendapatanKasHarian + $totalPemasukanSetoranRT;
        $record->total_pengeluaran =
            $totalPengeluaranKasHarian + $totalPengeluaranGajiPetugas;
        $record->total_pendapatan_bersih =
            $record->total_pendapatan - $record->total_pengeluaran;
        $record->saldo_akhir =
            $record->saldo_awal + $record->total_pendapatan_bersih;

        // saveQuietly() prevents re-firing the KasBulananRW saved/updated events.
        $record->saveQuietly();
    }

    /**
     * Recalculate a petugas's SlipGaji total for a specific period.
     *
     * Formula: total = gaji_pokok - Σ kasbon (same period)
     * Uses saveQuietly() to avoid triggering SlipGajiObserver and creating
     * a cascade loop with recalculate().
     *
     * @param  int    $petugasId The Petugas primary key
     * @param  string $periode   Period in YYYY-MM format
     */
    public static function recalculateSlipGaji(
        int $petugasId,
        string $tanggal,
    ): void {
        $slipGaji = SlipGaji::where("id_petugas", $petugasId)
            ->where("tanggal", ">=", "{$tanggal}")
            ->orderBy("tanggal", "asc")
            ->first();

        if (!$slipGaji) {
            // No slip gaji for this petugas and period — nothing to update.
            return;
        }

        $petugas = Petugas::find($petugasId);

        if (!$petugas) {
            return;
        }

        $startDate =
            $tanggal->day <= 25
                ? $tanggal->copy()->subMonth()->day(26)->toDateString()
                : $tanggal->copy()->day(26)->toDateString();

        $endDate =
            $tanggal->day <= 25
                ? $tanggal->copy()->day(25)->toDateString()
                : $tanggal->copy()->addMonth()->day(25)->toDateString();

        $totalKasbon = Kasbon::where("id_petugas", $petugasId)
            ->whereBetween("tanggal", [$startDate, $endDate])
            ->sum("jumlah");

        // Gaji cannot go negative.
        $slipGaji->total = max(
            0,
            (float) $petugas->gaji_pokok - (float) $totalKasbon,
        );

        // saveQuietly() prevents the SlipGajiObserver from firing and triggering
        // another round of KasBulananRW recalculation.
        $slipGaji->saveQuietly();
    }
}
