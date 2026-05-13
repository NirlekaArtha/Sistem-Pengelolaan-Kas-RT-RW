<?php

namespace Database\Factories;

use App\Models\KasBulananRT;
use App\Models\RT;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KasBulananRT>
 */
class KasBulananRTFactory extends Factory
{
    public function definition(): array
    {
        $saldoAwal       = fake()->numberBetween(500000, 5000000);
        $totalPendapatan = fake()->numberBetween(500000, 10000000);
        $totalPengeluaran = fake()->numberBetween(100000, (int)($totalPendapatan * 0.8));
        $totalBersih     = $totalPendapatan - $totalPengeluaran;
        $saldoAkhir      = $saldoAwal + $totalBersih;

        return [
            'id_rt'                   => RT::factory(),
            'periode'                 => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m'),
            'total_pendapatan'        => $totalPendapatan,
            'total_pengeluaran'       => $totalPengeluaran,
            'saldo_awal'              => $saldoAwal,
            'saldo_akhir'             => $saldoAkhir,
            'total_pendapatan_bersih' => $totalBersih,
            'file_path'               => null,
        ];
    }
}
