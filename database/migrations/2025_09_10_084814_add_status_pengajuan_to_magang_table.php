<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('magang', function (Blueprint $table) {
            $table->enum('status_pengajuan', [
                'belum diproses',
                'sedang diproses',
                'diterima',
                'ditolak'
            ])->default('belum diproses');
        });
    }

    public function down(): void
    {
        Schema::table('magang', function (Blueprint $table) {
            $table->dropColumn('status_pengajuan');
        });
    }
};
