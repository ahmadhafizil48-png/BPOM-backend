<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magang extends Model
{
    use HasFactory;

    protected $table = 'magang';

    protected $fillable = [
        'no_formulir',
        'nama',
        'nik',
        'nim',
        'no_hp',
        'universitas',
        'alamat_universitas',
        'jurusan',
        'semester',
        'divisi_tujuan',
        'waktu_mulai',
        'waktu_selesai',
        'proposal',
        'surat_permohonan',
        'status_pengajuan',
    ];
}
