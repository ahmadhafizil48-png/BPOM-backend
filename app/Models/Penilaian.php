<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'divisi_id',
        'proyek_id',
        'periode',
        'nilai',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function proyek()
    {
        return $this->belongsTo(ProyekUser::class, 'proyek_id');
    }
}
