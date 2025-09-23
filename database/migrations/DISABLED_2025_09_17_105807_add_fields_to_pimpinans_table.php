<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pimpinans', function (Blueprint $table) {
            if (!Schema::hasColumn('pimpinans', 'nama_pimpinan')) {
                $table->string('nama_pimpinan')->after('id');
            }
            if (!Schema::hasColumn('pimpinans', 'jabatan')) {
                $table->string('jabatan')->after('nama_pimpinan');
            }
            if (!Schema::hasColumn('pimpinans', 'kantor')) {
                $table->string('kantor')->nullable()->after('jabatan');
            }
            if (!Schema::hasColumn('pimpinans', 'tanda_tangan')) {
                $table->string('tanda_tangan')->nullable()->after('kantor');
            }
            if (!Schema::hasColumn('pimpinans', 'status')) {
                $table->enum('status', ['aktif', 'non-aktif'])->default('aktif')->after('tanda_tangan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pimpinans', function (Blueprint $table) {
            $table->dropColumn(['nama_pimpinan','jabatan','kantor','tanda_tangan','status']);
        });
    }
};
