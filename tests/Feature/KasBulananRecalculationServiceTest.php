<?php

namespace Tests\Feature;

use App\Enums\IuranWargaStatus;
use App\Enums\KasJenis;
use App\Enums\KasTipe;
use App\Enums\SetoranStatusValidasi;
use App\Enums\SlipGajiStatus;
use App\Enums\UserRole;
use App\Models\IuranWarga;
use App\Models\JenisIuranWarga;
use App\Models\Kasbon;
use App\Models\KasBulananRT;
use App\Models\KasBulananRW;
use App\Models\KasRT;
use App\Models\KasRW;
use App\Models\Petugas;
use App\Models\RT;
use App\Models\RW;
use App\Models\SetoranRW;
use App\Models\SlipGaji;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasBulananRecalculationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_rt_recap_is_created_automatically_and_recalculated_from_transactions(): void
    {
        [$rw, $rt] = $this->makeRwAndRt();
        $warga = $this->makeWarga($rt);
        $jenisIuran = JenisIuranWarga::create([
            'id_rt' => $rt->id,
            'jenis_iuran' => 'Iuran Keamanan',
            'jumlah' => 35000,
        ]);

        KasBulananRT::create([
            'id_rt' => $rt->id,
            'periode' => '2026-05',
            'total_pendapatan' => 0,
            'total_pengeluaran' => 0,
            'saldo_awal' => 0,
            'saldo_akhir' => 100000,
            'total_pendapatan_bersih' => 100000,
        ]);

        KasRT::create([
            'id_rt' => $rt->id,
            'tipe' => KasTipe::MASUK,
            'jenis' => KasJenis::DONASI,
            'jumlah' => 500000,
            'sumber_tujuan' => 'Donatur',
            'tanggal' => '2026-06-03',
        ]);

        KasRT::create([
            'id_rt' => $rt->id,
            'tipe' => KasTipe::KELUAR,
            'jenis' => KasJenis::OPERASIONAL,
            'jumlah' => 125000,
            'sumber_tujuan' => 'Operasional',
            'tanggal' => '2026-06-04',
        ]);

        IuranWarga::create([
            'id_rt' => $rt->id,
            'id_warga' => $warga->id,
            'id_jenis_iuran' => $jenisIuran->id,
            'periode' => '2026-06',
            'tanggal_bayar' => '2026-06-10',
            'status' => IuranWargaStatus::DIBAYAR,
        ]);

        SetoranRW::create([
            'id_rt' => $rt->id,
            'id_rw' => $rw->id,
            'periode' => '2026-06',
            'tanggal_setor' => '2026-06-15',
            'jumlah_setor' => 200000,
            'status_validasi' => SetoranStatusValidasi::VALID,
        ]);

        $record = KasBulananRT::where('id_rt', $rt->id)
            ->where('periode', '2026-06')
            ->firstOrFail();

        $this->assertSame('100000.00', $record->saldo_awal);
        $this->assertSame('535000.00', $record->total_pendapatan);
        $this->assertSame('325000.00', $record->total_pengeluaran);
        $this->assertSame('210000.00', $record->total_pendapatan_bersih);
        $this->assertSame('310000.00', $record->saldo_akhir);
        $this->assertSame(
            1,
            KasBulananRT::where('id_rt', $rt->id)
                ->where('periode', '2026-06')
                ->count(),
        );
    }

    public function test_rw_recap_uses_consistent_payroll_range_and_is_recalculated_after_kasbon(): void
    {
        [$rw, $rt] = $this->makeRwAndRt();
        $petugas = Petugas::create([
            'id_rw' => $rw->id,
            'tugas' => 'satpam',
            'nama' => 'Petugas Satu',
            'alamat' => 'Jl. Petugas',
            'gaji_pokok' => 1000000,
        ]);

        KasBulananRW::create([
            'id_rw' => $rw->id,
            'periode' => '2026-05',
            'total_pendapatan' => 0,
            'total_pengeluaran' => 0,
            'total_pendapatan_bersih' => 0,
            'saldo_awal' => 0,
            'saldo_akhir' => 200000,
        ]);

        KasRW::create([
            'id_rw' => $rw->id,
            'tipe' => KasTipe::MASUK,
            'jenis' => KasJenis::DONASI,
            'jumlah' => 1000000,
            'sumber_tujuan' => 'Donatur RW',
            'tanggal' => '2026-06-03',
        ]);

        KasRW::create([
            'id_rw' => $rw->id,
            'tipe' => KasTipe::KELUAR,
            'jenis' => KasJenis::OPERASIONAL,
            'jumlah' => 100000,
            'sumber_tujuan' => 'Operasional RW',
            'tanggal' => '2026-06-04',
        ]);

        SetoranRW::create([
            'id_rt' => $rt->id,
            'id_rw' => $rw->id,
            'periode' => '2026-06',
            'tanggal_setor' => '2026-06-10',
            'jumlah_setor' => 300000,
            'status_validasi' => SetoranStatusValidasi::VALID,
        ]);

        $slipGaji = SlipGaji::create([
            'id_petugas' => $petugas->id,
            'total' => 1000000,
            'tanggal' => '2026-06-25',
            'status' => SlipGajiStatus::TELAH_DIBAYAR,
        ]);

        Kasbon::create([
            'id_petugas' => $petugas->id,
            'jumlah' => 50000,
            'tanggal' => '2026-06-24',
        ]);

        Kasbon::create([
            'id_petugas' => $petugas->id,
            'jumlah' => 70000,
            'tanggal' => '2026-06-26',
        ]);

        $this->assertSame(
            '950000.00',
            $slipGaji->fresh()->total,
        );

        $record = KasBulananRW::where('id_rw', $rw->id)
            ->where('periode', '2026-06')
            ->firstOrFail();

        $this->assertSame('200000.00', $record->saldo_awal);
        $this->assertSame('1300000.00', $record->total_pendapatan);
        $this->assertSame('1100000.00', $record->total_pengeluaran);
        $this->assertSame('200000.00', $record->total_pendapatan_bersih);
        $this->assertSame('400000.00', $record->saldo_akhir);
        $this->assertSame(
            1,
            KasBulananRW::where('id_rw', $rw->id)
                ->where('periode', '2026-06')
                ->count(),
        );
    }

    public function test_rw_recap_only_reduces_cash_for_paid_salary_slips(): void
    {
        [$rw, $rt] = $this->makeRwAndRt();
        $petugas = Petugas::create([
            'id_rw' => $rw->id,
            'tugas' => 'satpam',
            'nama' => 'Petugas Satu',
            'alamat' => 'Jl. Petugas',
            'gaji_pokok' => 1000000,
        ]);

        KasRW::create([
            'id_rw' => $rw->id,
            'tipe' => KasTipe::MASUK,
            'jenis' => KasJenis::DONASI,
            'jumlah' => 1000000,
            'sumber_tujuan' => 'Donatur RW',
            'tanggal' => '2026-06-03',
        ]);

        SetoranRW::create([
            'id_rt' => $rt->id,
            'id_rw' => $rw->id,
            'periode' => '2026-06',
            'tanggal_setor' => '2026-06-10',
            'jumlah_setor' => 300000,
            'status_validasi' => SetoranStatusValidasi::VALID,
        ]);

        $slipGaji = SlipGaji::create([
            'id_petugas' => $petugas->id,
            'total' => 1000000,
            'tanggal' => '2026-06-25',
            'status' => SlipGajiStatus::BELUM_DIBAYAR,
        ]);

        $record = KasBulananRW::where('id_rw', $rw->id)
            ->where('periode', '2026-06')
            ->firstOrFail();

        $this->assertSame('1300000.00', $record->total_pendapatan);
        $this->assertSame('0.00', $record->total_pengeluaran);
        $this->assertSame('1300000.00', $record->saldo_akhir);

        $slipGaji->update([
            'status' => SlipGajiStatus::TELAH_DIBAYAR,
        ]);

        $record->refresh();

        $this->assertSame('1000000.00', $record->total_pengeluaran);
        $this->assertSame('300000.00', $record->saldo_akhir);
    }

    /**
     * @return array{0: RW, 1: RT}
     */
    private function makeRwAndRt(): array
    {
        $rwUser = User::factory()->create([
            'role' => UserRole::RW,
        ]);

        $rw = RW::create([
            'id_user' => $rwUser->id,
            'nomor_rw' => fake()->unique()->numerify('###'),
            'nama' => 'RW Test',
            'alamat' => 'Jl. RW',
            'no_telepon' => '080000000001',
        ]);

        $rtUser = User::factory()->create([
            'role' => UserRole::RT,
        ]);

        $rt = RT::create([
            'id_rw' => $rw->id,
            'id_user' => $rtUser->id,
            'nomor_rt' => fake()->unique()->numerify('###'),
            'nama' => 'RT Test',
            'alamat' => 'Jl. RT',
            'no_telepon' => '080000000002',
        ]);

        return [$rw, $rt];
    }

    private function makeWarga(RT $rt): Warga
    {
        $wargaUser = User::factory()->create([
            'role' => UserRole::WARGA,
        ]);

        return Warga::create([
            'id_rt' => $rt->id,
            'id_user' => $wargaUser->id,
            'nama_kepala_keluarga' => 'Warga Test',
            'alamat' => 'Jl. Warga',
            'no_telepon' => '080000000003',
        ]);
    }
}
