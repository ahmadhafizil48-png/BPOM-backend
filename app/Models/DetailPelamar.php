<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPelamar extends Model
{
    use HasFactory;

    protected $table = 'detail_pelamar';

    protected $fillable = [
        'magang_id',
        'nama',
        'nik',
        'nim',
        'no_hp',
        'universitas',
        'alamat_univ',
        'jurusan',
        'semester',
        'divisi_tujuan',
        'waktu_mulai',
        'waktu_selesai',
        'proposal',
        'surat',
        'status',
    ];

    public function magang()
    {
        return $this->belongsTo(Magang::class, 'magang_id');
    }
}
