<?php

namespace Tests\Feature\Admin;

use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Matkul;
use App\Models\Ruang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class JadwalTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);

        $this->admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
        ]);
        $this->admin->assignRole('admin');
    }

    public function test_admin_dapat_menambahkan_jadwal()
    {
        // $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin);

        $ruang = Ruangan::factory()->create();
        $matkul = MataKuliah::factory()->create();

        $response = $this->post('/jadwal', [
            'id_ruang' => $ruang->id_ruang,
            'id_matkul' => $matkul->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
        ]);

        $response->assertRedirect(); // pastikan redirect (302)
        \Log::info($ruang->id_ruang);
        \Log::info($matkul->id);
        $this->assertDatabaseHas('jadwals', [
            'id_ruang' => $ruang->id_ruang,
            'id_matkul' => $matkul->id,
            'hari' => 'senin',
        ]);
    }

    public function test_tidak_boleh_tambah_jadwal_tanpa_nama_ruangan()
    {
        $matkul = MataKuliah::factory()->create();

        $response = $this->actingAs($this->admin)->post('/jadwal', [
            'id_matkul' => $matkul->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
        ]);

        $response->assertSessionHasErrors('id_ruang');
    }

    public function test_jam_selesai_tidak_boleh_kurang_dari_jam_mulai()
    {
        $ruang = Ruangan::factory()->create();
        $matkul = MataKuliah::factory()->create();

        $response = $this->actingAs($this->admin)->post('/jadwal', [
            'id_ruang' => $ruang->id_ruang,
            'id_matkul' => $matkul->id,
            'hari' => 'senin',
            'jam_mulai' => '10:00',
            'jam_selesai' => '08:00',
        ]);

        $response->assertSessionHasErrors(); // Tergantung validasi jam
    }

    public function test_tidak_boleh_jadwal_tabrakan_di_ruangan_dan_hari_yang_sama()
    {
        $ruang = Ruangan::factory()->create();
        $matkul1 = MataKuliah::factory()->create();
        $matkul2 = MataKuliah::factory()->create();

        // Jadwal pertama
        Jadwal::create([
            'id_ruang' => $ruang->id_ruang,
            'id_matkul' => $matkul1->id,
            'hari' => 'Selasa',
            'jam_mulai' => '09:00',
            'jam_selesai' => '11:00',
        ]);

        // Tambah jadwal konflik
        $response = $this->actingAs($this->admin)->post('/jadwal', [
            'id_ruang' => $ruang->id,
            'id_matkul' => $matkul2->id,
            'hari' => 'Selasa',
            'jam_mulai' => '10:30',
            'jam_selesai' => '12:00',
        ]);

        $response->assertSessionHasErrors(); // Validasi konflik
    }

    public function test_admin_dapat_mengupdate_jadwal()
    {
        $jadwal = Jadwal::factory()->create();
        $ruang = Ruangan::factory()->create();
        $matkul = MataKuliah::factory()->create();

        $response = $this->actingAs($this->admin)->put("/jadwal/{$jadwal->id}", [
            'id_ruang' => $ruang->id_ruang,
            'id_matkul' => $matkul->id,
            'hari' => 'kamis',
            'jam_mulai' => '13:00',
            'jam_selesai' => '15:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jadwals', [
            'id' => $jadwal->id,
            'hari' => 'Kamis',
        ]);
    }

    public function test_admin_dapat_menghapus_jadwal()
    {
        $jadwal = Jadwal::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/jadwal/{$jadwal->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('jadwals', ['id' => $jadwal->id]);
    }
}
