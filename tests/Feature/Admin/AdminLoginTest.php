<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Ruang;
use App\Models\Matkul;
use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role 'admin' terlebih dahulu
        Role::create(['name' => 'admin']);

        // Buat user dan berikan role 'admin'
        $admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
        ]);

        $admin->assignRole('admin');
    }

    public function test_login_page_loads_correctly()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function test_login_with_empty_email()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'admin123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_login_with_invalid_email()
    {
        $response = $this->post('/login', [
            'email' => 'invalidemail@gmail.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/'); // default redirect gagal login
        $response->assertSessionHasErrors();
    }

    public function test_login_with_empty_password()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_login_with_invalid_password()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/'); // default redirect gagal login
        $response->assertSessionHasErrors();
    }

    public function test_login_with_invalid_email_and_password()
    {
        $response = $this->post('/login', [
            'email' => 'invalidemail@gmail.com',
            'password' => 'invalid_password',
        ]);

        $response->assertRedirect('/'); // default redirect gagal login
        $response->assertSessionHasErrors();
    }

    public function test_login_successful()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/dashboard'); // Atur sesuai redirect-mu
        $this->assertAuthenticated();
    }
}
