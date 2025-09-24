<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daftar_akun', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique(); // gabungan dari add email
            $table->string('nama');
            $table->string('password');        // gabungan dari add password
            $table->enum('role', ['Admin', 'Pembimbing', 'User']);
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daftar_akun');
    }
};
