<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komplain_nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // optional link ke penilaian yang dikomplain
            $table->foreignId('penilaian_id')->nullable()->constrained('penilaian_user')->onDelete('set null');

            $table->string('proyek')->nullable();
            $table->text('isi_komplain');
            $table->enum('status', ['Pending', 'Setuju', 'Tolak'])->default('Pending');

            $table->timestamps();
            $table->index(['user_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komplain_nilais');
    }
};
