<?php

namespace Database\Factories;

use App\Models\KasKeluarRT;
use App\Models\RT;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KasKeluarRT>
 */
class KasKeluarRTFactory extends Factory
{
    public function definition(): array
    {
        return [
            "id_rt" => RT::inRandomOrder()->first()->id,
            "jenis" => fake()->randomElement([
                "operasional",
                "kegiatan",
                "lainnya",
            ]),
            "jumlah" => fake()->numberBetween(50000, 3000000),
            "penerima" => fake()->words(2, true),
            "keterangan" => fake()->sentence(),
            "tanggal" => fake()
                ->dateTimeBetween("-6 months", "now")
                ->format("Y-m-d"),
        ];
    }
}
