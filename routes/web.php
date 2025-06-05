<?php

use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\MatkulController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\PemesananController;
use App\Http\Controllers\User\RuanganController;
use App\Http\Controllers\User\UsersController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

// homepage
Route::get('/', [HomeController::class, 'index']);

Route::get('/ruangan/{id}', [HomeController::class, 'show'])->name('ruangan.show');

// Route::get('/dashboard', function () {
//     return view('components.admin.dashboard');
// })->middleware(['auth', 'role:admin'])->name('dashboard');

Route::get('/peminjam', function () {
    return view('components.admin.peminjam');
})->middleware(['auth'])->name('peminjam');

Route::middleware('auth')->group(function () {

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('components.admin.dashboard');
        })->name('dashboard');

        // jadwal
        Route::resource('jadwal', JadwalController::class);
        // Matkul
        Route::resource('matkul', MatkulController::class);
        // Ruangan
        Route::resource('ruangan', RoomController::class);
        // Pengguna
        Route::resource('pengguna', UserController::class);
        // Akun
        Route::resource('akun', AkunController::class);
    });

    Route::middleware(['role:pengguna'])->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::resource('users', UsersController::class);

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

});
