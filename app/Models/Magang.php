<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magang extends Model
{
    use HasFactory;

    // kalau tabel plural (magangs), ini bisa dihapus karena Laravel otomatis tahu
    // protected $table = 'magangs';

    protected $fillable = [
        'nama',
        'nim',
        'jurusan',
        'universitas',
        'email',
        'no_hp',
        'alamat',
    ];
}
