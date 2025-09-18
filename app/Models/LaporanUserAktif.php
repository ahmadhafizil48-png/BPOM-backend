<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanUserAktif extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'divisi',
        'pembimbing',
        'periode',
        'proyek',
        'kehadiran',
        'progres',
        'tanggal_mulai',
    ];
}
