<?php

namespace Database\Factories;

use App\Models\RW;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RW>
 */
class RWFactory extends Factory
{
    public function definition(): array
    {
        return [
            "id_user" => User::factory()->create([
                "role" => "RW",
                "email" => "adminrw@gmail.com",
            ])->id,
            "nomor_rw" => fake()->unique()->numerify("###"),
            "nama" => "RW " . fake()->numerify("###"),
            "alamat" => fake()->address(),
            "no_telepon" => "08" . fake()->numerify("##########"),
        ];
    }
}
