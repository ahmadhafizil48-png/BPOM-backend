<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pimpinans', function (Blueprint $table) {
            $table->string('nama_pimpinan')->after('id');
            $table->string('jabatan');
            $table->string('kantor')->nullable();
            $table->string('tanda_tangan')->nullable();
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
        });
    }

    public function down(): void
    {
        Schema::table('pimpinans', function (Blueprint $table) {
            $table->dropColumn(['nama_pimpinan', 'jabatan', 'kantor', 'tanda_tangan', 'status']);
        });
    }
};
