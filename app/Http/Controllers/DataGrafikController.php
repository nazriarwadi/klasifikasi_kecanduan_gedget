<?php

namespace App\Http\Controllers;

use App\Models\HasilTes;

class DataGrafikController extends Controller
{
    public function index()
    {
        // 1. CEK SESSION: Apakah user sudah pernah tes?
        if (!session()->has('id_tes_terakhir')) {
            // Jika belum ada session, kirim status false
            return view('data-grafik', [
                'allowed' => false
            ]);
        }

        // 2. AMBIL DATA SPESIFIK USER
        $id = session('id_tes_terakhir');
        $hasil = HasilTes::find($id);

        // Jika data di database ternyata sudah dihapus tapi session masih ada
        if (!$hasil) {
            return view('data-grafik', ['allowed' => false]);
        }

        // 3. OLAH DATA UNTUK GRAFIK (Frekuensi Jawaban)
        // Kita akan menghitung berapa kali user menjawab bobot 1, 2, 3, 4, atau 5
        // Ini berguna untuk melihat pola: "Wah, ternyata saya paling banyak jawab 'Selalu' (5)"

        $frekuensi = [
            1 => 0, // Tidak Pernah / Rendah
            2 => 0, // Jarang
            3 => 0, // Kadang-kadang
            4 => 0, // Sering
            5 => 0  // Selalu / Tinggi
        ];

        // Loop kolom p1 sampai p20
        for ($i = 1; $i <= 20; $i++) {
            $nilaiJawaban = $hasil->{'p' . $i}; // Mengambil value p1, p2, dst secara dinamis
            if (isset($frekuensi[$nilaiJawaban])) {
                $frekuensi[$nilaiJawaban]++;
            }
        }

        // Siapkan data untuk Chart.js
        $chartData = [
            'labels' => ['Skor 1 (Rendah)', 'Skor 2 (Jarang)', 'Skor 3 (Kadang)', 'Skor 4 (Sering)', 'Skor 5 (Tinggi)'],
            'values' => array_values($frekuensi)
        ];

        return view('data-grafik', [
            'allowed' => true,
            'hasil' => $hasil,
            'chartData' => $chartData
        ]);
    }
}
