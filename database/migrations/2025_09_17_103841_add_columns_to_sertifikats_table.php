<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sertifikats', function (Blueprint $table) {
            $table->unsignedBigInteger('detail_pelamar_id')->nullable();
            $table->string('nomor_sertifikat')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->unsignedBigInteger('pimpinan_id')->nullable();
            $table->string('file')->nullable();

            // optional foreign key
            $table->foreign('detail_pelamar_id')->references('id')->on('detail_pelamar')->onDelete('set null');
            $table->foreign('pimpinan_id')->references('id')->on('pimpinans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('sertifikats', function (Blueprint $table) {
            $table->dropForeign(['detail_pelamar_id']);
            $table->dropForeign(['pimpinan_id']);
            $table->dropColumn(['detail_pelamar_id', 'nomor_sertifikat', 'tanggal_terbit', 'pimpinan_id', 'file']);
        });
    }
};
