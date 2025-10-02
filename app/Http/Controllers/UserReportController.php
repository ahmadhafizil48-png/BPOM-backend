<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProyekUser;
use Carbon\Carbon;

class UserReportController extends Controller
{
    // 🔹 User aktif yang akan selesai
    public function userAktif()
    {
        $today = Carbon::now();

        // ambil user yang status = berjalan, dan ada tanggal selesai
        $data = ProyekUser::with('user')
            ->where('status', 'berjalan')
            ->whereNotNull('tanggal_selesai')   // pastikan ada field tanggal_selesai di tabel
            ->get()
            ->map(function ($item) use ($today) {
                $item->sisa_hari = $today->diffInDays(Carbon::parse($item->tanggal_selesai), false);
                return $item;
            });

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // 🔹 Riwayat penilaian user
    public function riwayatPenilaian($user_id)
    {
        $penilaian = \App\Models\PenilaianUser::with('proyekUser')
            ->where('user_id', $user_id)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $penilaian
        ]);
    }
}
