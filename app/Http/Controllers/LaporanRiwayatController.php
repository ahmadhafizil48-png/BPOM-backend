<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAdmin;
use App\Models\RiwayatUserSelesai;
use App\Models\RiwayatUserDitolak;
use Illuminate\Http\Request;

class LaporanRiwayatController extends Controller
{
    // 🔹 Ambil semua data (admin, selesai, ditolak)
    public function index()
    {
        $riwayatAdmin = RiwayatAdmin::with('admin:id,name')->latest()->get();
        $riwayatSelesai = RiwayatUserSelesai::with(['user:id,name','pembimbing:id,nama'])->latest()->get();
        $riwayatDitolak = RiwayatUserDitolak::latest()->get();

        return response()->json([
            'riwayat_admin' => $riwayatAdmin,
            'riwayat_user_selesai' => $riwayatSelesai,
            'riwayat_user_ditolak' => $riwayatDitolak,
        ]);
    }

    // 🔹 Riwayat admin saja
    public function riwayatAdmin()
    {
        return response()->json(
            RiwayatAdmin::with('admin:id,name')->latest()->get()
        );
    }

    // 🔹 Riwayat user selesai magang
    public function riwayatUserSelesai()
    {
        return response()->json(
            RiwayatUserSelesai::with(['user:id,name','pembimbing:id,nama'])->latest()->get()
        );
    }

    // 🔹 Riwayat user ditolak dengan opsi filter
    public function riwayatUserDitolak(Request $request)
    {
        // Mulai query
        $query = RiwayatUserDitolak::query();

        // Filter berdasarkan tanggal (opsional)
        if ($request->has('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Filter berdasarkan divisi (opsional)
        if ($request->has('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        // Kembalikan hasil
        return response()->json($query->latest()->get());
    }

    // 🔹 Opsi filter
    public function getFilterOptions()
    {
        return response()->json([
            'filter_types' => ['semua', 'tanggal', 'divisi', 'status']
        ]);
    }
}
