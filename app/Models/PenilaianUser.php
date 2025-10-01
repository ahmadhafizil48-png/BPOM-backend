<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianUser extends Model
{
    use HasFactory;

    protected $table = 'penilaian_users';

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proyekUser()
    {
        return $this->belongsTo(ProyekUser::class, 'proyek_user_id');
    }

    public function komplain()
    {
        return $this->hasMany(KomplainNilai::class, 'penilaian_id');
    }
}
