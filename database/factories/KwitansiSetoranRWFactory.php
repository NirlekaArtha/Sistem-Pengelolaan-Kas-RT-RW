<?php

namespace Database\Factories;

use App\Models\KwitansiSetoranRW;
use App\Models\SetoranRW;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KwitansiSetoranRW>
 */
class KwitansiSetoranRWFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_setoran' => SetoranRW::inRandomOrder()->first()->id,
            'nomor_kwitansi' => 'KW-SET-'.strtoupper(fake()->unique()->bothify('####??')),
            'file_path' => null,
            'tanggal_cetak' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
        ];
    }
}
