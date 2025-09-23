<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komplain_nilai', function (Blueprint $table) {
            $table->id('id_komplain');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('divisi', 100);
            $table->string('proyek', 150);
            $table->text('isi_komplain');
            $table->dateTime('tanggal')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('status', ['Pending', 'Setuju', 'Tolak'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komplain_nilai');
    }
};
