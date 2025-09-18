<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatUserDitolak extends Model
{
    use HasFactory;

    protected $table = 'riwayat_user_ditolaks'; // <- pastikan nama tabel sesuai database

    protected $fillable = [
        'nama', 'no_formulir', 'divisi', 'tanggal_tolak', 'alasan'
    ];
}
