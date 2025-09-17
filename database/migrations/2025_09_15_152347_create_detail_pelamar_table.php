<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pelamar', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel magang
            $table->unsignedBigInteger('magang_id');
            $table->foreign('magang_id')->references('id')->on('magang')->onDelete('cascade');

            // Kolom tambahan untuk detail pelamar
            $table->text('catatan')->nullable(); // contoh tambahan
            $table->string('status_verifikasi')->default('belum diverifikasi');
            $table->string('reviewer')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pelamar');
    }
};
