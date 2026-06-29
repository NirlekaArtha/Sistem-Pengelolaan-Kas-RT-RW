<?php

namespace Database\Seeders;

use App\Enums\IuranWargaStatus;
use App\Enums\KasTipe;
use App\Enums\SetoranStatusValidasi;
use App\Enums\SlipGajiStatus;
use App\Enums\UserRole;
use App\Models\JenisIuranWarga;
use App\Models\Kasbon;
use App\Models\KasRT;
use App\Models\KasRW;
use App\Models\Petugas;
use App\Models\RT;
use App\Models\RW;
use App\Models\SetoranRW;
use App\Models\User;
use App\Models\Warga;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $periodes = [
            '2025-07',
            '2025-08',
            '2025-09',
            '2025-10',
            '2025-11',
            '2025-12',
            '2026-01',
            '2026-02',
            '2026-03',
            '2026-04',
            '2026-05',
            '2026-06',
        ];

        $rwConfigs = [
            [
                'email' => 'rw05@gmail.com',
                'nomor_rw' => '005',
                'nama' => 'M. Rochmat Hidayat, S.E',
            ],
            ['email' => 'rw2@gmail.com', 'nomor_rw' => '002'],
        ];

        foreach ($rwConfigs as $config) {
            $this->seedRW($config, $periodes);
        }
    }

    // -------------------------------------------------------------------------
    // Per-RW orchestrator
    // -------------------------------------------------------------------------

    private function seedRW(array $config, array $periodes): void
    {
        // --- RW & RT ---------------------------------------------------------
        $rwName = fake()->name();
        $userRw = User::factory()->create([
            'name' => $rwName,
            'email' => $config['email'],
            'role' => UserRole::RW,
        ]);

        $rw = RW::factory()->create([
            'id_user' => $userRw->id,
            'nomor_rw' => $config['nomor_rw'],
            'nama' => $rwName,
        ]);

        $rts = $this->createRTs($rw);

        // --- Petugas ---------------------------------------------------------
        $petugas = Petugas::factory(5)->create(['id_rw' => $rw->id]);

        // --- Per-RT static data (jenis iuran, kas acak) ----------------------
        $this->seedRTStaticData($rts);

        // --- Kas RW acak (bulk) ----------------------------------------------
        KasRW::factory(60)
            ->masuk()
            ->create([
                'id_rw' => $rw->id,
                'tanggal' => fn () => fake()->dateTimeBetween(
                    '2025-07-01',
                    '2026-06-30',
                ),
            ]);
        KasRW::factory(20)
            ->keluar()
            ->create([
                'id_rw' => $rw->id,
                'tanggal' => fn () => fake()->dateTimeBetween(
                    '2025-07-01',
                    '2026-06-30',
                ),
            ]);

        // Prefetch lookup data agar tidak query berulang per periode
        $jenisIuranByRT = JenisIuranWarga::whereIn('id_rt', $rts->pluck('id'))
            ->get()
            ->groupBy('id_rt');

        $wargasByRT = Warga::whereIn('id_rt', $rts->pluck('id'))
            ->get()
            ->groupBy('id_rt');

        // --- Loop periode ----------------------------------------------------
        $saldoAkhirRT = []; // cache saldo akhir per RT, dihitung kumulatif
        $saldoAkhirRW = 0;

        foreach ($periodes as $periode) {
            [$year, $month] = explode('-', $periode);
            $periodeSblm = Carbon::createFromFormat('Y-m', $periode)
                ->subMonth()
                ->format('Y-m');

            // Seed iuran + setoran semua RT sekaligus (bulk insert)
            $this->seedPeriodeRT(
                $rts,
                $rw,
                $periode,
                $year,
                $month,
                $periodeSblm,
                $jenisIuranByRT,
                $wargasByRT,
                $saldoAkhirRT,
            );

            // Kasbon + slip gaji petugas
            $this->seedPetugasPeriode($petugas, $periode, $year, $month);

            // KasBulanan RW
            $saldoAkhirRW = $this->seedKasBulananRW(
                $rw,
                $periode,
                $year,
                $month,
                $saldoAkhirRW,
            );
        }
    }

    // -------------------------------------------------------------------------
    // RT creation
    // -------------------------------------------------------------------------

    private function createRTs(RW $rw)
    {
        return collect(range(1, 4))->map(function ($i) use ($rw) {
            $rtName = fake()->name();
            $userRt = User::factory()->create([
                'email' => "rt{$i}_{$rw->nomor_rw}@gmail.com",
                'name' => $rtName,
                'role' => UserRole::RT,
            ]);

            return RT::factory()->create([
                'id_user' => $userRt->id,
                'id_rw' => $rw->id,
                'nomor_rt' => str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama' => $rtName,
            ]);
        });
    }

    // -------------------------------------------------------------------------
    // RT static data: jenis iuran + warga + kas acak
    // -------------------------------------------------------------------------

    private function seedRTStaticData($rts): void
    {
        // Bulk-insert jenis iuran untuk semua RT sekaligus
        $jenisIuranRows = [];
        $now = now();
        foreach ($rts as $rt) {
            foreach (
                [
                    ['Iuran Kebersihan', 35000],
                    ['Iuran Keamanan', 60000],
                    ['Iuran Sampah', 40000],
                ] as [$jenis, $jumlah]
            ) {
                $jenisIuranRows[] = [
                    'id_rt' => $rt->id,
                    'jenis_iuran' => $jenis,
                    'jumlah' => $jumlah,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        DB::table('jenis_iuran_wargas')->insert($jenisIuranRows);

        // Warga per RT
        $rts->each(function ($rt) {
            Warga::factory(30)
                ->make(['id_rt' => $rt->id])
                ->each(function ($warga) {
                    $name = fake()->name();
                    $userWarga = User::factory()->create([
                        'name' => $name,
                        'role' => UserRole::WARGA,
                    ]);
                    $warga->id_user = $userWarga->id;
                    $warga->nama_kepala_keluarga = $name;
                    $warga->save();
                });

            // Kas RT acak (bulk via factory batch)
            KasRT::factory(18)
                ->masuk()
                ->create([
                    'id_rt' => $rt->id,
                    'tanggal' => fn () => fake()->dateTimeBetween(
                        '2025-07-01',
                        '2026-06-30',
                    ),
                ]);
            KasRT::factory(6)
                ->keluar()
                ->create([
                    'id_rt' => $rt->id,
                    'tanggal' => fn () => fake()->dateTimeBetween(
                        '2025-07-01',
                        '2026-06-30',
                    ),
                ]);
        });
    }

    // -------------------------------------------------------------------------
    // Periode: iuran warga + setoran + kas bulanan RT
    // -------------------------------------------------------------------------

    private function seedPeriodeRT(
        $rts,
        RW $rw,
        string $periode,
        string $year,
        string $month,
        string $periodeSblm,
        $jenisIuranByRT,
        $wargasByRT,
        array &$saldoAkhirRT,
    ): void {
        $now = now();
        $statusOptions = IuranWargaStatus::cases();

        foreach ($rts as $rt) {
            $jenisIurans = $jenisIuranByRT[$rt->id] ?? collect();
            $wargas = $wargasByRT[$rt->id] ?? collect();

            // --- Bulk-insert iuran warga + kwitansi --------------------------
            $iuranRows = [];
            $kwitansiRows = [];

            foreach ($wargas as $warga) {
                foreach ($jenisIurans as $jenis) {
                    $status = fake()->randomElement($statusOptions);
                    $tanggalBayar =
                        $status === IuranWargaStatus::DIBAYAR
                            ? "$periode-".fake()->numberBetween(1, 28)
                            : null;

                    $iuranRows[] = [
                        'id_warga' => $warga->id,
                        'id_jenis_iuran' => $jenis->id,
                        'id_rt' => $rt->id,
                        'periode' => $periode,
                        'tanggal_bayar' => $tanggalBayar,
                        'status' => $status,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // Insert iuran sekaligus, ambil ID-nya untuk kwitansi
            DB::table('iuran_wargas')->insert($iuranRows);

            // Ambil ID iuran yang baru diinsert untuk kwitansi
            $insertedIurans = DB::table('iuran_wargas')
                ->where('id_rt', $rt->id)
                ->where('periode', $periode)
                ->whereNotNull('tanggal_bayar')
                ->get(['id', 'tanggal_bayar']);

            foreach ($insertedIurans as $iuran) {
                $kwitansiRows[] = [
                    'iuran_id' => $iuran->id,
                    'nomor_kwitansi' => 'KW-IUR-'.
                        strtoupper(fake()->unique()->bothify('####??')),
                    'tanggal_cetak' => $iuran->tanggal_bayar,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if ($kwitansiRows) {
                DB::table('kwitansi_iuran_wargas')->insert($kwitansiRows);
            }

            // --- Setoran RW --------------------------------------------------
            $tanggalSetor = "$periode-".fake()->numberBetween(1, 15);

            $setoranId = DB::table('setoran_rw')->insertGetId([
                'id_rt' => $rt->id,
                'id_rw' => $rw->id,
                'periode' => $periode,
                'tanggal_setor' => $tanggalSetor,
                'jumlah_setor' => 2000000,
                'status_validasi' => SetoranStatusValidasi::VALID->value,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('kwitansi_setoran_r_w_s')->insert([
                'id_setoran' => $setoranId,
                'nomor_kwitansi' => 'KW-SET-'.strtoupper(fake()->unique()->bothify('####??')),
                'tanggal_cetak' => $tanggalSetor,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // --- Kas Bulanan RT ----------------------------------------------
            $saldoAwal = $saldoAkhirRT[$rt->id] ?? 0;

            $totalKasMasuk = KasRT::where('id_rt', $rt->id)
                ->where('tipe', KasTipe::MASUK->value)
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->sum('jumlah');

            $totalKasKeluar = KasRT::where('id_rt', $rt->id)
                ->where('tipe', KasTipe::KELUAR->value)
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->sum('jumlah');

            $totalIuranWarga = DB::table('iuran_wargas')
                ->join(
                    'jenis_iuran_wargas',
                    'iuran_wargas.id_jenis_iuran',
                    '=',
                    'jenis_iuran_wargas.id',
                )
                ->where('iuran_wargas.id_rt', $rt->id)
                ->where('iuran_wargas.status', IuranWargaStatus::DIBAYAR->value)
                ->whereYear('iuran_wargas.tanggal_bayar', $year)
                ->whereMonth('iuran_wargas.tanggal_bayar', $month)
                ->sum('jenis_iuran_wargas.jumlah');

            $totalSetoranRW = 2000000; // sudah kita hardcode di atas

            $totalPendapatan = $totalKasMasuk + $totalIuranWarga;
            $totalPengeluaran = $totalKasKeluar + $totalSetoranRW;
            $totalBersih = $totalPendapatan - $totalPengeluaran;
            $saldoAkhir = $saldoAwal + $totalBersih;

            DB::table('kas_bulanan_rt')->updateOrInsert(
                [
                    'id_rt' => $rt->id,
                    'periode' => $periode,
                ],
                [
                    'total_pendapatan' => $totalPendapatan,
                    'total_pengeluaran' => $totalPengeluaran,
                    'saldo_awal' => $saldoAwal,
                    'saldo_akhir' => $saldoAkhir,
                    'total_pendapatan_bersih' => $totalBersih,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );

            $saldoAkhirRT[$rt->id] = $saldoAkhir; // cache untuk periode berikutnya
        }
    }

    // -------------------------------------------------------------------------
    // Kasbon + slip gaji petugas
    // -------------------------------------------------------------------------

    private function seedPetugasPeriode(
        $petugas,
        string $periode,
        string $year,
        string $month,
    ): void {
        $now = now();
        $slipRows = [];

        foreach ($petugas as $p) {
            Kasbon::factory(fake()->numberBetween(1, 2))->create([
                'id_petugas' => $p->id,
                'tanggal' => fn () => "$periode-".fake()->numberBetween(1, 28),
            ]);

            $periodeCarbon = Carbon::createFromFormat('Y-m', $periode);
            $gajiStart = $periodeCarbon
                ->copy()
                ->subMonth()
                ->day(26)
                ->toDateString();
            $gajiEnd = $periodeCarbon->copy()->day(25)->toDateString();

            $kasbonTotal = $p
                ->kasbons()
                ->whereBetween('tanggal', [$gajiStart, $gajiEnd])
                ->sum('jumlah');

            $slipRows[] = [
                'id_petugas' => $p->id,
                'total' => $p->gaji_pokok - $kasbonTotal,
                'tanggal' => "$periode-25",
                'status' => $periode === now()->format('Y-m')
                    ? SlipGajiStatus::BELUM_DIBAYAR->value
                    : SlipGajiStatus::TELAH_DIBAYAR->value,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('slip_gajis')->insert($slipRows);
    }

    // -------------------------------------------------------------------------
    // Kas Bulanan RW
    // -------------------------------------------------------------------------

    private function seedKasBulananRW(
        RW $rw,
        string $periode,
        string $year,
        string $month,
        float $saldoAwal,
    ): float {
        $now = now();
        $periodeCarbon = Carbon::createFromFormat('Y-m', $periode);
        $gajiStart = $periodeCarbon
            ->copy()
            ->subMonth()
            ->day(26)
            ->toDateString();
        $gajiEnd = $periodeCarbon->copy()->day(25)->toDateString();

        $totalKasMasukRW = KasRW::where('id_rw', $rw->id)
            ->where('tipe', KasTipe::MASUK->value)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->sum('jumlah');

        $totalKasKeluarRW = KasRW::where('id_rw', $rw->id)
            ->where('tipe', KasTipe::KELUAR->value)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->sum('jumlah');

        $totalSlipGaji = DB::table('slip_gajis')
            ->join('petugas', 'slip_gajis.id_petugas', '=', 'petugas.id')
            ->where('petugas.id_rw', $rw->id)
            ->whereBetween('slip_gajis.tanggal', [$gajiStart, $gajiEnd])
            ->where(
                'slip_gajis.status',
                SlipGajiStatus::TELAH_DIBAYAR->value,
            )
            ->sum('slip_gajis.total');

        $totalKasbon = DB::table('kasbons')
            ->join('petugas', 'kasbons.id_petugas', '=', 'petugas.id')
            ->where('petugas.id_rw', $rw->id)
            ->whereBetween('kasbons.tanggal', [$gajiStart, $gajiEnd])
            ->sum('kasbons.jumlah');

        $totalSetoranMasuk = SetoranRW::where('id_rw', $rw->id)
            ->where('periode', $periode)
            ->sum('jumlah_setor');

        $totalPendapatan = $totalKasMasukRW + $totalSetoranMasuk;
        $totalPengeluaran = $totalKasKeluarRW + $totalSlipGaji + $totalKasbon;
        $totalBersih = $totalPendapatan - $totalPengeluaran;
        $saldoAkhir = $saldoAwal + $totalBersih;

        DB::table('kas_bulanan_rw')->updateOrInsert(
            [
                'id_rw' => $rw->id,
                'periode' => $periode,
            ],
            [
                'total_pendapatan' => $totalPendapatan,
                'total_pengeluaran' => $totalPengeluaran,
                'total_pendapatan_bersih' => $totalBersih,
                'saldo_awal' => $saldoAwal,
                'saldo_akhir' => $saldoAkhir,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );

        return $saldoAkhir;
    }
}
