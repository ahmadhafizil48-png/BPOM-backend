<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('riwayat_user_ditolaks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_formulir');
            $table->string('divisi');
            $table->date('tanggal_tolak');
            $table->text('alasan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_user_ditolaks');
    }
};
