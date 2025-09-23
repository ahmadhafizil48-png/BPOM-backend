<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');      // peserta magang
            $table->unsignedBigInteger('divisi_id');    // divisi
            $table->unsignedBigInteger('proyek_id');    // proyek
            $table->string('periode');                  // misal: "10 Sep 2025 - 10 Des 2025"
            $table->string('nilai')->nullable();        // contoh: A, B+, C
            $table->text('catatan')->nullable();        // opsional: catatan pembimbing
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('divisi_id')->references('id')->on('divisis')->onDelete('cascade');
            $table->foreign('proyek_id')->references('id')->on('proyek_users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
