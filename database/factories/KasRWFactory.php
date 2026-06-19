<?php

namespace Database\Factories;

use App\Enums\KasJenis;
use App\Enums\KasTipe;
use App\Models\KasRW;
use App\Models\RW;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KasRW>
 */
class KasRWFactory extends Factory
{
    public function definition(): array
    {
        $tipe = fake()->randomElement(KasTipe::cases());

        $jenis =
            $tipe === KasTipe::MASUK
                ? fake()->randomElement([
                    KasJenis::DONASI,
                    KasJenis::SPONSORSHIP,
                    KasJenis::HIBAH,
                    KasJenis::HASIL_USAHA,
                    KasJenis::LAINNYA,
                ])
                : fake()->randomElement([
                    KasJenis::OPERASIONAL,
                    KasJenis::KEGIATAN,
                    KasJenis::LAINNYA,
                ]);

        $sumberTujuan =
            $tipe === KasTipe::MASUK ? fake()->company() : fake()->words(2, true);

        $jumlah =
            $tipe === KasTipe::MASUK
                ? fake()->numberBetween(500000, 20000000)
                : fake()->numberBetween(200000, 10000000);

        return [
            'id_rw' => RW::inRandomOrder()->first()?->id ?? RW::factory(),
            'tipe' => $tipe,
            'jenis' => $jenis,
            'jumlah' => $jumlah,
            'sumber_tujuan' => $sumberTujuan,
            'keterangan' => fake()->sentence(),
            'tanggal' => fake()
                ->dateTimeBetween('-6 months', 'now')
                ->format('Y-m-d'),
        ];
    }

    public function masuk(): static
    {
        return $this->state(
            fn (array $attributes) => [
                'tipe' => KasTipe::MASUK,
                'jenis' => fake()->randomElement([
                    KasJenis::DONASI,
                    KasJenis::SPONSORSHIP,
                    KasJenis::HIBAH,
                    KasJenis::HASIL_USAHA,
                    KasJenis::LAINNYA,
                ]),
                'sumber_tujuan' => fake()->company(),
                'jumlah' => function () {
                    $ratusanRibu = fake()->numberBetween(50, 1000) * 1000;
                    $pecahanRatusan = fake()->randomElement([
                        100,
                        200,
                        500,
                        900,
                    ]);

                    return $ratusanRibu + $pecahanRatusan;
                },
            ],
        );
    }

    public function keluar(): static
    {
        return $this->state(
            fn (array $attributes) => [
                'tipe' => KasTipe::KELUAR,
                'jenis' => fake()->randomElement([
                    KasJenis::OPERASIONAL,
                    KasJenis::KEGIATAN,
                    KasJenis::LAINNYA,
                ]),
                'sumber_tujuan' => fake()->words(2, true),
                'jumlah' => function () {
                    $ratusanRibu = fake()->numberBetween(50, 500) * 1000;
                    $pecahanRatusan = fake()->randomElement([
                        100,
                        200,
                        500,
                        900,
                    ]);

                    return $ratusanRibu + $pecahanRatusan;
                },
            ],
        );
    }
}
