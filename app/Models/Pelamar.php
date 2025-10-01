<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    use HasFactory;

    protected $table = 'pelamars'; // kasih tahu tabel yang dipakai

    protected $fillable = [
        'no_formulir',
        'nama',
        'divisi',
        'tanggal_daftar',
        'status',
    ];
}
