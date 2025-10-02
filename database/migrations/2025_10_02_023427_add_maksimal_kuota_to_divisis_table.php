<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('divisis', function (Blueprint $table) {
            $table->integer('maksimal_kuota')->default(0)->after('kuota');
        });
    }

    public function down(): void
    {
        Schema::table('divisis', function (Blueprint $table) {
            $table->dropColumn('maksimal_kuota');
        });
    }
};
