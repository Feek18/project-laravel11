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
            'gambar' => $this->faker->randomElement([
                'https://images.unsplash.com/photo-1635424239131-32dc44986b56?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1585637071663-799845ad5212?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1606761568499-6d2451b23c66?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://i.pinimg.com/736x/90/c3/ba/90c3ba81ec1d9728b503d5fdb7f192d5.jpg',
                'https://i.pinimg.com/736x/c0/7c/e5/c07ce573428d5ae4bdf5b2858c449b5c.jpg',
            ]),
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
