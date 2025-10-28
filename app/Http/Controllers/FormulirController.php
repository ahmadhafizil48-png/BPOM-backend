<?php

namespace App\Http\Controllers;

use App\Models\Formulir;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // ✅ Tambahkan ini agar tidak merah
use Illuminate\Validation\Rule;

class FormulirController extends Controller
{
    /**
     * PUBLIC – simpan pengajuan formulir (tanpa login)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'               => 'required|string|max:255',
            'nik'                => 'required|digits_between:8,20|unique:formulir,nik',
            'nim'                => 'nullable|digits_between:8,20',
            'no_hp'              => 'required|digits_between:10,15',
            'universitas'        => 'required|string|max:255',
            'alamat_universitas' => 'nullable|string|max:255',
            'jurusan'            => 'required|string|max:100',
            'semester'           => 'required|integer|min:1|max:14',
            'divisi_tujuan'      => 'required|string|max:100',
            'waktu_mulai'        => 'nullable|date',
            'waktu_selesai'      => 'nullable|date|after_or_equal:waktu_mulai',
            'proposal'           => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'surat_permohonan'   => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // 📎 Upload file jika ada
        if ($request->hasFile('proposal')) {
            $validated['proposal'] = $request->file('proposal')->store('proposal', 'public');
        }
        if ($request->hasFile('surat_permohonan')) {
            $validated['surat_permohonan'] = $request->file('surat_permohonan')->store('surat', 'public');
        }

        // 🔹 Tambahan otomatis
        $validated['status_pengajuan'] = 'belum diproses';
        $validated['no_formulir'] = 'F-' . date('Y') . str_pad(Formulir::count() + 1, 4, '0', STR_PAD_LEFT);

        // ✅ Gunakan Auth facade agar tidak merah di VS Code
        $validated['user_id'] = Auth::check() ? Auth::id() : 1;

        // 💾 Simpan ke database
        $formulir = Formulir::create($validated);

        return response()->json([
            'message' => 'Pengajuan formulir berhasil disimpan',
            'data'    => $formulir,
        ], 201);
    }

    /**
     * PUBLIC – cek status pengajuan (nik + nim)
     */
    public function cekStatus(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits_between:8,20',
            'nim' => 'required|digits_between:8,20',
        ]);

        $formulir = Formulir::where('nik', $request->nik)
            ->where('nim', $request->nim)
            ->first();

        if (!$formulir) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'message'          => 'Data ditemukan',
            'status_pengajuan' => $formulir->status_pengajuan,
            'data'             => $formulir,
        ]);
    }

    /**
     * ADMIN/PEMBIMBING – list semua formulir
     */
    public function index()
    {
        return response()->json([
            'message' => 'Daftar semua pengajuan formulir',
            'data'    => Formulir::orderBy('created_at', 'desc')->get(),
        ]);
    }

    /**
     * ADMIN/PEMBIMBING – detail formulir
     */
    public function show($id)
    {
        $formulir = Formulir::find($id);
        if (!$formulir) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail formulir',
            'data'    => $formulir,
        ]);
    }

    /**
     * ADMIN/PEMBIMBING – update formulir
     */
    public function update(Request $request, $id)
    {
        $formulir = Formulir::find($id);
        if (!$formulir) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama'               => 'sometimes|required|string|max:255',
            'nik'                => [
                'sometimes','required','digits_between:8,20',
                Rule::unique('formulir','nik')->ignore($formulir->id),
            ],
            'nim'                => 'nullable|digits_between:8,20',
            'no_hp'              => 'sometimes|required|digits_between:10,15',
            'universitas'        => 'sometimes|required|string|max:255',
            'alamat_universitas' => 'nullable|string|max:255',
            'jurusan'            => 'sometimes|required|string|max:100',
            'semester'           => 'sometimes|required|integer|min:1|max:14',
            'divisi_tujuan'      => 'sometimes|required|string|max:100',
            'waktu_mulai'        => 'nullable|date',
            'waktu_selesai'      => 'nullable|date|after_or_equal:waktu_mulai',
            'proposal'           => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'surat_permohonan'   => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'status_pengajuan'   => 'sometimes|in:belum diproses,sedang diproses,diterima,ditolak',
        ]);

        // Ganti file jika ada upload baru
        if ($request->hasFile('proposal')) {
            if ($formulir->proposal) {
                Storage::disk('public')->delete($formulir->proposal);
            }
            $validated['proposal'] = $request->file('proposal')->store('proposal', 'public');
        }
        if ($request->hasFile('surat_permohonan')) {
            if ($formulir->surat_permohonan) {
                Storage::disk('public')->delete($formulir->surat_permohonan);
            }
            $validated['surat_permohonan'] = $request->file('surat_permohonan')->store('surat', 'public');
        }

        $formulir->update($validated);

        return response()->json([
            'message' => 'Data formulir berhasil diperbarui',
            'data'    => $formulir,
        ]);
    }

    /**
     * ADMIN/PEMBIMBING – update status + buat akun otomatis jika diterima
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pengajuan' => 'required|in:belum diproses,sedang diproses,diterima,ditolak'
        ]);

        $formulir = Formulir::find($id);
        if (!$formulir) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $oldStatus = $formulir->status_pengajuan;
        $formulir->status_pengajuan = $request->status_pengajuan;
        $formulir->save();

        $account = null;
        $generatedPassword = null;

        // Jika baru diterima → buat akun user otomatis
        if ($oldStatus !== 'diterima' && $request->status_pengajuan === 'diterima') {
            $email = $formulir->nik . '@pelamar.local';
            $existing = User::where('email', $email)->first();

            if (!$existing) {
                $generatedPassword = 'password';
                $account = User::create([
                    'name'          => $formulir->nama,
                    'email'         => $email,
                    'password'      => Hash::make($generatedPassword),
                    'role_id'       => 3, // role mahasiswa
                    'pembimbing_id' => null,
                    'is_active'     => true,
                ]);
            }
        }

        return response()->json([
            'message'      => 'Status berhasil diperbarui',
            'data'         => $formulir,
            'account'      => $account,
            'default_pass' => $generatedPassword,
        ]);
    }

    /**
     * ADMIN – hapus formulir
     */
    public function destroy($id)
    {
        $formulir = Formulir::find($id);
        if (!$formulir) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($formulir->proposal) {
            Storage::disk('public')->delete($formulir->proposal);
        }
        if ($formulir->surat_permohonan) {
            Storage::disk('public')->delete($formulir->surat_permohonan);
        }

        $formulir->delete();

        return response()->json(['message' => 'Data formulir berhasil dihapus']);
    }
}
