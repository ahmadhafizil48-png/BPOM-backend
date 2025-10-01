<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', [
                'Pelamar',
                'User_Aktif',
                'User_Selesai',
                'User_Ditolak',
                'Pembimbing'
            ]);

            // Kolom Laporan Pelamar
            $table->string('nama')->nullable();
            $table->string('universitas')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('no_formulir')->nullable();
            $table->string('divisi')->nullable();
            $table->string('status')->nullable();
            $table->date('tanggal_daftar')->nullable();

            // Kolom Laporan User Aktif
            $table->string('pembimbing')->nullable();
            $table->string('periode')->nullable();
            $table->string('proyek')->nullable();
            $table->string('kehadiran')->nullable(); // ex: 95%
            $table->string('progress')->nullable(); // ex: 80%
            $table->date('tanggal_mulai')->nullable();

            // Kolom Laporan User Selesai
            $table->string('nilai')->nullable();
            $table->string('sertifikat')->nullable();
            $table->date('tanggal_sertifikat')->nullable();
            $table->date('tanggal_selesai')->nullable();

            // Kolom Laporan User Ditolak
            $table->date('tanggal_tolak')->nullable();
            $table->string('alasan')->nullable();

            // Kolom Laporan Pembimbing
            $table->integer('jumlah_user')->nullable();
            $table->integer('selesai')->nullable();
            $table->string('rata_nilai')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
