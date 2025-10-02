<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomplainNilai extends Model
{
    use HasFactory;

    // Nama tabel sesuai database
    protected $table = 'komplain_nilais';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'user_id',
        'penilaian_id',
        'proyek',
        'isi_komplain',
        'status',
    ];

    /**
     * Relasi ke PenilaianUser
     * Satu komplain terkait satu penilaian
     */
    public function penilaian()
    {
        return $this->belongsTo(PenilaianUser::class, 'penilaian_id');
    }

    /**
     * Relasi ke User
     * Satu komplain dibuat oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
