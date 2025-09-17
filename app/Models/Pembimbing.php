<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'divisi',
        'no_hp',
        'email',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'pembimbing_id');
    }
}
