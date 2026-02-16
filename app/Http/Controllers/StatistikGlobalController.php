<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilTes;
use Illuminate\Support\Facades\DB;

class StatistikGlobalController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query
        $query = HasilTes::query();

        // 2. Terapkan Filter
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        if ($request->filled('umur')) {
            $query->where('umur', $request->umur);
        }
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // 3. Hitung Data Statistik
        $stats = $query->select('tingkat_kecanduan', DB::raw('count(*) as total'))
            ->groupBy('tingkat_kecanduan')
            ->pluck('total', 'tingkat_kecanduan')->toArray();

        // Siapkan data default (agar grafik tidak error jika kosong)
        $dataPie = [
            'Tidak Kecanduan' => $stats['Tidak Kecanduan'] ?? 0,
            'Kecanduan Ringan' => $stats['Kecanduan Ringan'] ?? 0,
            'Kecanduan Berat' => $stats['Kecanduan Berat'] ?? 0,
        ];

        // 4. Hitung Total Responden
        $totalResponden = array_sum($dataPie);
        $totalBerat = $dataPie['Kecanduan Berat'];

        return view('statistik-global', [
            'dataPie' => $dataPie,
            'totalResponden' => $totalResponden,
            'totalBerat' => $totalBerat,
            'filterKelas' => $request->kelas,
            'filterUmur' => $request->umur,
            'filterJK' => $request->jenis_kelamin,
        ]);
    }
}
