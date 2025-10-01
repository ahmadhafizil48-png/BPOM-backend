<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProyekProgress extends Model
{
    use HasFactory;

    protected $table = 'proyek_progress';

    protected $fillable = [
        'proyek_user_id',
        'deskripsi_progress',
        'tanggal',
    ];

    public function proyek()
    {
        return $this->belongsTo(ProyekUser::class, 'proyek_user_id');
    }
}
