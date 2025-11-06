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

            // ✅ Hubungkan dengan tabel users (relasi one-to-many)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            // kalau user dihapus, kolom ini jadi null, tidak error

            // 🧾 Identitas dasar
            $table->string('no_formulir')->unique(); // Contoh: F-20250001
            $table->string('nama');
            $table->string('email')->nullable(); // ✅ tambahkan kolom email untuk user yang mendaftar
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

            // 📊 Status pengajuan
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
