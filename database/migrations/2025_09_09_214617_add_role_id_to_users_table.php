<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom role_id dan foreign key
            $table->unsignedBigInteger('role_id')->after('email')->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            
            // Drop kolom role string lama (kalau ada)
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            
            // Kembalikan kolom role string
            $table->string('role')->default('user');
        });
    }
};