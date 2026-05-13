<?php

namespace Database\Factories;

use App\Models\Petugas;
use App\Models\RW;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Petugas>
 */
class PetugasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_rw'      => RW::inRandomOrder()->first()->id,
            'tugas'      => fake()->randomElement(['satpam', 'kebersihan', 'sampah']),
            'nama'       => fake()->name(),
            'alamat'     => fake()->address(),
            'gaji_pokok' => fake()->randomElement([1500000, 1800000, 2000000, 2200000, 2500000]),
        ];
    }
}
