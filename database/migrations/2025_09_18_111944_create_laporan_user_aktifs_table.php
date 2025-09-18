<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('laporan_user_aktifs', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('divisi');
        $table->string('pembimbing');
        $table->string('periode');
        $table->string('proyek');
        $table->integer('kehadiran')->default(0);
        $table->integer('progres')->default(0);
        $table->date('tanggal_mulai');
        $table->timestamps();
    });
}

};
