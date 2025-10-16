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
    Schema::table('user_aktif', function (Blueprint $table) {
        $table->string('nik')->nullable();
        $table->string('nim')->nullable();
        $table->string('nohp')->nullable();
        $table->string('universitas')->nullable();
        $table->string('alamatuniv')->nullable();
        $table->string('jurusan')->nullable();
        $table->string('semester')->nullable();
        $table->string('divisi')->nullable();
        $table->string('pembimbing')->nullable();
        $table->date('tanggalmulai')->nullable();
        $table->date('tanggalselesai')->nullable();
    });
}

public function down(): void
{
    Schema::table('user_aktif', function (Blueprint $table) {
        $table->dropColumn([
            'nik', 'nim', 'nohp', 'universitas', 'alamatuniv',
            'jurusan', 'semester', 'divisi', 'pembimbing',
            'tanggalmulai', 'tanggalselesai'
        ]);
    });
}

};
