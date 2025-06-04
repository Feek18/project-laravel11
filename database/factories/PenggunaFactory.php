<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengguna>
 */
class PenggunaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'user_id' => null,
            'nama' => $this->faker->name,
            'alamat' => $this->faker->address,     
            'gender' => $this->faker->randomElement(['pria', 'wanita']),
            'no_telp' => $this->faker->unique()->phoneNumber,
            'gambar' => null,
        ];
    }
}
