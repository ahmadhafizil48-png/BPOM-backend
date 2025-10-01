<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_bimbingan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_bimbingan_id'); // relasi ke data_bimbingan
            $table->string('nilai_akhir')->nullable();       // contoh: A, B+, C
            $table->enum('sertifikat', ['Sudah diberikan', 'Belum diberikan'])->default('Belum diberikan');
            $table->timestamps();

            $table->foreign('data_bimbingan_id')
                  ->references('id')->on('data_bimbingan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_bimbingan');
    }
};
