<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use Illuminate\Http\Request;

class PelamarController extends Controller
{
    /**
     * Tampilkan semua pelamar
     */
    public function index()
    {
        $pelamar = Pelamar::orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Daftar pelamar',
            'data'    => $pelamar,
        ]);
    }

    /**
     * Tampilkan detail pelamar berdasarkan ID
     */
    public function show($id)
    {
        $pelamar = Pelamar::find($id);

        if (!$pelamar) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail pelamar',
            'data'    => $pelamar,
        ]);
    }
}
