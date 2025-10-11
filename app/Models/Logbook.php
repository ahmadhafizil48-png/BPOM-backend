<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $fillable = [
        'user_id', 'tanggal', 'aktivitas', 'jam_mulai', 'jam_selesai', 'kendala', 'catatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

