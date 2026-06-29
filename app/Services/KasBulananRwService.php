<?php

namespace App\Services;

use App\Enums\KasTipe;
use App\Enums\SetoranStatusValidasi;
use App\Enums\SlipGajiStatus;
use App\Models\Kasbon;
use App\Models\KasBulananRW;
use App\Models\KasRW;
use App\Models\Petugas;
use App\Models\SetoranRW;
use App\Models\SlipGaji;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class KasBulananRwService
{
    /**
     * @return array{
     *     saldo_awal: float,
     *     total_pendapatan_kas_harian: float,
     *     total_pemasukan_setoran_rt: float,
     *     total_pengeluaran_kas_harian: float,
     *     total_pengeluaran_gaji_petugas: float,
     *     total_kasbon_petugas: float,
     *     total_pendapatan: float,
     *     total_pengeluaran: float,
     *     total_pendapatan_bersih: float,
     *     saldo_akhir: float,
     *     gaji_start_date: string,
     *     gaji_end_date: string
     * }
     */
    public static function calculateTotals(
        int $rwId,
        string $periode,
        float|int|string|null $saldoAwal = 0,
    ): array {
        $saldoAwal = (float) ($saldoAwal ?? 0);

        $totalPendapatanKasHarian = (float) KasRW::where('id_rw', $rwId)
            ->where('tipe', KasTipe::MASUK->value)
            ->where('tanggal', 'like', "{$periode}-%")
            ->sum('jumlah');

        $totalPemasukanSetoranRT = (float) SetoranRW::where('id_rw', $rwId)
            ->where('periode', $periode)
            ->where('status_validasi', SetoranStatusValidasi::VALID->value)
            ->sum('jumlah_setor');

        [$gajiStartDate, $gajiEndDate] = static::getPayrollDateRangeForPeriod(
            $periode,
        );

        $totalPengeluaranKasHarian = (float) KasRW::where('id_rw', $rwId)
            ->where('tipe', KasTipe::KELUAR->value)
            ->where('tanggal', 'like', "{$periode}-%")
            ->sum('jumlah');

        $totalPengeluaranGajiPetugas = (float) SlipGaji::join(
            'petugas',
            'slip_gajis.id_petugas',
            '=',
            'petugas.id',
        )
            ->where('petugas.id_rw', $rwId)
            ->whereDate('slip_gajis.tanggal', '>=', $gajiStartDate)
            ->whereDate('slip_gajis.tanggal', '<=', $gajiEndDate)
            ->where(
                'slip_gajis.status',
                SlipGajiStatus::TELAH_DIBAYAR->value,
            )
            ->sum('slip_gajis.total');

        $totalKasbonPetugas = (float) Kasbon::join(
            'petugas',
            'kasbons.id_petugas',
            '=',
            'petugas.id',
        )
            ->where('petugas.id_rw', $rwId)
            ->whereDate('kasbons.tanggal', '>=', $gajiStartDate)
            ->whereDate('kasbons.tanggal', '<=', $gajiEndDate)
            ->sum('kasbons.jumlah');

        $totalPendapatan =
            $totalPendapatanKasHarian + $totalPemasukanSetoranRT;
        $totalPengeluaran =
            $totalPengeluaranKasHarian +
            $totalPengeluaranGajiPetugas +
            $totalKasbonPetugas;
        $totalPendapatanBersih = $totalPendapatan - $totalPengeluaran;
        $saldoAkhir = $saldoAwal + $totalPendapatanBersih;

        return [
            'saldo_awal' => $saldoAwal,
            'total_pendapatan_kas_harian' => $totalPendapatanKasHarian,
            'total_pemasukan_setoran_rt' => $totalPemasukanSetoranRT,
            'total_pengeluaran_kas_harian' => $totalPengeluaranKasHarian,
            'total_pengeluaran_gaji_petugas' => $totalPengeluaranGajiPetugas,
            'total_kasbon_petugas' => $totalKasbonPetugas,
            'total_pendapatan' => $totalPendapatan,
            'total_pengeluaran' => $totalPengeluaran,
            'total_pendapatan_bersih' => $totalPendapatanBersih,
            'saldo_akhir' => $saldoAkhir,
            'gaji_start_date' => $gajiStartDate,
            'gaji_end_date' => $gajiEndDate,
        ];
    }

    /**
     * @return array{0: string, 1: string}
     */
    public static function getPayrollDateRangeForPeriod(string $periode): array
    {
        $periodeCarbon = Carbon::createFromFormat('Y-m', $periode);

        return [
            $periodeCarbon->copy()->subMonth()->day(26)->toDateString(),
            $periodeCarbon->copy()->day(25)->toDateString(),
        ];
    }

    /**
     * @return array{0: string, 1: string}
     */
    public static function getPayrollDateRangeForDate(
        CarbonInterface|string $tanggal,
    ): array {
        $tanggalCarbon = $tanggal instanceof CarbonInterface
            ? Carbon::instance($tanggal)
            : Carbon::parse($tanggal);

        $startDate =
            $tanggalCarbon->day <= 25
                ? $tanggalCarbon->copy()->subMonth()->day(26)->toDateString()
                : $tanggalCarbon->copy()->day(26)->toDateString();

        $endDate =
            $tanggalCarbon->day <= 25
                ? $tanggalCarbon->copy()->day(25)->toDateString()
                : $tanggalCarbon->copy()->addMonth()->day(25)->toDateString();

        return [$startDate, $endDate];
    }

    public static function getPayrollPeriodForDate(
        CarbonInterface|string $tanggal,
    ): string {
        $tanggalCarbon = $tanggal instanceof CarbonInterface
            ? Carbon::instance($tanggal)
            : Carbon::parse($tanggal);

        return $tanggalCarbon->day <= 25
            ? $tanggalCarbon->format('Y-m')
            : $tanggalCarbon->copy()->addMonth()->format('Y-m');
    }

    /**
     * Recalculate KasBulananRW totals for a specific RW and period.
     *
     * Mirrors the manual "recalculate" action in KasBulananRWSTable.
     * Uses saveQuietly() to prevent re-triggering observer chains.
     *
     * Formula:
     *   Pendapatan  = kas harian masuk + setoran RT (valid)
     *   Pengeluaran = kas harian keluar
     *                 + slip gaji petugas dibayar (tgl 26 bulan lalu s/d tgl 25 bulan ini)
     *                 + kasbon petugas   (rentang yang sama)
     *
     * saldo_awal is automatically inherited from the previous month's saldo_akhir
     * if a previous-month record exists for the same RW.
     *
     * @param  int  $rwId  The RW's primary key
     * @param  string  $periode  Period in YYYY-MM format
     */
    public static function recalculate(int $rwId, string $periode): void
    {
        $record = static::ensureRecord($rwId, $periode);

        // ── Inherit saldo_awal from previous month's saldo_akhir ─────────────
        $prevPeriode = Carbon::createFromFormat('Y-m', $periode)
            ->subMonth()
            ->format('Y-m');

        $prevRecord = KasBulananRW::where('id_rw', $rwId)
            ->where('periode', $prevPeriode)
            ->first();

        if ($prevRecord) {
            $record->saldo_awal = $prevRecord->saldo_akhir;
        }

        // ── Write back ───────────────────────────────────────────────────────
        $totals = static::calculateTotals(
            $rwId,
            $periode,
            $record->saldo_awal,
        );

        $record->total_pendapatan = $totals['total_pendapatan'];
        $record->total_pengeluaran = $totals['total_pengeluaran'];
        $record->total_pendapatan_bersih =
            $totals['total_pendapatan_bersih'];
        $record->saldo_akhir = $totals['saldo_akhir'];

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
     * @param  int  $rwId  The RW's primary key
     * @param  string  $fromPeriode  Starting period (YYYY-MM) — inclusive
     */
    public static function recalculateChain(
        int $rwId,
        string $fromPeriode,
    ): void {
        static::ensureRecord($rwId, $fromPeriode);

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
     * @param  int  $petugasId  The Petugas primary key
     * @param  string  $periode  Period in YYYY-MM format
     */
    public static function recalculateSlipGaji(
        int $petugasId,
        string $tanggal,
    ): void {
        $slipGaji = SlipGaji::where('id_petugas', $petugasId)
            ->where('tanggal', '>=', "{$tanggal}")
            ->orderBy('tanggal', 'asc')
            ->first();

        if (! $slipGaji) {
            // No slip gaji for this petugas and period — nothing to update.
            return;
        }

        $petugas = Petugas::find($petugasId);

        if (! $petugas) {
            return;
        }

        [$startDate, $endDate] = static::getPayrollDateRangeForDate($tanggal);

        $totalKasbon = Kasbon::where('id_petugas', $petugasId)
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->sum('jumlah');

        // Gaji cannot go negative.
        $slipGaji->total = max(
            0,
            (float) $petugas->gaji_pokok - (float) $totalKasbon,
        );

        // saveQuietly() prevents the SlipGajiObserver from firing and triggering
        // another round of KasBulananRW recalculation.
        $slipGaji->saveQuietly();
    }

    public static function ensureRecord(int $rwId, string $periode): KasBulananRW
    {
        return KasBulananRW::firstOrCreate(
            [
                'id_rw' => $rwId,
                'periode' => $periode,
            ],
            [
                'total_pendapatan' => 0,
                'total_pengeluaran' => 0,
                'total_pendapatan_bersih' => 0,
                'saldo_awal' => 0,
                'saldo_akhir' => 0,
            ],
        );
    }
}
