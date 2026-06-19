<?php

namespace Database\Factories;

use App\Enums\SetoranStatusValidasi;
use App\Models\RT;
use App\Models\RW;
use App\Models\SetoranRW;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SetoranRW>
 */
class SetoranRWFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_rt' => RT::inRandomOrder()->first()->id,
            'id_rw' => RW::inRandomOrder()->first()->id,
            'periode' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m'),
            'tanggal_setor' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'jumlah_setor' => fake()->numberBetween(500000, 10000000),
            'status_validasi' => fake()->randomElement(SetoranStatusValidasi::cases()),
        ];
    }

    public function valid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_validasi' => SetoranStatusValidasi::VALID,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_validasi' => SetoranStatusValidasi::PENDING,
        ]);
    }
}
