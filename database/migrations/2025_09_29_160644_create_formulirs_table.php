<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formulir', function (Blueprint $table) {
            $table->id();

            // ❌ Hapus constraint biar gak error
            // ✅ Simpan sebagai kolom biasa dulu
            $table->unsignedBigInteger('user_id')->nullable(); 

            $table->string('no_formulir')->unique(); // Contoh: F-20250001
            $table->string('nama');
            $table->string('nik')->unique();
            $table->string('nim')->nullable();
            $table->string('no_hp');

            // 🏫 Pendidikan
            $table->string('universitas');
            $table->string('alamat_universitas')->nullable();
            $table->string('jurusan');
            $table->string('semester');

            // 💼 Detail magang
            $table->string('divisi_tujuan');
            $table->date('waktu_mulai')->nullable();
            $table->date('waktu_selesai')->nullable();

            // 📎 File upload
            $table->string('proposal')->nullable();
            $table->string('surat_permohonan')->nullable();

            // 📊 Status magang
            $table->enum('status_pengajuan', [
                'belum diproses',
                'sedang diproses',
                'diterima',
                'ditolak'
            ])->default('belum diproses');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulir');
    }
};