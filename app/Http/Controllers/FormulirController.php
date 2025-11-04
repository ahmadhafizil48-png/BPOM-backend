<?php

namespace App\Http\Controllers;

use App\Models\Formulir;
use App\Models\User;
use App\Models\UserAktif;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class FormulirController extends Controller
{
    /**
     * PUBLIC – simpan pengajuan formulir (bisa login atau tidak)
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

        // Upload file jika ada
        if ($request->hasFile('proposal')) {
            $validated['proposal'] = $request->file('proposal')->store('proposal', 'public');
        }
        if ($request->hasFile('surat_permohonan')) {
            $validated['surat_permohonan'] = $request->file('surat_permohonan')->store('surat', 'public');
        }

        // Tambahan otomatis
        $validated['status_pengajuan'] = 'belum diproses';
        $validated['no_formulir'] = 'F-' . date('Y') . str_pad(Formulir::count() + 1, 4, '0', STR_PAD_LEFT);

        // Jika user login → ambil ID, jika belum → buat akun baru otomatis
        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
            $user = Auth::user();
        } else {
            $email = $request->nik . '@pelamar.local';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $request->nama,
                    'password' => Hash::make('password'),
                    'role_id' => 3,
                    'is_active' => true,
                ]
            );
            $validated['user_id'] = $user->id;
        }

        // Simpan formulir
        $formulir = Formulir::create($validated);

        // Cari divisi dengan huruf bebas besar kecil
        $divisi = Divisi::whereRaw('LOWER(nama_divisi) = ?', [strtolower(trim($request->divisi_tujuan))])->first();

        // Tambahkan otomatis ke tabel user_aktif
        UserAktif::updateOrCreate(
            ['user_id' => $user->id],
            [
                'divisi_id'     => $divisi ? $divisi->id : null,
                'pembimbing_id' => null,
                'status_akun'   => 'Ada Akun',
                'is_active'     => true,
            ]
        );

        return response()->json([
            'message' => 'Formulir berhasil dikirim dan user_id telah dihubungkan.',
            'data'    => $formulir,
        ], 201);
    }

    /**
     * PUBLIC – cek status pengajuan
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
     * ADMIN/PEMBIMBING – lihat semua formulir
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
                'sometimes', 'required', 'digits_between:8,20',
                Rule::unique('formulir', 'nik')->ignore($formulir->id),
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
     * ADMIN/PEMBIMBING – update status formulir dan sinkronkan ke tabel user_aktif
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pengajuan' => 'required|in:belum diproses,sedang diproses,diterima,ditolak',
            'divisi_id'        => 'nullable|exists:divisis,id',
            'pembimbing_id'    => 'nullable|exists:users,id',
        ]);

        $formulir = Formulir::find($id);
        if (!$formulir) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $formulir->update(['status_pengajuan' => $request->status_pengajuan]);

        // Update user aktif hanya jika formulir diterima
        if ($request->status_pengajuan === 'diterima') {
            $userAktif = UserAktif::where('user_id', $formulir->user_id)->first();
            if ($userAktif) {
                $userAktif->update([
                    'divisi_id'     => $request->divisi_id ?? $userAktif->divisi_id,
                    'pembimbing_id' => $request->pembimbing_id ?? $userAktif->pembimbing_id,
                ]);
            } else {
                UserAktif::create([
                    'user_id'       => $formulir->user_id,
                    'divisi_id'     => $request->divisi_id ?? null,
                    'pembimbing_id' => $request->pembimbing_id ?? null,
                    'status_akun'   => 'Ada Akun',
                    'is_active'     => true,
                ]);
            }
        }

        return response()->json([
            'message' => 'Status formulir dan user aktif berhasil diperbarui',
            'data'    => $formulir,
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
