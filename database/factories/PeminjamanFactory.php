<?php

namespace Database\Factories;

use App\Models\Pengguna;
use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pengguna = Pengguna::inRandomOrder()->first() ?? Pengguna::factory()->create();
        $ruangan = Ruangan::inRandomOrder()->first() ?? Ruangan::factory()->create();

        $tanggal = $this->faker->dateTimeBetween('now', '+1 month');
        $waktuMulai = $this->faker->time('H:i:s');
        $durasi = $this->faker->numberBetween(1, 2);
        $waktuSelesai = date('H:i:s', strtotime($waktuMulai . " +$durasi hour"));

        return [
            'id_pengguna' => $pengguna->id,
            'id_ruang' => $ruangan->id_ruang,
            'status_peminjaman' => 'terencana',
            'keperluan' => $this->faker->sentence(),
            'status_persetujuan' => $this->faker->randomElement(['pending', 'disetujui', 'ditolak']),
            'tanggal_pinjam' => $tanggal->format('Y-m-d'),
            'waktu_mulai' => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
        ];
    }
}
