<?php

namespace App\Http\Controllers;
use App\Models\HasilTes;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TesKecanduanController extends Controller
{
    // MENYIMPAN DAFTAR PERTANYAAN DI SINI
    private $pertanyaan = [
        1 => 'Lama saya menggunakan gadget dalam sehari',
        2 => 'Dalam seminggu, saya menggunakan gadget selama',
        3 => 'Saya suka main gadget terus sampai lupa waktu',
        4 => 'Saya merasa kesal ketika saya tidak dapat menggunakan gadget saya',
        5 => 'Saya merasa kesal ketika diminta untuk berhenti menggunakan gadget saya',
        6 => 'Menggunakan gadget adalah hal terpenting dalam hidup saya',
        7 => 'Saya suka susah fokus belajar karena kepikiran ingin main gadget',
        8 => 'Saya pernah tidak mengerjakan tugas atau kegiatan lain gara-gara bermain gadget',
        9 => 'Saya jadi susah tidur atau tidur tidak nyenyak setelah main gadget',
        10 => 'Saya sering tidur larut malam gara-gara main gadget',
        11 => 'Menggunakan gadget lebih menyenangkan dari pada melakukan hal lain',
        12 => 'Saya kehilangan minat pada hobi atau aktivitas lain karena lebih suka gadget',
        13 => 'Saya lebih sering main gadget dari pada ngobrol bareng keluarga',
        14 => 'Saya lebih suka main gadget sendirian dari pada main sama teman',
        15 => 'Menggunakan gadget membantu saya melupakan masalah',
        16 => 'Saya pernah bohong ke orang tua soal apa yang saya lakukan di gadget',
        17 => 'Orang tua mencoba menghentikan saya main gadget, tetapi gagal',
        18 => 'Saya tidak bisa mengontrol penggunaan gadget saya',
        19 => 'Saya lebih suka main gadget dari pada belajar',
        20 => 'Menggunakan gadget membuat saya merasa lebih baik ketika sedang kacau',
    ];

    // Method untuk menampilkan Halaman Tes
    public function index()
    {
        // Kirim data pertanyaan ke view
        return view('tes-kecanduan', [
            'pertanyaan' => $this->pertanyaan
        ]);
    }

    // Method untuk memproses jawaban (Submit)
    public function store(Request $request)
    {
        // 1. Validasi Input (Tetap sama)
        $rules = [
            'nama' => 'required',
            'kelas' => 'required',
            'umur' => 'required',
            'jenis_kelamin' => 'required',
        ];
        for ($i = 1; $i <= 20; $i++) {
            $rules['p' . $i] = 'required|integer|min:1|max:5';
        }
        $validatedData = $request->validate($rules);

        // ---------------------------------------------------------
        // IMPLEMENTASI ALGORITMA DECISION TREE C5.0 (Sesuai Bab 3)
        // ---------------------------------------------------------

        // Ambil Nilai Atribut Penting (Root & Node Cabang)
        // P13: Saya lebih sering main gadget dari pada ngobrol... (Root)
        $p13 = $request->input('p13'); // Lebih sering gadget drpd keluarga
        $p18 = $request->input('p18'); // Tidak bisa kontrol diri

        $tingkatKecanduan = '';
        $alasan = ''; // Variabel baru untuk alasan
        $saran = '';

        // --- LOGIKA DECISION TREE C5.0 ---

        // Cek Root (Pertanyaan 13)
        if ($p13 == 1 || $p13 == 2) {
            // NODE KIRI: Tidak Pernah/Jarang
            $tingkatKecanduan = 'Tidak Kecanduan';
            $alasan = 'Anak memiliki prioritas sosial yang sangat baik. Ia lebih memilih berinteraksi dengan keluarga dibandingkan bermain gadget.';
            $saran = 'Pertahankan pola asuh ini. Tetap awasi konten yang diakses, namun berikan apresiasi pada anak karena mampu memprioritaskan keluarga.';

        } elseif ($p13 == 4 || $p13 == 5) {
            // NODE KANAN: Sering/Selalu
            $tingkatKecanduan = 'Kecanduan Berat';
            $alasan = 'Interaksi sosial utama anak telah tergantikan oleh gadget. Gadget bukan lagi hiburan, melainkan kebutuhan primer yang menggeser peran keluarga.';
            $saran = 'Segera lakukan "Detoks Digital". Batasi akses gadget secara ketat, simpan gadget di luar kamar tidur, dan perbanyak aktivitas fisik bersama keluarga.';

        } elseif ($p13 == 3) {
            // NODE TENGAH: Kadang-kadang -> Cek P18

            if ($p18 == 1) {
                // P18 Tidak Pernah
                $tingkatKecanduan = 'Tidak Kecanduan';
                $alasan = 'Meskipun terkadang bermain gadget, anak memiliki kontrol diri yang luar biasa dan tahu kapan harus berhenti.';
                $saran = 'Aman. Berikan kebebasan terkontrol. Pastikan durasi bermain tetap seimbang dengan waktu belajar.';

            } elseif ($p18 == 2 || $p18 == 3) {
                // P18 Jarang/Kadang
                $tingkatKecanduan = 'Kecanduan Ringan';
                $alasan = 'Terdeteksi indikasi penurunan kontrol diri. Anak mulai kesulitan berhenti bermain gadget meskipun sadar harus melakukan hal lain.';
                $saran = 'Waspada. Buat jadwal "Waktu Bebas Gadget" (misal: saat makan malam). Orang tua harus menjadi contoh (role model) yang baik.';

            } elseif ($p18 == 4 || $p18 == 5) {
                // P18 Sering/Selalu
                $tingkatKecanduan = 'Kecanduan Berat';
                $alasan = 'Anak menyadari ketidakmampuannya mengontrol diri namun terus melanjutkan penggunaan gadget (kompulsif).';
                $saran = 'Kondisi Kritis. Perlu intervensi tegas. Pertimbangkan konsultasi dengan psikolog anak jika muncul perilaku tantrum saat gadget diambil.';
            }
        }

        // --- SIMPAN KE DATABASE ---

        // Hitung total skor (Hanya untuk data statistik)
        $totalSkor = 0;
        for ($i = 1; $i <= 20; $i++) {
            $totalSkor += $request->input('p' . $i);
        }

        $dataToSave = $request->except('_token');
        $dataToSave['total_skor'] = $totalSkor;
        $dataToSave['tingkat_kecanduan'] = $tingkatKecanduan;

        // PENTING: Simpan Alasan dan Saran ke Database
        $dataToSave['alasan'] = $alasan;
        $dataToSave['saran'] = $saran;

        $hasil = HasilTes::create($dataToSave);

        session(['id_tes_terakhir' => $hasil->id]);

        return redirect()->route('tes.hasil', ['id' => $hasil->id]);
    }

    // Method untuk Menampilkan Halaman Hasil
    public function showResult($id)
    {
        $hasil = HasilTes::findOrFail($id);
        return view('tes-hasil', compact('hasil'));
    }

    // Fungsi untuk cetak hasil tes ke pdf
    public function cetakPdf($id)
    {
        // 1. Ambil data berdasarkan ID
        $hasil = HasilTes::findOrFail($id);

        // 2. Load View PDF yang baru dibuat
        $pdf = Pdf::loadView('pdf.cetak-hasil', ['hasil' => $hasil]);

        // 3. Set ukuran kertas (opsional, default A4)
        $pdf->setPaper('A4', 'portrait');

        // 4. Download file PDF
        // Nama file: Hasil-Tes-NamaAnak.pdf
        return $pdf->download('Hasil-Tes-' . str_replace(' ', '-', $hasil->nama) . '.pdf');
    }

    // Method untuk melihat hasil tes terakhir dari session
    public function lihatHasilTerakhir()
    {
        // Cek apakah ada ID tersimpan di session
        if (session()->has('id_tes_terakhir')) {
            $id = session('id_tes_terakhir');

            // Cek apakah data masih ada di database (untuk jaga-jaga)
            $hasil = HasilTes::find($id);

            if ($hasil) {
                return redirect()->route('tes.hasil', ['id' => $id]);
            }
        }

        // Jika tidak ada session atau data hilang, kembalikan ke halaman tes awal
        // dengan pesan error (opsional)
        return redirect()->route('tes.kecanduan')->with('warning', 'Anda belum melakukan tes atau sesi telah berakhir.');
    }
}
