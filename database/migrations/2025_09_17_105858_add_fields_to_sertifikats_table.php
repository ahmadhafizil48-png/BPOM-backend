<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sertifikats', function (Blueprint $table) {
            if (!Schema::hasColumn('sertifikats', 'nama')) {
                $table->string('nama')->after('id');
            }
            if (!Schema::hasColumn('sertifikats', 'divisi_id')) {
                $table->unsignedBigInteger('divisi_id')->nullable()->after('nama');
                $table->foreign('divisi_id')->references('id')->on('divisis')->onDelete('set null');
            }
            if (!Schema::hasColumn('sertifikats', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable()->after('divisi_id');
            }
            if (!Schema::hasColumn('sertifikats', 'status')) {
                $table->enum('status', ['pending','selesai','batal'])->default('pending')->after('tanggal_selesai');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sertifikats', function (Blueprint $table) {
            $table->dropForeign(['divisi_id']);
            $table->dropColumn(['nama','divisi_id','tanggal_selesai','status']);
        });
    }
};
