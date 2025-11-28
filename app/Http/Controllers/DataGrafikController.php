<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilTes; // Panggil Model HasilTes
use Illuminate\Support\Facades\DB;

class DataGrafikController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query
        $query = HasilTes::query();

        // 2. Terapkan Filter jika ada input dari user
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        if ($request->filled('umur')) {
            $query->where('umur', $request->umur);
        }
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // 3. Ambil Data untuk Grafik (Persentase Kecanduan)
        // Kita hitung jumlah per kategori berdasarkan data yang sudah difilter
        $stats = $query->select('tingkat_kecanduan', DB::raw('count(*) as total'))
            ->groupBy('tingkat_kecanduan')
            ->pluck('total', 'tingkat_kecanduan')->toArray();

        // Siapkan data default jika kosong (agar grafik tidak error)
        $dataPie = [
            'Tidak Kecanduan' => $stats['Tidak Kecanduan'] ?? 0,
            'Kecanduan Ringan' => $stats['Kecanduan Ringan'] ?? 0,
            'Kecanduan Berat' => $stats['Kecanduan Berat'] ?? 0,
        ];

        // 4. Hitung Total Responden (Untuk Info Card)
        $totalResponden = array_sum($dataPie);
        $totalBerat = $dataPie['Kecanduan Berat'];

        // Kirim data ke View
        return view('data-grafik', [
            'dataPie' => $dataPie,
            'totalResponden' => $totalResponden,
            'totalBerat' => $totalBerat,
            // Kirim balik input filter agar form tidak reset setelah submit
            'filterKelas' => $request->kelas,
            'filterUmur' => $request->umur,
            'filterJK' => $request->jenis_kelamin,
        ]);
    }
}
