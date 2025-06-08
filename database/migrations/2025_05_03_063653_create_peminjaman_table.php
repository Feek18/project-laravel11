<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_ruang');
            $table->enum('status_peminjaman', ['terencana', 'insidental']);
            $table->text('keperluan');
            $table->string('status_persetujuan')->default('pending');
            $table->date('tanggal_pinjam');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id')->on('penggunas');
            $table->foreign('id_ruang')->references('id_ruang')->on('ruangan_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
