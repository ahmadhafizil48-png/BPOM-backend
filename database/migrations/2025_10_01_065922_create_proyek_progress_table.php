<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyek_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proyek_user_id'); // relasi ke proyek_user
            $table->text('deskripsi_progress');           // contoh: "Sudah membuat UI Login"
            $table->date('tanggal')->nullable();         // bisa auto pakai created_at juga
            $table->timestamps();

            $table->foreign('proyek_user_id')->references('id')->on('proyek_user')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyek_progress');
    }
};
