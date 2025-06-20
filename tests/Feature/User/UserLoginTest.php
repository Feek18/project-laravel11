<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for testing
        $this->user = User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => bcrypt('123123qa'),
        ]);
    }

    public function test_user_can_login_successfully()
    {
        $response = $this->post('/login', [
            'email' => 'user@gmail.com',
            'password' => '123123qa',
        ]);

        $response->assertRedirect('/profile');
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_user_can_update_profile_successfully()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('dummy.jpg');

        $response = $this->actingAs($this->user)->put('/profile', [
            'nama' => 'New User Name',
            'email' => 'user@gmail.com',
            'no_telp' => '08123456789',
            'alamat' => 'Jalan Baru No. 123',
            'gender' => 'pria',
            'gambar' => $file,
        ]);

        $response->assertSessionHas('success', 'Profile berhasil diupdate');

        $this->assertDatabaseHas('penggunas', [
            'user_id' => $this->user->id,
            'nama' => 'New User Name',
        ]);

        // Pastikan sesuai dengan folder saat store()
        Storage::disk('public')->assertExists('gambar/' . $file->hashName()); // ✅ BENAR

    }

    public function test_update_profile_should_fail_if_required_fields_are_missing()
    {
        $response = $this->actingAs($this->user)->from('/profile')->put('/profile', [
            'nama' => '',
            'email' => '',
            'no_telp' => '',
            'alamat' => '',
            'gender' => '',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHasErrors(['nama', 'email', 'no_telp', 'alamat', 'gender']);
    }

    public function test_update_profile_should_fail_without_image()
    {
        $response = $this->actingAs($this->user)->from('/profile')->put('/profile', [
            'nama' => 'New User Name',
            'email' => 'user@gmail.com',
            'no_telp' => '08123456789',
            'alamat' => 'Jalan Baru No. 123',
            'gender' => 'pria',
            // 'gambar' => missing
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHasErrors('gambar');
    }
}
