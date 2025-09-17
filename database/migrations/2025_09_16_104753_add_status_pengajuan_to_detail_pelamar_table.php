<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pelamar', function (Blueprint $table) {
            $table->string('status_pengajuan')->default('belum diproses')->after('semester');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pelamar', function (Blueprint $table) {
            $table->dropColumn('status_pengajuan');
        });
    }
};
