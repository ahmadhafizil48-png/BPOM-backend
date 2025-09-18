<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // nama peserta / pemegang sertifikat
            $table->unsignedBigInteger('divisi_id')->nullable(); // relasi ke divisi
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['pending', 'selesai', 'batal'])->default('pending');
            $table->timestamps();

            $table->foreign('divisi_id')->references('id')->on('divisis')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
