<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom pembimbing_id jika belum ada
            if (!Schema::hasColumn('users', 'pembimbing_id')) {
                $table->unsignedBigInteger('pembimbing_id')->nullable()->after('id');

                // Set foreign key ke tabel pembimbings
                $table->foreign('pembimbing_id')->references('id')->on('pembimbings')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'pembimbing_id')) {
                $table->dropForeign(['pembimbing_id']); // hapus foreign key
                $table->dropColumn('pembimbing_id');    // hapus kolom
            }
        });
    }
};
