<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPelamar extends Model
{
    use HasFactory;

    protected $table = 'detail_pelamar';

    // 🔓 semua kolom bisa diisi mass assignment
    protected $guarded = [];

    /*
    // 🔒 kalau mau lebih aman, pakai fillable (pilih salah satu, bukan dua-duanya)
    protected $fillable = [
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
    */
}
