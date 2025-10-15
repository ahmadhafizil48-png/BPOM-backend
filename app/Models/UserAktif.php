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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }
}
