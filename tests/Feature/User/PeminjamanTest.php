<?php

namespace Tests\Feature\User;

use App\Models\Pengguna;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Peminjaman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class PeminjamanTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $ruangan;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'pengguna', 'guard_name' => 'web']);

        $this->user = User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => bcrypt('asdasd'),
        ]);

        $this->user->assignRole('pengguna');

        $this->pengguna = Pengguna::factory()->create([
            'user_id' => $this->user->id,
            'nama' => 'User',
            'alamat' => 'jakarta',
            'no_telp' => '08123456789',
            'gender' => 'pria',
            'gambar' => null,
        ]);

        $this->ruangan = Ruangan::factory()->create();
    }

    public function test_user_can_book_room_successfully()
    {
        // dd($this->user);
        $tanggal = Carbon::now()->format('Y-m-d');

        $response = $this->actingAs($this->user)->post('/pemesanan', [
            'id_ruang' => $this->ruangan->id_ruang,
            'tanggal_pinjam' => $tanggal,
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '10:00',
            'keperluan' => 'Tes Booking',
        ]);

        $response->assertRedirect('/pemesanan');
        $this->assertDatabaseHas('peminjaman', [
            'id_ruang' => $this->ruangan->id_ruang,
            'tanggal_pinjam' => $tanggal,
            'waktu_mulai' => '09:00:00',
            'waktu_selesai' => '10:00:00',
            'keperluan' => 'Tes Booking',
        ]);
    }

    public function test_booking_should_fail_due_to_conflict()
    {
        $tanggal = Carbon::now()->format('Y-m-d');

        // Booking pertama (sudah ada di DB)
        Peminjaman::create([
            'id_pengguna' => $this->user->id,
            'id_ruang' => $this->ruangan->id_ruang,
            'tanggal_pinjam' => $tanggal,
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '10:00',
            'keperluan' => 'Booking Awal',
            'status' => 'disetujui',
        ]);

        // Booking kedua (konflik)
        $response = $this->actingAs($this->user)->post('/peminjam', [
            'id_ruang' => $this->ruangan->id_ruang,
            'tanggal_pinjam' => $tanggal,
            'waktu_mulai' => '09:30',
            'waktu_selesai' => '10:30',
            'keperluan' => 'Tes Booking Konflik',
        ]);

        $response->assertSessionHasErrors('conflict');
    }

    // public function test_user_can_book_room_via_qr_successfully()
    // {
    //     $tanggal = Carbon::now()->format('Y-m-d');
    //     $waktuMulai = Carbon::now();
    //     $waktuSelesai = $waktuMulai->copy()->addHour();

    //     $response = $this->actingAs($this->user)->post('/qr/peminjaman', [
    //         'id_ruang' => $this->ruangan->id_ruang,
    //         'keperluan' => 'Tes Booking QR',
    //         'duration' => 1,
    //     ]);

    //     $response->assertRedirectContains('/qr/success');
    //     $this->assertDatabaseHas('peminjamans', [
    //         'id_ruang' => $this->ruangan->id_ruang,
    //         'keperluan' => 'Tes Booking QR',
    //     ]);
    // }

    // public function test_qr_booking_should_fail_due_to_conflict()
    // {
    //     $waktuMulai = Carbon::now();
    //     $waktuSelesai = $waktuMulai->copy()->addHour();

    //     // Booking pertama
    //     Peminjaman::create([
    //         'id_pengguna' => $this->user->id,
    //         'id_ruang' => $this->ruangan->id_ruang,
    //         'tanggal_pinjam' => $waktuMulai->format('Y-m-d'),
    //         'waktu_mulai' => $waktuMulai->format('H:i'),
    //         'waktu_selesai' => $waktuSelesai->format('H:i'),
    //         'keperluan' => 'Booking Awal',
    //         'status' => 'disetujui',
    //     ]);

    //     // Booking QR (konflik)
    //     $response = $this->actingAs($this->user)->post('/qr/peminjaman', [
    //         'id_ruang' => $this->ruangan->id_ruang,
    //         'keperluan' => 'Tes Booking QR Konflik',
    //         'duration' => 1,
    //     ]);

    //     $response->assertSessionHasErrors('conflict');
    // }
}
