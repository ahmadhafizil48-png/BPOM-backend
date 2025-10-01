<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomplainNilai extends Model
{
    use HasFactory;

    protected $table = 'komplain_nilais';

    protected $fillable = [
        'user_id',
        'penilaian_id',
        'proyek',
        'isi_komplain',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penilaian()
    {
        return $this->belongsTo(PenilaianUser::class, 'penilaian_id');
    }
}
