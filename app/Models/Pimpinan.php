<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pimpinan extends Model
{
    use HasFactory;

    protected $table = 'pimpinans';

    protected $fillable = [
        'nama_pimpinan',
        'jabatan',
        'kantor',         
        'tanda_tangan',   
        'status',
    ];

    // Cast untuk memastikan data type
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Jika ingin tetap menggunakan accessor untuk backward compatibility
    public function getNamaAttribute()
    {
        return $this->nama_pimpinan;
    }

    public function getTtdAttribute()
    {
        return $this->tanda_tangan;
    }
}