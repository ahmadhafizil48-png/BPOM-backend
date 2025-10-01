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
        'no_formulir',
        'nama',
        'nik',
        'nim',
        'no_hp',
        'universitas',
        'alamat_universitas',
        'jurusan',
        'semester',
        'divisi_tujuan',
        'waktu_mulai',
        'waktu_selesai',
        'proposal',
        'surat_permohonan',
        'status_pengajuan',
    ];

    protected $casts = [
        'waktu_mulai'   => 'date',
        'waktu_selesai' => 'date',
    ];

    /**
     * Boot model untuk auto-generate no_formulir dan default status.
     * Format no_formulir: F-YYYY-XXXX (contoh: F-2025-0001)
     */
    protected static function booted()
    {
        static::creating(function (Formulir $formulir) {
            // Auto generate no_formulir jika kosong
            if (empty($formulir->no_formulir)) {
                $year = now()->format('Y');
                // Dapatkan nomor urut berdasarkan total rows tahun ini
                $lastForYear = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
                $nextId = $lastForYear ? ($lastForYear->id + 1) : 1;
                $seq = str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
                $formulir->no_formulir = "F-{$year}-{$seq}";
            }

            // Default status jika tidak dikirim
            if (empty($formulir->status_pengajuan)) {
                $formulir->status_pengajuan = 'belum diproses';
            }
        });
    }
}
