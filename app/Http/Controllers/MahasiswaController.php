<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MahasiswaData;
use App\Models\FormulirMagang;
use App\Models\LaporanAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    /**
     * GET — Profil Mahasiswa
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load([
            'mahasiswaData.pembimbing.user',
            'mahasiswaData.formulir',
        ]);

        return response()->json([
            'status' => true,
            'data'   => [
                'user'       => $user,
                'mahasiswa'  => $user->mahasiswaData,
                'pembimbing' => $user->mahasiswaData->pembimbing->user ?? null,
                'formulir'   => $user->mahasiswaData->formulir ?? null,
            ]
        ]);
    }


    /* ============================================================
     |                        LAPORAN AKHIR
     ============================================================ */

    public function showLaporanAkhir(Request $request)
    {
        $mhs = $request->user()->mahasiswaData;

        $laporan = LaporanAkhir::where('mahasiswa_id', $mhs->id)->first();

        return response()->json([
            'status' => true,
            'file'   => $laporan ? $laporan->file_laporan : null,
            'status_laporan' => $laporan->status ?? 'pending'
        ]);
    }


    public function uploadLaporanAkhir(Request $request)
    {
        $mhs = $request->user()->mahasiswaData;

        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx|max:8000'
        ]);

        // Cek apakah sudah ada laporan akhir
        $laporan = LaporanAkhir::where('mahasiswa_id', $mhs->id)->first();

        // Jika ada → hapus file lama
        if ($laporan) {
            Storage::disk('public')->delete($laporan->file_laporan);
        }

        // Upload file baru
        $path = $request->file('file')->store('laporan-akhir', 'public');

        // Create or Update
        $laporan = LaporanAkhir::updateOrCreate(
            ['mahasiswa_id' => $mhs->id],
            ['file_laporan' => $path, 'status' => 'pending']
        );

        return response()->json([
            'status'  => true,
            'message' => 'Laporan akhir berhasil di-upload.',
            'file'    => $path
        ]);
    }


    public function deleteLaporanAkhir(Request $request)
    {
        $mhs = $request->user()->mahasiswaData;

        $laporan = LaporanAkhir::where('mahasiswa_id', $mhs->id)->first();

        if ($laporan) {
            Storage::disk('public')->delete($laporan->file_laporan);
            $laporan->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Laporan akhir berhasil dihapus.'
        ]);
    }


    /* ============================================================
     |                     PROGRESS TRACKING
     ============================================================ */

    public function progress(Request $request)
    {
        $mhs = $request->user()->mahasiswaData;
        $form = $mhs->formulir;

        if (!$form) {
            return response()->json([
                'status' => true,
                'progress' => 0,
                'detail' => []
            ]);
        }

        $mulai = strtotime($form->waktu_mulai);
        $selesai = strtotime($form->waktu_selesai);
        $now = time();

        if ($now < $mulai) $now = $mulai;
        if ($now > $selesai) $now = $selesai;

        $progress = round((($now - $mulai) / ($selesai - $mulai)) * 100);

        return response()->json([
            'status' => true,
            'progress' => $progress,
            'tanggal_mulai' => $form->waktu_mulai,
            'tanggal_selesai' => $form->waktu_selesai
        ]);
    }
}
