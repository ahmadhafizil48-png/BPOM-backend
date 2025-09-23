<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarAkun extends Model
{
    protected $table = 'daftar_akun';
    public $timestamps = false;

    protected $fillable = [
    'username',
    'email',
    'nama',
    'role',
    'status',
    'password',
];


    protected $hidden = [
        'password', // biar password tidak tampil di JSON response
    ];
}
