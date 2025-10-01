<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyek_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');      // relasi ke user (pelamar yg diterima)
            $table->unsignedBigInteger('divisi_id');    // ambil dari tabel divisis
            $table->string('nama_proyek')->nullable();  // contoh: Sistem Absensi
            $table->enum('status', ['belum_mulai', 'berjalan', 'selesai'])->default('belum_mulai');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('divisi_id')->references('id')->on('divisis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyek_user');
    }
};
