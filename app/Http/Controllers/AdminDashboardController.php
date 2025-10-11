<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelamar;
use App\Models\User;
use App\Models\Pembimbing;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah pelamar
        $jumlahPelamar = Pelamar::count();

        // Hitung pelamar berdasarkan status
        $belumDiproses = Pelamar::where('status', 'Belum Diproses')->count();
        $sedangDiproses = Pelamar::where('status', 'Sedang Diproses')->count();

        // Hitung total user dan pembimbing
        $jumlahUser = User::count();
        $jumlahPembimbing = Pembimbing::count();

        // Statistik pelamar per bulan berdasarkan tanggal_daftar
        $pelamarPerBulan = Pelamar::select(
            DB::raw('MONTH(tanggal_daftar) as bulan'),
            DB::raw('COUNT(*) as jumlah')
        )
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        // Format data agar mudah dibaca frontend (chart)
        $dataChart = [];
        foreach ($pelamarPerBulan as $item) {
            $dataChart[] = [
                'bulan' => date("M", mktime(0, 0, 0, $item->bulan, 1)),
                'jumlah' => $item->jumlah
            ];
        }

        // Return JSON untuk API frontend
        return response()->json([
            'jumlah_pelamar' => $jumlahPelamar,
            'belum_diproses' => $belumDiproses,
            'sedang_diproses' => $sedangDiproses,
            'jumlah_user' => $jumlahUser,
            'jumlah_pembimbing' => $jumlahPembimbing,
            'pelamar_per_bulan' => $dataChart
        ]);
    }
}
