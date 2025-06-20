<?php

namespace Database\Factories;

use App\Models\MataKuliah;
use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_ruang' => Ruangan::factory(),
            'id_matkul' => MataKuliah::factory(),
            'hari' => $this->faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']),
            'jam_mulai' => $start = $this->faker->time('H:i', '15:00'),
            'jam_selesai' => date('H:i', strtotime($start . ' +1 hours')),
        ];

    }
}
