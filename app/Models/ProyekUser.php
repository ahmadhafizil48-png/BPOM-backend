<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyekUser extends Model
{
    use HasFactory;

    protected $table = 'proyek_user';

    protected $fillable = [
        'user_id',
        'divisi_id',
        'nama_proyek',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianUser::class, 'proyek_user_id');
    }
}
