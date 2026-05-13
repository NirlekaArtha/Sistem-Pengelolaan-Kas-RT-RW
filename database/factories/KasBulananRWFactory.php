<?php

namespace Database\Factories;

use App\Models\KasBulananRW;
use App\Models\RW;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KasBulananRW>
 */
class KasBulananRWFactory extends Factory
{
    public function definition(): array
    {
        $saldoAwal        = fake()->numberBetween(1000000, 10000000);
        $totalPendapatan  = fake()->numberBetween(2000000, 30000000);
        $totalPengeluaran = fake()->numberBetween(500000, (int)($totalPendapatan * 0.8));
        $totalBersih      = $totalPendapatan - $totalPengeluaran;
        $saldoAkhir       = $saldoAwal + $totalBersih;

        return [
            'id_rw'                   => RW::inRandomOrder()->first()->id,
            'periode'                 => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m'),
            'total_pendapatan'        => $totalPendapatan,
            'total_pengeluaran'       => $totalPengeluaran,
            'total_pendapatan_bersih' => $totalBersih,
            'saldo_awal'              => $saldoAwal,
            'saldo_akhir'             => $saldoAkhir,
            'file_path'               => null,
        ];
    }
}
