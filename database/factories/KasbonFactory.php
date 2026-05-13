<?php

namespace Database\Factories;

use App\Models\Kasbon;
use App\Models\Petugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kasbon>
 */
class KasbonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_petugas' => Petugas::inRandomOrder()->first()->id,
            'jumlah'     => fake()->randomElement([100000, 200000, 250000, 300000, 500000]),
            'tanggal'    => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
        ];
    }
}
