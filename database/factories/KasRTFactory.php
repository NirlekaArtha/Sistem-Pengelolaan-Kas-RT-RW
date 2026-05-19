<?php

namespace Database\Factories;

use App\Models\KasRT;
use App\Models\RT;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KasRT>
 */
class KasRTFactory extends Factory
{
    public function definition(): array
    {
        $tipe = fake()->randomElement(['masuk', 'keluar']);
        
        $jenis = $tipe === 'masuk'
            ? fake()->randomElement(['donasi', 'sponsorship', 'hibah', 'hasil usaha', 'lainnya'])
            : fake()->randomElement(['operasional', 'kegiatan', 'lainnya']);

        $sumberTujuan = $tipe === 'masuk'
            ? fake()->company()
            : fake()->words(2, true);

        $jumlah = $tipe === 'masuk'
            ? fake()->numberBetween(100000, 5000000)
            : fake()->numberBetween(50000, 3000000);

        return [
            'id_rt'         => RT::inRandomOrder()->first()?->id ?? RT::factory(),
            'tipe'          => $tipe,
            'jenis'         => $jenis,
            'jumlah'        => $jumlah,
            'sumber_tujuan' => $sumberTujuan,
            'keterangan'    => fake()->sentence(),
            'tanggal'       => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }

    public function masuk(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe'          => 'masuk',
            'jenis'         => fake()->randomElement(['donasi', 'sponsorship', 'hibah', 'hasil usaha', 'lainnya']),
            'sumber_tujuan' => fake()->company(),
            'jumlah'        => fake()->numberBetween(100000, 5000000),
        ]);
    }

    public function keluar(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe'          => 'keluar',
            'jenis'         => fake()->randomElement(['operasional', 'kegiatan', 'lainnya']),
            'sumber_tujuan' => fake()->words(2, true),
            'jumlah'        => fake()->numberBetween(50000, 3000000),
        ]);
    }
}
