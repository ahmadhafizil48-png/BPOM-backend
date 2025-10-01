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
        Schema::create('data_bimbingan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');        // mahasiswa
            $table->unsignedBigInteger('pembimbing_id');  // pembimbing
            $table->unsignedBigInteger('divisi_id');      // divisi
            $table->string('proyek');
            $table->string('status_proyek')->default('Belum mulai');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();

            // foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pembimbing_id')->references('id')->on('pembimbings')->onDelete('cascade');
            $table->foreign('divisi_id')->references('id')->on('divisis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_bimbingan');
    }
};
