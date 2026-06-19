<?php

namespace Database\Factories;

use App\Models\IuranWarga;
use App\Models\KwitansiIuranWarga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KwitansiIuranWarga>
 */
class KwitansiIuranWargaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'iuran_id' => IuranWarga::inRandomOrder()->first()->id,
            'nomor_kwitansi' => 'KW-IUR-'.strtoupper(fake()->unique()->bothify('####??')),
            'file_path' => null,
            'tanggal_cetak' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
        ];
    }
}
