<?php

namespace Database\Factories;

use App\Models\KasKeluarRW;
use App\Models\RW;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KasKeluarRW>
 */
class KasKeluarRWFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_rw'      => RW::inRandomOrder()->first()->id,
            'jenis'      => fake()->randomElement(['operasional', 'kegiatan', 'lainnya']),
            'jumlah'     => fake()->numberBetween(200000, 10000000),
            'keterangan' => fake()->sentence(),
            'tanggal'    => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }
}
