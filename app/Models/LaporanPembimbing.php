<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPembimbing extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'divisi',
        'jumlah_user',
        'selesai',
        'rata_nilai',
    ];
}
