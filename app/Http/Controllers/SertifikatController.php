<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    public function index()
    {
        return response()->json(
            Sertifikat::with(['pelamar', 'pimpinan'])->get(),
            200
        );
    }

    public function show($id)
    {
        $sertifikat = Sertifikat::with(['pelamar', 'pimpinan'])->findOrFail($id);
        return response()->json($sertifikat, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'detail_pelamar_id' => 'required|exists:detail_pelamar,id',
            'nomor_sertifikat' => 'required|string|unique:sertifikats',
            'tanggal_terbit' => 'required|date',
            'pimpinan_id' => 'required|exists:pimpinans,id',
            'file' => 'nullable|string',
        ]);

        $sertifikat = Sertifikat::create($validated);

        return response()->json($sertifikat, 201);
    }

    public function update(Request $request, $id)
    {
        $sertifikat = Sertifikat::findOrFail($id);

        $sertifikat->update($request->all());

        return response()->json($sertifikat, 200);
    }

    public function destroy($id)
    {
        $sertifikat = Sertifikat::findOrFail($id);
        $sertifikat->delete();

        return response()->json(null, 204);
    }
}
