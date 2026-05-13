<?php

namespace Database\Factories;

use App\Models\JenisIuranWarga;
use App\Models\RT;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JenisIuranWarga>
 */
class JenisIuranWargaFactory extends Factory
{
    public function definition(): array
    {
        $jenis = fake()->randomElement([
            'Iuran Kebersihan',
            'Iuran Keamanan',
            'Iuran Sosial',
            'Iuran Sampah',
            'Iuran Perbaikan Jalan',
        ]);

        return [
            'id_rt'       => RT::inRandomOrder()->first()->id,
            'jenis_iuran' => $jenis,
            'jumlah'      => fake()->randomElement([10000, 15000, 20000, 25000, 30000, 50000]),
        ];
    }
}
