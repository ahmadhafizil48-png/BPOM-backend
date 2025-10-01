<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBimbingan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_bimbingan';

    protected $fillable = [
        'data_bimbingan_id',
        'nilai_akhir',
        'sertifikat',
    ];

    public function dataBimbingan()
    {
        return $this->belongsTo(DataBimbingan::class, 'data_bimbingan_id');
    }
}
