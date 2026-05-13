<?php

namespace Database\Factories;

use App\Models\RT;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warga>
 */
class WargaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_rt'                => RT::inRandomOrder()->first()->id,
            'id_user'              => User::factory()->create(['role' => 'Warga'])->id,
            'nama_kepala_keluarga' => fake()->name('male'),
            'alamat'               => fake()->address(),
            'no_telepon'           => '08' . fake()->numerify('##########'),
        ];
    }
}
