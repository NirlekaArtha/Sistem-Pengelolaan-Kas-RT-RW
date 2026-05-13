<?php

namespace Database\Seeders;

use App\Models\IuranWarga;
use App\Models\JenisIuranWarga;
use App\Models\KasBulananRT;
use App\Models\KasBulananRW;
use App\Models\KasKeluarRT;
use App\Models\KasKeluarRW;
use App\Models\KasMasukRT;
use App\Models\KasMasukRW;
use App\Models\Kasbon;
use App\Models\KwitansiIuranWarga;
use App\Models\KwitansiSetoranRW;
use App\Models\Petugas;
use App\Models\RT;
use App\Models\RW;
use App\Models\SetoranRW;
use App\Models\SlipGaji;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Alur: RW (1) → RT (3) → Warga (10/RT) → Petugas (3) → Iuran → Kas → Setoran → Gaji
     */
    public function run(): void
    {
        // ── 1. RW ─────────────────────────────────────────────────────────────
        $rw = RW::factory()->create([
            'nomor_rw'   => '001',
            'nama'       => 'RW 001',
        ]);

        // ── 2. RT (3 RT di bawah RW) ─────────────────────────────────────────
        $rts = collect(range(1, 3))->map(function ($i) use ($rw) {
            return RT::factory()->create([
                'id_rw'      => $rw->id,
                'nomor_rt'   => str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama'       => "RT 00{$i}",
            ]);
        });

        // ── 4. Warga (10 KK per RT) ──────────────────────────────────────────
        $rts->each(function ($rt) {
            Warga::factory(10)->create(['id_rt' => $rt->id]);
        });

        // ── 5. Petugas ─────────────
        $petugas = Petugas::factory(6)->create([
            'id_rw' => $rw->id
        ]);

        // ── 6. Jenis Iuran (2 jenis per RT) ──────────────────────────────────
        $rts->each(function ($rt) {
            JenisIuranWarga::create(['id_rt' => $rt->id, 'jenis_iuran' => 'Iuran Kebersihan', 'jumlah' => 35000]);
            JenisIuranWarga::create(['id_rt' => $rt->id, 'jenis_iuran' => 'Iuran Keamanan',   'jumlah' => 60000]);
        });

        // ── 7. Iuran Warga (3 bulan × warga × jenis iuran) ───────────────────
        $periodes = ['2025-01', '2025-02', '2025-03'];

        $rts->each(function ($rt) use ($periodes) {
            $jenisIurans = JenisIuranWarga::where('id_rt', $rt->id)->get();
            $wargas      = Warga::where('id_rt', $rt->id)->get();

            $wargas->each(function ($warga) use ($jenisIurans, $rt, $periodes) {
                foreach ($jenisIurans as $jenis) {
                    foreach ($periodes as $periode) {
                        $status = fake()->randomElement(['belum bayar', 'dibayar', 'telat']);
                        $iuran  = IuranWarga::create([
                            'id_warga'       => $warga->id,
                            'id_jenis_iuran' => $jenis->id,
                            'id_rt'          => $rt->id,
                            'periode'        => $periode,
                            'tanggal_bayar'  => $status !== 'belum bayar'
                                ? fake()->dateTimeBetween("$periode-01", "$periode-28")->format('Y-m-d')
                                : null,
                            'status' => $status,
                        ]);

                        // Kwitansi hanya untuk yang sudah dibayar/telat
                        if ($status !== 'belum bayar') {
                            KwitansiIuranWarga::create([
                                'iuran_id'       => $iuran->id,
                                'nomor_kwitansi' => 'KW-IUR-' . strtoupper(fake()->unique()->bothify('####??')),
                                'file_path'      => null,
                                'tanggal_cetak'  => $iuran->tanggal_bayar,
                            ]);
                        }
                    }
                }
            });
        });

        // ── 10. Setoran RW (per RT per bulan) ────────────────────────────────
        $rts->each(function ($rt) use ($rw, $periodes) {
            foreach ($periodes as $periode) {
                $setoran = SetoranRW::create([
                    'id_rt'           => $rt->id,
                    'id_rw'           => $rw->id,
                    'periode'         => $periode,
                    'tanggal_setor'   => fake()->dateTimeBetween("$periode-01", "$periode-15")->format('Y-m-d'),
                    'jumlah_setor'    => 2000000,
                    'status_validasi' => 'valid',
                ]);

                // Kwitansi setoran yang sudah valid
                KwitansiSetoranRW::create([
                    'id_setoran'     => $setoran->id,
                    'nomor_kwitansi' => 'KW-SET-' . strtoupper(fake()->unique()->bothify('####??')),
                    'file_path'      => null,
                    'tanggal_cetak'  => $setoran->tanggal_setor,
                ]);
            }
        });

        // ── 8. Kas Masuk & Keluar RT ──────────────────────────────────────────
        $rts->each(function ($rt) {
            KasMasukRT::factory(6)->state(fn () => [
                'id_rt' => $rt->id,
                'tanggal' => fake()->dateTimeBetween('-3 months', 'now'),
            ])->create();
            
            KasKeluarRT::factory(6)->state(fn () => [
                'id_rt' => $rt->id,
                'tanggal' => fake()->dateTimeBetween('-3 months', 'now'),
            ])->create();           
        });

        // ── 9. Kas Bulanan RT (3 bulan per RT) ───────────────────────────────
        $rts->each(function ($rt) use ($periodes) {
            foreach ($periodes as $periode) {
                [$year, $month] = explode('-', $periode);
                $periodeSblm = \Carbon\Carbon::createFromFormat('Y-m', $periode)
                ->subMonth()
                ->format('Y-m');

                $kasBulanSblm = KasBulananRT::where('id_rt', $rt->id)
                                ->where('periode', $periodeSblm)
                                ->first();

                $saldoAwal = $kasBulanSblm ? $kasBulanSblm->saldo_akhir : 0;

                $totalKasMasuk = KasMasukRT::where('id_rt', $rt->id)
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->sum('jumlah');

                $totalIuranWarga = IuranWarga::query()
                    ->join('jenis_iuran_wargas', 'iuran_wargas.id_jenis_iuran', '=', 'jenis_iuran_wargas.id')
                    ->where('iuran_wargas.id_rt', $rt->id)
                    ->where('iuran_wargas.status', 'dibayar')
                    ->whereYear('iuran_wargas.tanggal_bayar', $year)
                    ->whereMonth('iuran_wargas.tanggal_bayar', $month)
                    ->sum('jenis_iuran_wargas.jumlah');
                    
                $totalKasKeluar = KasKeluarRT::where('id_rt', $rt->id)
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->sum('jumlah');

                $totalSetoranRW = SetoranRW::where('id_rt', $rt->id)->sum('jumlah_setor');

                $totalPendapatan  = $totalKasMasuk + $totalIuranWarga;
                $totalPengeluaran = $totalKasKeluar + $totalSetoranRW;
                $totalBersih      = $totalPendapatan - $totalPengeluaran;

                KasBulananRT::create([
                    'id_rt'                   => $rt->id,
                    'periode'                 => $periode,
                    'total_pendapatan'        => $totalPendapatan,
                    'total_pengeluaran'       => $totalPengeluaran,
                    'saldo_awal'              => $saldoAwal,
                    'saldo_akhir'             => $saldoAwal + $totalBersih,
                    'total_pendapatan_bersih' => $totalBersih,
                    'file_path'               => null,
                ]);
            }
        });

        // ── 13. Kasbon & Slip Gaji per Petugas ───────────────────────────────
        $petugas->each(function ($p) use ($periodes) {
            // 1-2 kasbon per petugas
            foreach($periodes as $periode){ 
                [$year, $month] = explode('-', $periode);
                Kasbon::factory(fake()->numberBetween(1, 2))->create(['id_petugas' => $p->id, 'tanggal' => $periode . "-" . fake()->numberBetween(1, 28)]);
                
                $kasbonTotal = $p->kasbons()->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->sum('jumlah');
                // Slip gaji tiap bulan
                SlipGaji::create([
                    'id_petugas' => $p->id,
                    'total'      => $p->gaji_pokok - $kasbonTotal,
                    'tanggal'    => $periode . '-25',
                    'file_path'  => null,
                ]);
            }
        });

        // ── 11. Kas Masuk & Keluar RW ─────────────────────────────────────────
        KasMasukRW::factory(6)->state(fn () => [
            'id_rw' => $rw->id,
            'tanggal' => fake()->dateTimeBetween('-3 months', 'now'),
        ])->create();

        KasKeluarRW::factory(6)->state(fn () => [
            'id_rw' => $rw->id,
            'tanggal' => fake()->dateTimeBetween('-3 months', 'now'),
        ])->create();

        // ── 12. Kas Bulanan RW (3 bulan) ──────────────────────────────────────
        foreach ($periodes as $periode) {
            [$year, $month] = explode('-', $periode);
            $periodeSblm = \Carbon\Carbon::createFromFormat('Y-m', $periode)
                ->subMonth()
                ->format('Y-m');

            $kasBulanSblm = KasBulananRW::where('id_rw', $rw->id)
                ->where('periode', $periodeSblm)
                ->first();

            $saldoAwal = $kasBulanSblm ? $kasBulanSblm->saldo_akhir : 0;

            $totalKasMasuk = KasMasukRW::where('id_rw', $rw->id)
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->sum('jumlah');
                
            $totalKasKeluar = KasKeluarRW::where('id_rw', $rw->id)
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->sum('jumlah');

            $totalSlipGaji = SlipGaji::whereHas('petugas', function ($q) use ($rw) {
                $q->where('id_rw', $rw->id);
            })
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->sum('total');

            $totalSetoranRW = SetoranRW::where('id_rw', $rw->id)->sum('jumlah_setor');

            $totalPendapatan  = $totalKasMasuk + $totalSetoranRW;
            $totalPengeluaran = $totalKasKeluar + $totalSlipGaji;
            $totalBersih      = $totalPendapatan - $totalPengeluaran;

            KasBulananRW::create([
                'id_rw'                   => $rw->id,
                'periode'                 => $periode,
                'total_pendapatan'        => $totalPendapatan,
                'total_pengeluaran'       => $totalPengeluaran,
                'total_pendapatan_bersih' => $totalBersih,
                'saldo_awal'              => $saldoAwal,
                'saldo_akhir'             => $saldoAwal + $totalBersih,
                'file_path'               => null,
            ]);
        }

        
    }
}
