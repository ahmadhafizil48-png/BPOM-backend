<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;

    protected $table = 'riwayat_bimbingan';

    protected $fillable = [
        'tipe',
        'tanggal',
        'admin',
        'aksi',
        'user',
        'divisi',
        'pembimbing',
        'periode',
        'nilai',
        'sertifikat',
        'tanggal_selesai',
        'no_formulir',
        'tanggal_tolak',
        'alasan',
    ];
}
