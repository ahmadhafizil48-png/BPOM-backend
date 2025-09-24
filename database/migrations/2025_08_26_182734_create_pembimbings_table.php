<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel pembimbings
        Schema::create('pembimbings', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('divisi');
            $table->string('no_hp')->nullable();
            $table->string('email')->unique();
            $table->timestamps();
        });

        // Buat tabel pivot pembimbing-user
        Schema::create('pembimbing_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembimbing_id')->constrained('pembimbings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Tambah relasi langsung di tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pembimbing_id')) {
                $table->unsignedBigInteger('pembimbing_id')->nullable()->after('id');
                $table->foreign('pembimbing_id')->references('id')->on('pembimbings')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        // Drop foreign key dari users
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'pembimbing_id')) {
                $table->dropForeign(['pembimbing_id']);
                $table->dropColumn('pembimbing_id');
            }
        });

        Schema::dropIfExists('pembimbing_user');
        Schema::dropIfExists('pembimbings');
    }
};
