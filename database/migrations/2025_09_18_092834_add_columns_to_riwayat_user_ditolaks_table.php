<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_user_ditolaks', function (Blueprint $table) {
            $table->string('nama')->after('id');
            $table->string('no_formulir')->after('nama');
            $table->string('divisi')->after('no_formulir');
            $table->date('tanggal_tolak')->after('divisi');
            $table->text('alasan')->after('tanggal_tolak');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_user_ditolaks', function (Blueprint $table) {
            $table->dropColumn(['nama','no_formulir','divisi','tanggal_tolak','alasan']);
        });
    }
};
