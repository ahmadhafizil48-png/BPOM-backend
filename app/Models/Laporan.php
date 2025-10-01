<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = [
        'tipe',
        'nama',
        'universitas',
        'jurusan',
        'no_formulir',
        'divisi',
        'status',
        'tanggal_daftar',
        'pembimbing',
        'periode',
        'proyek',
        'kehadiran',
        'progress',
        'tanggal_mulai',
        'nilai',
        'sertifikat',
        'tanggal_sertifikat',
        'tanggal_selesai',
        'tanggal_tolak',
        'alasan',
        'jumlah_user',
        'selesai',
        'rata_nilai'
    ];
}
