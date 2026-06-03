<?php

namespace App\Services;

use App\Models\IuranWarga;
use App\Models\KasBulananRT;
use App\Models\KasRT;
use App\Models\SetoranRW;

class KasBulananRtService
{
    /**
     * Recalculate KasBulananRT totals for a specific RT and period.
     *
     * Mirrors the manual "recalculate" action in KasBulananRTSTable.
     * Uses saveQuietly() to prevent re-triggering observer chains.
     *
     * Formula:
     *   Pendapatan  = kas harian masuk  + iuran warga (berdasarkan tanggal_bayar)
     *   Pengeluaran = setoran RW (valid) + kas harian keluar
     *
     * saldo_awal is automatically inherited from the previous month's saldo_akhir
     * if a previous-month record exists for the same RT.
     *
     * @param  int    $rtId    The RT's primary key
     * @param  string $periode Period in YYYY-MM format
     */
    public static function recalculate(int $rtId, string $periode): void
    {
        $record = KasBulananRT::where('id_rt', $rtId)
            ->where('periode', $periode)
            ->first();

        if (!$record) {
            // No kas bulanan record exists for this period — nothing to update.
            return;
        }

        // ── Inherit saldo_awal from previous month's saldo_akhir ─────────────
        $prevPeriode = \Carbon\Carbon::createFromFormat('Y-m', $periode)
            ->subMonth()
            ->format('Y-m');

        $prevRecord = KasBulananRT::where('id_rt', $rtId)
            ->where('periode', $prevPeriode)
            ->first();

        if ($prevRecord) {
            $record->saldo_awal = $prevRecord->saldo_akhir;
        }

        // ── Pendapatan ────────────────────────────────────────────────────────
        $totalPendapatanKasHarian = KasRT::where('id_rt', $rtId)
            ->where('tipe', 'masuk')
            ->where('tanggal', 'like', "{$periode}-%")
            ->sum('jumlah');

        $totalPendapatanIuranWarga = IuranWarga::join(
            'jenis_iuran_wargas',
            'iuran_wargas.id_jenis_iuran',
            '=',
            'jenis_iuran_wargas.id',
        )
            ->where('jenis_iuran_wargas.id_rt', $rtId)
            ->where('status', 'dibayar')
            ->where(
                'iuran_wargas.tanggal_bayar',
                'like',
                "{$periode}-%",
            )
            ->sum('jenis_iuran_wargas.jumlah');

        // ── Pengeluaran ───────────────────────────────────────────────────────
        $totalPengeluaranKasHarian = KasRT::where('id_rt', $rtId)
            ->where('tipe', 'keluar')
            ->where('tanggal', 'like', "{$periode}-%")
            ->sum('jumlah');

        $totalPengeluaranSetoranRW = SetoranRW::where('id_rt', $rtId)
            ->where('periode', $periode)
            ->where('status_validasi', 'valid')
            ->sum('jumlah_setor');

        // ── Write back ────────────────────────────────────────────────────────
        $record->total_pendapatan =
            $totalPendapatanKasHarian + $totalPendapatanIuranWarga;
        $record->total_pengeluaran =
            $totalPengeluaranKasHarian + $totalPengeluaranSetoranRW;
        $record->total_pendapatan_bersih =
            $record->total_pendapatan - $record->total_pengeluaran;
        $record->saldo_akhir =
            $record->saldo_awal + $record->total_pendapatan_bersih;

        // saveQuietly() prevents re-firing the KasBulananRT saved/updated events.
        $record->saveQuietly();
    }

    /**
     * Recalculate KasBulananRT for a given period and all subsequent periods
     * for the same RT, in chronological order.
     *
     * Because each month's saldo_awal is derived from the previous month's
     * saldo_akhir, recalculating from a given month forward ensures the entire
     * chain stays consistent.
     *
     * @param  int    $rtId        The RT's primary key
     * @param  string $fromPeriode Starting period (YYYY-MM) — inclusive
     */
    public static function recalculateChain(int $rtId, string $fromPeriode): void
    {
        $records = KasBulananRT::where('id_rt', $rtId)
            ->where('periode', '>=', $fromPeriode)
            ->orderBy('periode', 'asc')
            ->get();

        foreach ($records as $record) {
            static::recalculate($rtId, $record->periode);
        }
    }
}
