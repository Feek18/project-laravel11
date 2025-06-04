<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        $user = User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
        ]);
        $user->assignRole('admin');

        Pengguna::factory()->count(10)->create();
    }
}
