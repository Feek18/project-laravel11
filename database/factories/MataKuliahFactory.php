<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MataKuliah>
 */
class MataKuliahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_matkul' => 'MK' . $this->faker->unique()->numberBetween(10, 50),
            'mata_kuliah' => $this->faker->randomElement([
                'Sistem Informasi Pariwisata',
                'Interoperabilitas',
                'Kecerdasan Buatan',
                'Pengolahan Citra Digital',
                'Analisa dan Desain Perangkat Lunak',
                'Data Warehouse',
                'Pemrograman Perangkat Bergerak (Mobile Programming)',
            ]),
            'semester' => '5',
        ];
    }
}
