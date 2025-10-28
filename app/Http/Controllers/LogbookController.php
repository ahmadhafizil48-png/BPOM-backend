<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logbook;
use Illuminate\Support\Facades\Storage; // 🆕 untuk simpan file

class LogbookController extends Controller
{
    // 🔹 Ambil semua logbook milik user tertentu
    public function index(Request $request)
    {
        $logbooks = Logbook::where('user_id', $request->user_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json($logbooks);
    }

    // 🔹 Simpan logbook baru (dengan lampiran opsional)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kendala' => 'nullable|string',
            'catatan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:2048', // 🆕 validasi file
        ]);

        // 🧩 kalau ada file yang dikirim
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $path = $file->store('lampiran_logbook', 'public'); // simpan di storage/app/public/lampiran_logbook
            $validated['lampiran'] = $path;
        }

        $logbook = Logbook::create($validated);

        return response()->json([
            'message' => 'Logbook disimpan',
            'data' => $logbook
        ], 201);
    }

    // 🔹 Update logbook (dengan ganti lampiran jika dikirim)
    public function update(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kendala' => 'nullable|string',
            'catatan' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:2048',
        ]);

        // 🧩 kalau ada file baru, hapus yang lama dan upload yang baru
        if ($request->hasFile('lampiran')) {
            if ($logbook->lampiran && Storage::disk('public')->exists($logbook->lampiran)) {
                Storage::disk('public')->delete($logbook->lampiran);
            }

            $file = $request->file('lampiran');
            $path = $file->store('lampiran_logbook', 'public');
            $validated['lampiran'] = $path;
        }

        $logbook->update($validated);

        return response()->json(['message' => 'Logbook diperbarui', 'data' => $logbook]);
    }

    // 🔹 Hapus logbook (hapus file juga)
    public function destroy($id)
    {
        $logbook = Logbook::findOrFail($id);

        // 🧩 Hapus file dari storage jika ada
        if ($logbook->lampiran && Storage::disk('public')->exists($logbook->lampiran)) {
            Storage::disk('public')->delete($logbook->lampiran);
        }

        $logbook->delete();

        return response()->json(['message' => 'Logbook dihapus']);
    }
}
