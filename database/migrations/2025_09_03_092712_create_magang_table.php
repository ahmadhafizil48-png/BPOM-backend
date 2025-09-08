<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('magangs', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('nim');
    $table->string('jurusan');
    $table->string('universitas');
    $table->string('email')->unique();
    $table->string('no_hp');
    $table->string('alamat');
    $table->timestamps();
});

}
};
