<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembimbings', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('divisi');
            $table->string('no_hp')->nullable();
            $table->string('email')->unique();
            $table->timestamps();
        });

        // Tabel relasi pembimbing - user (bimbingan)
        Schema::create('pembimbing_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembimbing_id')->constrained('pembimbings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembimbing_user');
        Schema::dropIfExists('pembimbings');
    }
};
