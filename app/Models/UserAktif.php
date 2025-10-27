<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAktif extends Model
{
    use HasFactory;

    protected $table = 'user_aktif';

    protected $fillable = [
        'user_id',
        'divisi_id',
        'pembimbing_id',
        'status_akun',
        'is_active',
    ];

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke tabel divisis
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    /**
     * Relasi ke tabel pembimbings
     */
    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }

    /**
     * Relasi ke tabel formulir
     * Menghubungkan user_aktif.user_id dengan formulir.user_id
     */
    public function formulir()
    {
        return $this->hasOne(Formulir::class, 'user_id', 'user_id');
    }
}
