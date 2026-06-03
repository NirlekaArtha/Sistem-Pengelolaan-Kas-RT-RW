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
     * Formula:
     *   Pendapatan  = kas harian masuk + setoran RT (valid)
     *   Pengeluaran = kas harian keluar
     *                 + slip gaji petugas (tgl 26 bulan lalu s/d tgl 25 bulan ini)
     *                 + kasbon petugas   (rentang yang sama)
     *
     * saldo_awal is automatically inherited from the previous month's saldo_akhir
     * if a previous-month record exists for the same RW.
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

        // ── Inherit saldo_awal from previous month's saldo_akhir ─────────────
        $prevPeriode = \Carbon\Carbon::createFromFormat('Y-m', $periode)
            ->subMonth()
            ->format('Y-m');

        $prevRecord = KasBulananRW::where('id_rw', $rwId)
            ->where('periode', $prevPeriode)
            ->first();

        if ($prevRecord) {
            $record->saldo_awal = $prevRecord->saldo_akhir;
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

        // ── Rentang tanggal penggajian: tgl 26 bulan lalu s/d tgl 25 bulan ini ─
        $periodeCarbon  = Carbon::createFromFormat('Y-m', $periode);
        $gajiStartDate  = $periodeCarbon->copy()->subMonth()->day(26)->toDateString(); // tgl 26 bulan lalu
        $gajiEndDate    = $periodeCarbon->copy()->day(25)->toDateString();             // tgl 25 bulan ini

        // ── Pengeluaran ──────────────────────────────────────────────────────
        $totalPengeluaranKasHarian = KasRW::where("id_rw", $rwId)
            ->where("tipe", "keluar")
            ->where("tanggal", "like", "{$periode}-%")
            ->sum("jumlah");

        // Gaji petugas: ambil dari slip_gajis yang tanggalnya dalam rentang penggajian
        $totalPengeluaranGajiPetugas = SlipGaji::join(
                'petugas',
                'slip_gajis.id_petugas',
                '=',
                'petugas.id',
            )
            ->where('petugas.id_rw', $rwId)
            ->whereBetween('slip_gajis.tanggal', [$gajiStartDate, $gajiEndDate])
            ->sum('slip_gajis.total');

        // Kasbon petugas: dalam rentang penggajian yang sama
        $totalKasbonPetugas = Kasbon::join(
                'petugas',
                'kasbons.id_petugas',
                '=',
                'petugas.id',
            )
            ->where('petugas.id_rw', $rwId)
            ->whereBetween('kasbons.tanggal', [$gajiStartDate, $gajiEndDate])
            ->sum('kasbons.jumlah');

        // ── Write back ───────────────────────────────────────────────────────
        $record->total_pendapatan =
            $totalPendapatanKasHarian + $totalPemasukanSetoranRT;
        $record->total_pengeluaran =
            $totalPengeluaranKasHarian + $totalPengeluaranGajiPetugas + $totalKasbonPetugas;
        $record->total_pendapatan_bersih =
            $record->total_pendapatan - $record->total_pengeluaran;
        $record->saldo_akhir =
            $record->saldo_awal + $record->total_pendapatan_bersih;

        // saveQuietly() prevents re-firing the KasBulananRW saved/updated events.
        $record->saveQuietly();
    }

    /**
     * Recalculate KasBulananRW for a given period and all subsequent periods
     * for the same RW, in chronological order.
     *
     * Because each month's saldo_awal is derived from the previous month's
     * saldo_akhir, recalculating from a given month forward ensures the entire
     * chain stays consistent.
     *
     * @param  int    $rwId        The RW's primary key
     * @param  string $fromPeriode Starting period (YYYY-MM) — inclusive
     */
    public static function recalculateChain(int $rwId, string $fromPeriode): void
    {
        $records = KasBulananRW::where('id_rw', $rwId)
            ->where('periode', '>=', $fromPeriode)
            ->orderBy('periode', 'asc')
            ->get();

        foreach ($records as $record) {
            static::recalculate($rwId, $record->periode);
        }
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