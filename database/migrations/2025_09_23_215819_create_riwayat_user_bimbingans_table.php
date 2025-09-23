<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_user_bimbingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pembimbing_id')->nullable()->constrained('pembimbings')->onDelete('set null');
            $table->foreignId('divisi_id')->nullable()->constrained('divisis')->onDelete('set null');
            $table->string('nama_proyek');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('nilai_akhir')->nullable(); // A, B+, dll
            $table->enum('sertifikat', ['sudah_diberikan', 'belum_diberikan'])->default('belum_diberikan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_user_bimbingans');
    }
};
