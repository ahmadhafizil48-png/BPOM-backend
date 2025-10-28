<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    // 🔹 Kolom yang bisa diisi (termasuk lampiran)
    protected $fillable = [
        'user_id',
        'tanggal',
        'aktivitas',
        'jam_mulai',
        'jam_selesai',
        'kendala',
        'catatan',
        'lampiran', // 🆕 tambahkan kolom untuk file
    ];

    // 🔹 Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
