<?php

namespace Database\Factories;

use App\Models\KasMasukRW;
use App\Models\RW;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KasMasukRW>
 */
class KasMasukRWFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_rw'      => RW::inRandomOrder()->first()->id,
            'jenis'      => fake()->randomElement(['donasi', 'sponsorship', 'hibah', 'hasil usaha', 'lainnya']),
            'jumlah'     => fake()->numberBetween(500000, 20000000),
            'sumber'     => fake()->company(),
            'keterangan' => fake()->sentence(),
            'tanggal'    => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }
}
