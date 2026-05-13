<?php

namespace Database\Factories;

use App\Models\KasMasukRT;
use App\Models\RT;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KasMasukRT>
 */
class KasMasukRTFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_rt'      => RT::inRandomOrder()->first()->id,
            'jenis'      => fake()->randomElement(['donasi', 'sponsorship', 'hibah', 'hasil usaha', 'lainnya']),
            'jumlah'     => fake()->numberBetween(100000, 5000000),
            'sumber'     => fake()->company(),
            'keterangan' => fake()->sentence(),
            'tanggal'    => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }
}
