<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyek_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pembimbing_id')->nullable()->constrained('pembimbings')->onDelete('set null');
            $table->foreignId('divisi_id')->nullable()->constrained('divisis')->onDelete('set null');
            $table->string('nama_proyek')->nullable();
            $table->enum('status', ['belum_mulai', 'berjalan', 'selesai'])->default('belum_mulai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyek_users');
    }
};
