<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembimbings', function (Blueprint $table) {
            if (!Schema::hasColumn('pembimbings', 'nama')) {
                $table->string('nama')->after('id');
            }
            if (!Schema::hasColumn('pembimbings', 'divisi')) {
                $table->string('divisi')->after('nama');
            }
            if (!Schema::hasColumn('pembimbings', 'no_hp')) {
                $table->string('no_hp')->nullable()->after('divisi');
            }
            if (!Schema::hasColumn('pembimbings', 'email')) {
                $table->string('email')->unique()->after('no_hp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembimbings', function (Blueprint $table) {
            if (Schema::hasColumn('pembimbings', 'nama')) {
                $table->dropColumn('nama');
            }
            if (Schema::hasColumn('pembimbings', 'divisi')) {
                $table->dropColumn('divisi');
            }
            if (Schema::hasColumn('pembimbings', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
            if (Schema::hasColumn('pembimbings', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
