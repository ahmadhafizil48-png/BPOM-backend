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
        Schema::create('pimpinan', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('pimpinan', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('jabatan');
    $table->string('kantor');
    $table->string('tanda_tangan'); // bisa simpan path file tanda tangan
    $table->enum('status', ['Aktif', 'Nonaktif'])->default('Nonaktif');
    $table->timestamps();
});

    }
};
