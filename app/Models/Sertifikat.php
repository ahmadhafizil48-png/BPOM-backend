<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'sertifikats';

    protected $fillable = [
        'pelamar_id',
        'divisi_id',
        'tanggal_selesai',
        'status',
        'file',
        'laporan', // tambahkan kolom laporan
    ];

    // Relasi ke pelamar
    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }

    // Relasi ke divisi
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
}
