<?php

namespace App\Http\Controllers;

use App\Models\RiwayatBimbingan;
use Illuminate\Http\Request;

class RiwayatBimbinganController extends Controller
{
    public function index()
    {
        $riwayat = RiwayatBimbingan::with(['dataBimbingan.user', 'dataBimbingan.divisi'])
            ->get();

        return response()->json([
            'message' => 'Riwayat User Bimbingan',
            'data' => $riwayat
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_bimbingan_id' => 'required|exists:data_bimbingan,id',
            'nilai_akhir' => 'required|string',
            'sertifikat' => 'required|in:Sudah diberikan,Belum diberikan',
        ]);

        $riwayat = RiwayatBimbingan::create($request->all());

        return response()->json([
            'message' => 'Riwayat bimbingan berhasil ditambahkan',
            'data' => $riwayat
        ]);
    }
}
