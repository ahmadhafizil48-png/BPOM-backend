<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataBimbingan extends Model
{
    protected $table = 'data_bimbingan';
    protected $fillable = [
        'user_id',
        'pembimbing_id',
        'divisi_id',
        'proyek',
        'status_proyek',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}
