<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('riwayat_user_selesais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('divisi')->nullable();
            $table->foreignId('pembimbing_id')->nullable()->constrained('pembimbings')->nullOnDelete();
            $table->string('periode')->nullable();
            $table->string('nilai')->nullable();
            $table->string('sertifikat')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_user_selesais');
    }
};
