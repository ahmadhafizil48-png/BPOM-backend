<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // ✅ Status user (aktif / nonaktif)
            $table->boolean('is_active')->default(1);

            // ✅ Role user (admin / pembimbing / mahasiswa)
            $table->unsignedBigInteger('role_id')->nullable();

            // ✅ Jika nanti ada relasi dengan pembimbing
            $table->unsignedBigInteger('pembimbing_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
