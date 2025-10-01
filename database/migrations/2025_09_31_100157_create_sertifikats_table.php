<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelamar_id');   // relasi ke pelamar
            $table->unsignedBigInteger('divisi_id');    // relasi ke divisi
            $table->date('tanggal_selesai');            // kolom sesuai gambar
            $table->enum('status', ['Belum Dibuat', 'Sudah Dibuat'])->default('Belum Dibuat');
            $table->string('file')->nullable();         // file sertifikat (optional)
            $table->timestamps();

            // Foreign keys
            $table->foreign('pelamar_id')->references('id')->on('pelamars')->onDelete('cascade');
            $table->foreign('divisi_id')->references('id')->on('divisis')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sertifikats');
    }
};
