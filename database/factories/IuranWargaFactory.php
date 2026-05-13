<?php

namespace Database\Factories;

use App\Models\IuranWarga;
use App\Models\JenisIuranWarga;
use App\Models\RT;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IuranWarga>
 */
class IuranWargaFactory extends Factory
{
    public function definition(): array
    {
        $status       = fake()->randomElement(['belum bayar', 'dibayar', 'telat']);
        $tanggalBayar = $status !== 'belum bayar'
            ? fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d')
            : null;

        return [
            'id_warga'       => Warga::inRandomOrder()->first()->id,
            'id_jenis_iuran' => JenisIuranWarga::inRandomOrder()->first()->id,
            'id_rt'          => RT::inRandomOrder()->first()->id,
            'periode'        => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m'),
            'tanggal_bayar'  => $tanggalBayar,
            'status'         => $status,
        ];
    }
}
