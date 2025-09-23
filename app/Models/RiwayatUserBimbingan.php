<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatUserBimbingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pembimbing_id',
        'divisi_id',
        'nama_proyek',
        'tanggal_mulai',
        'tanggal_selesai',
        'nilai_akhir',
        'sertifikat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}
