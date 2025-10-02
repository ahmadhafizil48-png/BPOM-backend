<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // konsisten dengan model
            $table->foreignId('proyek_user_id')->nullable()->constrained('proyek_user')->onDelete('set null');

            // skor (0-100)
            $table->unsignedTinyInteger('kehadiran')->nullable();
            $table->unsignedTinyInteger('taat_jadwal')->nullable();
            $table->unsignedTinyInteger('penguasaan_materi')->nullable();
            $table->unsignedTinyInteger('praktek_kerja')->nullable();
            $table->unsignedTinyInteger('inisiatif')->nullable();
            $table->unsignedTinyInteger('komunikasi')->nullable();
            $table->unsignedTinyInteger('laporan_kerja')->nullable();
            $table->unsignedTinyInteger('presentasi')->nullable();

            $table->decimal('nilai_total', 6, 2)->nullable();
            $table->decimal('nilai_rata', 5, 2)->nullable();

            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'proyek_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_user');
    }
};
