<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logbook_harian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->date('tanggal'); // 1 logbook = 1 tanggal
            $table->string('file_logbook'); // pdf / doc / docx
            $table->enum('status', ['pending', 'verified'])->default('pending');
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'tanggal']); // 1 hari 1 logbook
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa_data')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_harian');
    }
};
