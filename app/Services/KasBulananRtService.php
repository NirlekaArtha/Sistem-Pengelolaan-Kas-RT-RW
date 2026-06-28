<?php

namespace App\Services;

use App\Enums\IuranWargaStatus;
use App\Enums\KasTipe;
use App\Enums\SetoranStatusValidasi;
use App\Models\IuranWarga;
use App\Models\KasBulananRT;
use App\Models\KasRT;
use App\Models\SetoranRW;
use Carbon\Carbon;

class KasBulananRtService
{
    /**
     * @return array{
     *     saldo_awal: float,
     *     total_pendapatan_kas_harian: float,
     *     total_pendapatan_iuran_warga: float,
     *     total_pengeluaran_kas_harian: float,
     *     total_pengeluaran_setoran_rw: float,
     *     total_pendapatan: float,
     *     total_pengeluaran: float,
     *     total_pendapatan_bersih: float,
     *     saldo_akhir: float
     * }
     */
    public static function calculateTotals(
        int $rtId,
        string $periode,
        float|int|string|null $saldoAwal = 0,
    ): array {
        $saldoAwal = (float) ($saldoAwal ?? 0);

        $totalPendapatanKasHarian = (float) KasRT::where('id_rt', $rtId)
            ->where('tipe', KasTipe::MASUK->value)
            ->where('tanggal', 'like', "{$periode}-%")
            ->sum('jumlah');

        $totalPendapatanIuranWarga = (float) IuranWarga::join(
            'jenis_iuran_wargas',
            'iuran_wargas.id_jenis_iuran',
            '=',
            'jenis_iuran_wargas.id',
        )
            ->where('jenis_iuran_wargas.id_rt', $rtId)
            ->where('status', IuranWargaStatus::DIBAYAR->value)
            ->where('iuran_wargas.tanggal_bayar', 'like', "{$periode}-%")
            ->sum('jenis_iuran_wargas.jumlah');

        $totalPengeluaranKasHarian = (float) KasRT::where('id_rt', $rtId)
            ->where('tipe', KasTipe::KELUAR->value)
            ->where('tanggal', 'like', "{$periode}-%")
            ->sum('jumlah');

        $totalPengeluaranSetoranRW = (float) SetoranRW::where('id_rt', $rtId)
            ->where('periode', $periode)
            ->where('status_validasi', SetoranStatusValidasi::VALID->value)
            ->sum('jumlah_setor');

        $totalPendapatan =
            $totalPendapatanKasHarian + $totalPendapatanIuranWarga;
        $totalPengeluaran =
            $totalPengeluaranKasHarian + $totalPengeluaranSetoranRW;
        $totalPendapatanBersih = $totalPendapatan - $totalPengeluaran;
        $saldoAkhir = $saldoAwal + $totalPendapatanBersih;

        return [
            'saldo_awal' => $saldoAwal,
            'total_pendapatan_kas_harian' => $totalPendapatanKasHarian,
            'total_pendapatan_iuran_warga' => $totalPendapatanIuranWarga,
            'total_pengeluaran_kas_harian' => $totalPengeluaranKasHarian,
            'total_pengeluaran_setoran_rw' => $totalPengeluaranSetoranRW,
            'total_pendapatan' => $totalPendapatan,
            'total_pengeluaran' => $totalPengeluaran,
            'total_pendapatan_bersih' => $totalPendapatanBersih,
            'saldo_akhir' => $saldoAkhir,
        ];
    }

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
     * @param  int  $rtId  The RT's primary key
     * @param  string  $periode  Period in YYYY-MM format
     */
    public static function recalculate(int $rtId, string $periode): void
    {
        $record = static::ensureRecord($rtId, $periode);

        // ── Inherit saldo_awal from previous month's saldo_akhir ─────────────
        $prevPeriode = Carbon::createFromFormat('Y-m', $periode)
            ->subMonth()
            ->format('Y-m');

        $prevRecord = KasBulananRT::where('id_rt', $rtId)
            ->where('periode', $prevPeriode)
            ->first();

        if ($prevRecord) {
            $record->saldo_awal = $prevRecord->saldo_akhir;
        }

        // ── Write back ────────────────────────────────────────────────────────
        $totals = static::calculateTotals(
            $rtId,
            $periode,
            $record->saldo_awal,
        );

        $record->total_pendapatan = $totals['total_pendapatan'];
        $record->total_pengeluaran = $totals['total_pengeluaran'];
        $record->total_pendapatan_bersih =
            $totals['total_pendapatan_bersih'];
        $record->saldo_akhir = $totals['saldo_akhir'];

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
     * @param  int  $rtId  The RT's primary key
     * @param  string  $fromPeriode  Starting period (YYYY-MM) — inclusive
     */
    public static function recalculateChain(int $rtId, string $fromPeriode): void
    {
        static::ensureRecord($rtId, $fromPeriode);

        $records = KasBulananRT::where('id_rt', $rtId)
            ->where('periode', '>=', $fromPeriode)
            ->orderBy('periode', 'asc')
            ->get();

        foreach ($records as $record) {
            static::recalculate($rtId, $record->periode);
        }
    }

    public static function ensureRecord(int $rtId, string $periode): KasBulananRT
    {
        return KasBulananRT::firstOrCreate(
            [
                'id_rt' => $rtId,
                'periode' => $periode,
            ],
            [
                'total_pendapatan' => 0,
                'total_pengeluaran' => 0,
                'saldo_awal' => 0,
                'saldo_akhir' => 0,
                'total_pendapatan_bersih' => 0,
            ],
        );
    }
}
