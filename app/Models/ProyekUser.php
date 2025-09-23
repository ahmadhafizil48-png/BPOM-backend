<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyekUser extends Model
{
    use HasFactory;

    protected $table = 'proyek_users';

    protected $fillable = [
        'user_id',
        'pembimbing_id',
        'divisi_id',
        'nama_proyek',
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
}
