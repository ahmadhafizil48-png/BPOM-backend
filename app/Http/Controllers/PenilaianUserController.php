<?php

namespace App\Http\Controllers;

use App\Models\PenilaianUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenilaianUserController extends Controller
{
    // index: list semua penilaian / filter by proyek_user_id or user_id
    public function index(Request $request)
    {
        $q = PenilaianUser::with(['user', 'proyekUser']);

        if ($request->has('proyek_user_id')) {
            $q->where('proyek_user_id', $request->proyek_user_id);
        }
        if ($request->has('user_id')) {
            $q->where('user_id', $request->user_id);
        }

        return response()->json($q->get());
    }

    // store: buat penilaian (biasanya pembimbing)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'proyek_user_id' => 'nullable|exists:proyek_users,id',
            'kehadiran' => 'nullable|integer|between:0,100',
            'taat_jadwal' => 'nullable|integer|between:0,100',
            'penguasaan_materi' => 'nullable|integer|between:0,100',
            'praktek_kerja' => 'nullable|integer|between:0,100',
            'inisiatif' => 'nullable|integer|between:0,100',
            'komunikasi' => 'nullable|integer|between:0,100',
            'laporan_kerja' => 'nullable|integer|between:0,100',
            'presentasi' => 'nullable|integer|between:0,100',
            'catatan' => 'nullable|string',
        ]);

        // hitung nilai
        $scores = collect($validated)->only([
            'kehadiran','taat_jadwal','penguasaan_materi','praktek_kerja',
            'inisiatif','komunikasi','laporan_kerja','presentasi'
        ])->filter()->values();

        $nilai_total = $scores->sum();
        $nilai_rata = $scores->count() ? round($nilai_total / $scores->count(), 2) : null;

        $validated['nilai_total'] = $nilai_total;
        $validated['nilai_rata'] = $nilai_rata;

        $penilaian = PenilaianUser::create($validated);

        return response()->json([
            'message' => 'Penilaian tersimpan',
            'data' => $penilaian->load('user','proyekUser')
        ], 201);
    }

    // show
    public function show($id)
    {
        $p = PenilaianUser::with(['user','proyekUser','komplain'])->findOrFail($id);
        return response()->json($p);
    }

    // update
    public function update(Request $request, $id)
    {
        $penilaian = PenilaianUser::findOrFail($id);

        $validated = $request->validate([
            'kehadiran' => 'nullable|integer|between:0,100',
            'taat_jadwal' => 'nullable|integer|between:0,100',
            'penguasaan_materi' => 'nullable|integer|between:0,100',
            'praktek_kerja' => 'nullable|integer|between:0,100',
            'inisiatif' => 'nullable|integer|between:0,100',
            'komunikasi' => 'nullable|integer|between:0,100',
            'laporan_kerja' => 'nullable|integer|between:0,100',
            'presentasi' => 'nullable|integer|between:0,100',
            'catatan' => 'nullable|string',
        ]);

        $penilaian->update($validated);

        // recalc
        $scores = collect($penilaian->toArray())->only([
            'kehadiran','taat_jadwal','penguasaan_materi','praktek_kerja',
            'inisiatif','komunikasi','laporan_kerja','presentasi'
        ])->filter()->values();

        $penilaian->nilai_total = $scores->sum();
        $penilaian->nilai_rata = $scores->count() ? round($penilaian->nilai_total / $scores->count(), 2) : null;
        $penilaian->save();

        return response()->json([
            'message' => 'Penilaian diperbarui',
            'data' => $penilaian
        ]);
    }

    // destroy
    public function destroy($id)
    {
        $penilaian = PenilaianUser::findOrFail($id);
        $penilaian->delete();

        return response()->json(['message' => 'Penilaian dihapus']);
    }
}
