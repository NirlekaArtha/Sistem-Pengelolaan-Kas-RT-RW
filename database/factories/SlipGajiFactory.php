<?php

namespace Database\Factories;

use App\Enums\SlipGajiStatus;
use App\Models\Petugas;
use App\Models\SlipGaji;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SlipGaji>
 */
class SlipGajiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_petugas' => Petugas::inRandomOrder()->first()->id,
            'total' => fake()->numberBetween(1000000, 3000000),
            'tanggal' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'status' => SlipGajiStatus::BELUM_DIBAYAR,
            'file_path' => null,
        ];
    }
}
