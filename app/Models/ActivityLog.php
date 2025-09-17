<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'admin_name',
        'action',
        'table_name',
        'description',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter berdasarkan tabel
    public function scopeFilterByTable($query, $tableName)
    {
        if ($tableName && $tableName !== 'semua') {
            return $query->where('table_name', $tableName);
        }
        return $query;
    }

    // Scope untuk filter berdasarkan tipe aksi
    public function scopeFilterByAction($query, $actionType)
    {
        if ($actionType && $actionType !== 'semua') {
            return $query->where('action', $actionType);
        }
        return $query;
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeFilterByDate($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        return $query;
    }
}