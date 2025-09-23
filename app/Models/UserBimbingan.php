<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBimbingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pembimbing_id',
        'divisi_id',
        'proyek_id',
        'status',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Pembimbing
    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }

    // Relasi ke Divisi
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    // Relasi ke Proyek
    public function proyek()
    {
        return $this->belongsTo(ProyekUser::class, 'proyek_id');
    }
}
