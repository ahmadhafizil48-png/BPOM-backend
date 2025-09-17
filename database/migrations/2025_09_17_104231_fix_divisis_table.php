<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('divisis')) {
            Schema::table('divisis', function (Blueprint $table) {
                if (!Schema::hasColumn('divisis', 'nama_divisi')) {
                    $table->string('nama_divisi')->nullable();
                }
                if (!Schema::hasColumn('divisis', 'deskripsi')) {
                    $table->text('deskripsi')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('divisis', function (Blueprint $table) {
            $table->dropColumn(['nama_divisi', 'deskripsi']);
        });
    }
};
