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
        Schema::create('riwayat', function (Blueprint $table) {
            $table->id();
            
            // tipe riwayat: admin, user_selesai, user_ditolak
            $table->enum('tipe', ['Admin', 'User_Selesai', 'User_Ditolak']);

            // umum
            $table->date('tanggal')->nullable();
            
            // untuk Admin
            $table->string('admin')->nullable();
            $table->string('aksi')->nullable();

            // untuk User (baik selesai maupun ditolak)
            $table->string('user')->nullable();
            $table->string('divisi')->nullable();
            $table->string('pembimbing')->nullable();
            $table->string('periode')->nullable();
            $table->string('nilai')->nullable();
            $table->string('sertifikat')->nullable();
            $table->date('tanggal_selesai')->nullable();

            // untuk User Ditolak
            $table->string('no_formulir')->nullable();
            $table->date('tanggal_tolak')->nullable();
            $table->text('alasan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat');
    }
};
