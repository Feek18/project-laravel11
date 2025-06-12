<?php

use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\MatkulController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\User\PemesananController;
use App\Http\Controllers\User\PesananController;
use App\Http\Controllers\User\RuanganController;
use App\Http\Controllers\User\UsersController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

// homepage
Route::get('/', [HomeController::class, 'index']);
Route::get('/ruangan/{id}', [HomeController::class, 'show'])->name('ruangan.show');

// QR Code routes (accessible without auth for scanning)
Route::prefix('qr')->group(function () {
    Route::get('/scan/{token}', [QRCodeController::class, 'scanQR'])->name('qr.scan');
    Route::get('/room/{room_id}', [QRCodeController::class, 'showRoomBorrowForm'])->name('qr.room.borrow');
    Route::post('/room/{room_id}/process', [QRCodeController::class, 'processRoomBorrow'])->name('qr.room.process');
    Route::get('/success/{id}', [QRCodeController::class, 'showSuccess'])->name('qr.success');
    Route::post('/approve/{token}', [QRCodeController::class, 'approveQR'])->name('qr.approve');
    Route::get('/test', function() {
        return view('qr.test');
    })->name('qr.test');
});

// Route::get('/dashboard', function () {
//     return view('components.admin.dashboard');
// })->middleware(['auth', 'role:admin'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
        // Peminjam
        Route::resource('peminjam', PeminjamanController::class);
        Route::put('/peminjam/{id}/persetujuan', [PeminjamanController::class, 'persetujuan'])
            ->name('peminjam.persetujuan');
        
        // QR Code generation for admin
        Route::post('/qr/generate-room', [QRCodeController::class, 'generateRoomQR'])->name('qr.generate.room');
    });

    Route::middleware(['role:pengguna'])->group(function () {
        Route::resource('users', UsersController::class);

        // detail ruangan
        // Route::controller(HomeController::class)->group(function () {
        //     Route::get('/ruangan/{id}', 'show')->name('ruangan.show');
        // });

        // pemesanan untuk pinjam ruangan
        Route::resource('pemesanan', PemesananController::class);

        // pesanan ruangan
        Route::resource('pesanRuangan', PesananController::class);

        // QR Code generation for users
        Route::post('/qr/generate-instant', [QRCodeController::class, 'generateInstantQR'])->name('qr.generate.instant');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
