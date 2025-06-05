<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ruangan>
 */
class RuanganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gambar' => $this->faker->imageUrl(640, 480, null, true),
            'nama_ruangan' => 'Ruang ' . $this->faker->randomElement(['Gedung']) . ' ' . $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8]),
            'lokasi' => $this->faker->randomElement([
                'Gedung D4 TRPL - Lantai 3',
                'Gedung Lab EC - Lantai 2',
                'Gedung PUT - Lantai 2',
                'Gedung D - Lantai 3',
            ]),
        ];
    }
}
