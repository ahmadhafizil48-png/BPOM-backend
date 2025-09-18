<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_pembimbings', function (Blueprint $table) {
            $table->string('nama')->nullable();
            $table->string('divisi')->nullable();
            $table->integer('jumlah_user')->default(0);
            $table->integer('selesai')->default(0);
            $table->float('rata_nilai')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_pembimbings', function (Blueprint $table) {
            $table->dropColumn(['nama', 'divisi', 'jumlah_user', 'selesai', 'rata_nilai']);
        });
    }
};
