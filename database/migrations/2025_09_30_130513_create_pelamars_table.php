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
        Schema::create('pelamars', function (Blueprint $table) {
            $table->id(); // ID auto increment
            $table->string('no_formulir')->unique(); // contoh: F001, F002
            $table->string('nama'); // nama pelamar
            $table->string('divisi'); // divisi tujuan
            $table->date('tanggal_daftar'); // tanggal daftar
            $table->string('status')->default('Belum Diproses'); // status (Belum Diproses, Sedang Diproses, Diterima, Ditolak)
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelamars');
    }
};
