<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'divisi_id',
        'no_hp',
        'email'
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'pembimbing_user');
    }

    // ✅ Tambahan baru — tidak menghapus fungsi lama
    public function user()
    {
        return $this->hasOne(User::class, 'pembimbing_id');
    }
}
