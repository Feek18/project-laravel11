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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ruang');
            $table->unsignedBigInteger('id_matkul');
            $table->string('nama_perkuliahan');
            $table->enum('hari', ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu']);
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
            $table->foreign('id_ruang')->references('id_ruang')->on('ruangan_kelas')->onDelete('cascade');
            $table->foreign('id_matkul')->references('id')->on('mata_kuliah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
