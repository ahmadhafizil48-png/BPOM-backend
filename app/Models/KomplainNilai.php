<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomplainNilai extends Model
{
    protected $table = 'komplain_nilai';
    protected $primaryKey = 'id_komplain';
    public $timestamps = true;

    
protected $fillable = [
    'user_id',
    'divisi',
    'proyek',
    'isi_komplain',
    'tanggal',
    'status',
];
}
