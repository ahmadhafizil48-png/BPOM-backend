<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAdmin;
use App\Models\RiwayatUserSelesai;
use App\Models\RiwayatUserDitolak;
use App\Models\Magang;
use Illuminate\Http\Request;

class LaporanRiwayatController extends Controller
{
    // 🔹 Ambil semua data (admin, selesai, ditolak, pelamar)
    public function index()
    {
        $riwayatAdmin = RiwayatAdmin::with('admin:id,name')->latest()->get();
        $riwayatSelesai = RiwayatUserSelesai::with(['user:id,name', 'pembimbing:id,nama'])->latest()->get();
        $riwayatDitolak = RiwayatUserDitolak::latest()->get();
        $riwayatPelamar = Magang::select(
            'nama',
            'universitas',
            'jurusan',
            'id as no_formulir',
            'divisi_tujuan as divisi',
            'status_pengajuan as status',
            'created_at as tanggal_daftar'
        )->latest()->get();

        return response()->json([
            'riwayat_admin' => $riwayatAdmin,
            'riwayat_user_selesai' => $riwayatSelesai,
            'riwayat_user_ditolak' => $riwayatDitolak,
            'riwayat_pelamar' => $riwayatPelamar,
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
            RiwayatUserSelesai::with(['user:id,name', 'pembimbing:id,nama'])->latest()->get()
        );
    }

    // 🔹 Riwayat user ditolak dengan opsi filter
    public function riwayatUserDitolak(Request $request)
    {
        $query = RiwayatUserDitolak::query();

        // Filter berdasarkan tanggal (opsional)
        if ($request->has('tanggal')) {
            $query->whereDate('tanggal_tolak', $request->tanggal);
        }

        // Filter berdasarkan divisi (opsional)
        if ($request->has('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        return response()->json($query->latest()->get());
    }

    // 🔹 Riwayat semua pelamar
    public function riwayatPelamar()
    {
        $riwayatPelamar = Magang::select(
            'nama',
            'universitas',
            'jurusan',
            'id as no_formulir',
            'divisi_tujuan as divisi',
            'status_pengajuan as status',
            'created_at as tanggal_daftar'
        )->latest()->get();

        return response()->json($riwayatPelamar);
    }

    // 🔹 Opsi filter
    public function getFilterOptions()
    {
        return response()->json([
            'filter_types' => ['semua', 'tanggal', 'divisi', 'status']
        ]);
    }
}
