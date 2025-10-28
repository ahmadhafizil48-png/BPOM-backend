<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    /**
     * 🟢 Upload laporan sebelum sertifikat bisa diunduh
     */
    public function uploadLaporan(Request $request, $id)
    {
        $sertifikat = Sertifikat::find($id);

        if (!$sertifikat) {
            return response()->json(['message' => 'Data sertifikat tidak ditemukan'], 404);
        }

        $request->validate([
            'laporan_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // maksimal 5MB
        ]);

        // Upload laporan
        $path = $request->file('laporan_file')->store('laporan', 'public');

        // Simpan path laporan di kolom 'laporan' (sesuai migration)
        $sertifikat->laporan = $path;
        $sertifikat->save();

        return response()->json([
            'message' => 'Laporan berhasil diupload. Sertifikat kini bisa diunduh.',
            'data' => $sertifikat
        ], 200);
    }

    /**
     * 🟢 Download sertifikat (jika laporan sudah diupload)
     */
    public function download($id)
    {
        $sertifikat = Sertifikat::find($id);

        if (!$sertifikat) {
            return response()->json(['message' => 'Data sertifikat tidak ditemukan'], 404);
        }

        // Cek apakah laporan sudah diupload
        if (empty($sertifikat->laporan)) {
            return response()->json(['message' => 'Upload laporan terlebih dahulu sebelum mengunduh sertifikat.'], 403);
        }

        // Pastikan sertifikat ada
        if (!$sertifikat->file || !Storage::disk('public')->exists($sertifikat->file)) {
            return response()->json(['message' => 'File sertifikat belum tersedia.'], 404);
        }

        return response()->download(storage_path('app/public/' . $sertifikat->file));
    }
}
