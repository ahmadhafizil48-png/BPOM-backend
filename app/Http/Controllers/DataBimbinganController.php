<?php

namespace App\Http\Controllers;

use App\Models\DataBimbingan;
use Illuminate\Http\Request;

class DataBimbinganController extends Controller
{
    // GET semua data user bimbingan untuk pembimbing
    public function index(Request $request)
    {
        $pembimbingId = $request->user()->id; // asumsi login pembimbing

        $data = DataBimbingan::with(['user','divisi'])
            ->where('pembimbing_id', $pembimbingId)
            ->get()
            ->map(function ($item) {
                return [
                    'nama'          => $item->user->name,
                    'divisi'        => $item->divisi->nama,
                    'periode'       => $item->tanggal_mulai . ' - ' . $item->tanggal_selesai,
                    'proyek'        => $item->proyek,
                    'status_proyek' => $item->status_proyek,
                ];
            });

        return response()->json([
            'message' => 'Data User Bimbingan',
            'data'    => $data
        ]);
    }

    // GET detail satu user bimbingan
    public function show($id)
    {
        $item = DataBimbingan::with(['user','divisi'])->findOrFail($id);

        return response()->json([
            'message' => 'Detail Data Bimbingan',
            'data'    => $item
        ]);
    }
}
