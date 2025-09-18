<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_pelamar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('magang_id'); // relasi ke tabel magang
            $table->string('nama');
            $table->string('nik')->unique();
            $table->string('nim')->unique();
            $table->string('no_hp');
            $table->string('universitas');
            $table->string('alamat_univ')->nullable();
            $table->string('jurusan');
            $table->integer('semester');
            $table->string('divisi_tujuan');
            $table->date('waktu_mulai')->nullable();
            $table->date('waktu_selesai')->nullable();
            $table->string('proposal')->nullable();
            $table->string('surat')->nullable();
            $table->string('status')->default('Belum Diproses');
            $table->timestamps();

            $table->foreign('magang_id')
                ->references('id')
                ->on('magang')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pelamar');
    }
};
