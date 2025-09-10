<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magang', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik')->unique();
            $table->string('nim')->nullable();
            $table->string('no_hp');

            // Pendidikan
            $table->string('universitas');
            $table->string('alamat_universitas')->nullable();
            $table->string('jurusan');
            $table->string('semester');

            // Detail magang
            $table->string('divisi_tujuan');
            $table->date('waktu_mulai')->nullable();
            $table->date('waktu_selesai')->nullable();

            // File (path)
            $table->string('proposal')->nullable();
            $table->string('surat_permohonan')->nullable();

            $table->enum('status_pengajuan', ['belum diproses','sedang diproses','diterima','ditolak'])->default('belum diproses');


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magang');
    }
};
