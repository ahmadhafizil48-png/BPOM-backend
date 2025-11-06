<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulir extends Model
{
    use HasFactory;

    protected $table = 'formulir';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'user_id',              // Relasi ke tabel users
        'no_formulir',
        'nama',
        'email',                // ✅ Tambahan kolom email biar user isi langsung
        'nik',
        'nim',
        'no_hp',
        'universitas',
        'alamat_universitas',
        'jurusan',
        'semester',
        'divisi_tujuan',        // divisi tetap dari user
        'waktu_mulai',
        'waktu_selesai',
        'proposal',
        'surat_permohonan',
        'status_pengajuan',
        'pembimbing_id',        // ✅ Tambahan untuk admin set pembimbing
    ];

    protected $casts = [
        'waktu_mulai'   => 'date',
        'waktu_selesai' => 'date',
    ];

    /**
     * 🔹 Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 🔹 Relasi ke pembimbing (admin bisa set ini)
     */
    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }

    /**
     * 🔹 Relasi ke user_aktif (optional)
     */
    public function userAktif()
    {
        return $this->hasOne(UserAktif::class, 'user_id', 'user_id');
    }

    /**
     * Boot model: generate no_formulir otomatis dan status default
     */
    protected static function booted()
    {
        static::creating(function (Formulir $formulir) {
            // Generate otomatis no_formulir
            if (empty($formulir->no_formulir)) {
                $year = now()->format('Y');
                $lastForYear = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
                $nextId = $lastForYear ? ($lastForYear->id + 1) : 1;
                $seq = str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
                $formulir->no_formulir = "F-{$year}-{$seq}";
            }

            // Set status default
            if (empty($formulir->status_pengajuan)) {
                $formulir->status_pengajuan = 'belum diproses';
            }
        });
    }
}
