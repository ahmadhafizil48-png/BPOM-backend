<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Magang;
use App\Models\DetailPelamar;

class MagangController extends Controller
{
    // 🔹 Simpan data pendaftaran magang
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'nik' => 'required|digits_between:8,20|unique:magang,nik',
                'nim' => 'nullable|digits_between:8,20',
                'no_hp' => 'required|digits_between:10,15',
                'universitas' => 'required|string|max:255',
                'alamat_universitas' => 'nullable|string|max:255',
                'jurusan' => 'required|string|max:100',
                'semester' => 'required|integer|min:1|max:14',
                'divisi_tujuan' => 'required|string|max:100',
                'waktu_mulai' => 'nullable|date',
                'waktu_selesai' => 'nullable|date|after_or_equal:waktu_mulai',
                'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'surat_permohonan' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'status_pengajuan' => 'nullable|in:belum diproses,sedang diproses,diterima,ditolak'
            ]);

            // Simpan file kalau ada
            if ($request->hasFile('proposal')) {
                $validated['proposal'] = $request->file('proposal')->store('proposal', 'public');
            }
            if ($request->hasFile('surat_permohonan')) {
                $validated['surat_permohonan'] = $request->file('surat_permohonan')->store('surat', 'public');
            }

            // Set status default
            $validated['status_pengajuan'] = $validated['status_pengajuan'] ?? 'belum diproses';

            // Simpan ke tabel magang
            $magang = Magang::create($validated);

            // 🔹 Otomatis simpan juga ke detail_pelamar
            DetailPelamar::create([
                'magang_id' => $magang->id,
                'nama' => $magang->nama,
                'nik' => $magang->nik,
                'nim' => $magang->nim,
                'universitas' => $magang->universitas,
                'jurusan' => $magang->jurusan,
                'semester' => $magang->semester,
                'status_pengajuan' => $magang->status_pengajuan,
            ]);

            return response()->json([
                'message' => 'Data berhasil disimpan',
                'data' => $magang
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 🔹 Cek status magang berdasarkan NIK & NIM
    public function cekStatus(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits_between:8,20',
            'nim' => 'required|digits_between:8,20',
        ]);

        $magang = Magang::where('nik', $request->nik)
                        ->where('nim', $request->nim)
                        ->first();

        if (!$magang) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Data ditemukan',
            'status_pengajuan' => $magang->status_pengajuan,
            'data' => $magang,
        ], 200);
    }

    // 🔹 Ambil semua data pendaftar
    public function index()
    {
        $magang = Magang::all();

        return response()->json([
            'message' => 'Daftar semua pendaftar magang',
            'data' => $magang
        ], 200);
    }

    // 🔹 Ambil detail 1 pendaftar berdasarkan ID
    public function show($id)
    {
        $magang = Magang::find($id);

        if (!$magang) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail pendaftar ditemukan',
            'data' => $magang
        ], 200);
    }

    // 🔹 Update data magang
    public function update(Request $request, $id)
    {
        $magang = Magang::find($id);

        if (!$magang) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'nik' => 'sometimes|required|digits_between:8,20|unique:magang,nik,' . $id,
            'nim' => 'nullable|digits_between:8,20',
            'no_hp' => 'sometimes|required|digits_between:10,15',
            'universitas' => 'sometimes|required|string|max:255',
            'alamat_universitas' => 'nullable|string|max:255',
            'jurusan' => 'sometimes|required|string|max:100',
            'semester' => 'sometimes|required|integer|min:1|max:14',
            'divisi_tujuan' => 'sometimes|required|string|max:100',
            'waktu_mulai' => 'nullable|date',
            'waktu_selesai' => 'nullable|date|after_or_equal:waktu_mulai',
            'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'surat_permohonan' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'status_pengajuan' => 'sometimes|in:belum diproses,sedang diproses,diterima,ditolak',
        ]);

        if ($request->hasFile('proposal')) {
            $validated['proposal'] = $request->file('proposal')->store('proposal', 'public');
        }
        if ($request->hasFile('surat_permohonan')) {
            $validated['surat_permohonan'] = $request->file('surat_permohonan')->store('surat', 'public');
        }

        $magang->update($validated);

        // 🔹 Update juga ke detail_pelamar
        if (isset($validated['status_pengajuan'])) {
            DetailPelamar::where('magang_id', $magang->id)->update([
                'status_pengajuan' => $validated['status_pengajuan'],
            ]);
        }

        return response()->json([
            'message' => 'Data magang berhasil diperbarui',
            'data' => $magang
        ], 200);
    }

    // 🔹 Update status magang (khusus admin)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pengajuan' => 'required|in:belum diproses,sedang diproses,diterima,ditolak'
        ]);

        $magang = Magang::find($id);

        if (!$magang) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $magang->status_pengajuan = $request->status_pengajuan;
        $magang->save();

        // 🔹 Update juga detail_pelamar
        DetailPelamar::where('magang_id', $magang->id)->update([
            'status_pengajuan' => $request->status_pengajuan,
        ]);

        return response()->json([
            'message' => 'Status berhasil diperbarui',
            'data' => $magang
        ], 200);
    }

    // 🔹 Hapus data magang
    public function destroy($id)
    {
        $magang = Magang::find($id);

        if (!$magang) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $magang->delete();

        return response()->json([
            'message' => 'Data magang berhasil dihapus'
        ], 200);
    }
}
