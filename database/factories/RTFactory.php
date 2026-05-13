<?php

namespace Database\Factories;

use App\Models\RT;
use App\Models\RW;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RT>
 */
class RTFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_rw'      => RW::inRandomOrder()->first()->id,
            'id_user'    => User::factory()->create(['role' => 'RT'])->id,
            'nomor_rt'   => fake()->unique()->numerify('###'),
            'nama'       => 'RT ' . fake()->numerify('###'),
            'alamat'     => fake()->address(),
            'no_telepon' => '08' . fake()->numerify('##########'),
        ];
    }
}
