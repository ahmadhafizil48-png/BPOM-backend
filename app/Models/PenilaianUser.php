<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianUser extends Model
{
    use HasFactory;

    protected $table = 'penilaian_user'; // pastikan sesuai dengan nama tabel

    protected $fillable = [
        'user_id',
        'proyek_user_id',
        'kehadiran',
        'taat_jadwal',
        'penguasaan_materi',
        'praktek_kerja',
        'inisiatif',
        'komunikasi',
        'laporan_kerja',
        'presentasi',
        'nilai_total',
        'nilai_rata',
        'catatan',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke ProyekUser
    public function proyekUser()
    {
        return $this->belongsTo(ProyekUser::class, 'proyek_user_id');
    }

    // Relasi ke KomplainNilai
    public function komplain()
    {
        return $this->hasOne(KomplainNilai::class, 'penilaian_id');
    }
}
