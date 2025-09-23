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
    Schema::table('daftar_akun', function (Blueprint $table) {
        if (!Schema::hasColumn('daftar_akun', 'email')) {
            $table->string('email')->unique()->after('username');
        }
    });
}

public function down()
{
    Schema::table('daftar_akun', function (Blueprint $table) {
        $table->dropColumn('email');
    });
}


};
