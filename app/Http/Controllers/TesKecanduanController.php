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
        // 1. Validasi Input
        $rules = [
            'nama' => 'required',
            'kelas' => 'required',
            'umur' => 'required',
            'jenis_kelamin' => 'required',
        ];
        // Validasi semua soal P1-P20
        for ($i = 1; $i <= 20; $i++) {
            $rules['p' . $i] = 'required|integer|min:1|max:5';
        }
        $validatedData = $request->validate($rules);

        // ---------------------------------------------------------
        // IMPLEMENTASI ALGORITMA DECISION TREE C5.0 (SESUAI SKRIPSI)
        // Referensi: Gambar 3.44 Hasil Pohon Keputusan
        // ---------------------------------------------------------

        // Ambil Nilai Atribut Penting untuk Node (Percabangan)
        $p13 = $request->input('p13'); // Root
        $p19 = $request->input('p19'); // Node 1.2
        $p8 = $request->input('p8');  // Node 1.3
        $p20 = $request->input('p20'); // Node 1.4
        $p11 = $request->input('p11'); // Node 1.5
        $p6 = $request->input('p6');  // Node 1.6
        $p2 = $request->input('p2');  // Node 1.7 (Durasi mingguan)
        $p16 = $request->input('p16'); // Node 1.8 (Bohong ke ortu)

        // Default value
        $tingkatKecanduan = 'Tidak Terdefinisi';
        $alasan = '';
        $saran = '';

        // --- MULAI LOGIKA POHON KEPUTUSAN ---

        // ROOT: P13 (Lebih sering main gadget daripada ngobrol dgn keluarga)
        if ($p13 == 1 || $p13 == 2) {
            // Tidak Pernah (1) atau Jarang (2)
            $tingkatKecanduan = 'Tidak Kecanduan';
            $alasan = 'Anak memiliki prioritas sosial yang baik dan lebih memilih interaksi keluarga dibanding gadget.';
            $saran = 'Pertahankan pola asuh yang hangat ini agar anak tetap nyaman berinteraksi dengan keluarga.';

        } elseif ($p13 == 5) {
            // Selalu (5) -> Di skripsi Gambar 3.37 ini masuk Kecanduan Ringan
            $tingkatKecanduan = 'Kecanduan Ringan';
            $alasan = 'Meskipun anak sangat sering bermain gadget, pola perilaku lain menunjukkan masih ada kontrol.';
            $saran = 'Mulai batasi waktu layar secara bertahap dan buat aturan waktu keluarga tanpa gadget.';

        } elseif ($p13 == 3) {
            // Kadang-kadang (3) -> Masuk Node 1.1 (Cek P19)
            // P19: Lebih suka main gadget daripada belajar
            if ($p19 == 1) { // Tidak Pernah
                $tingkatKecanduan = 'Tidak Kecanduan';
                $alasan = 'Minat belajar anak masih tinggi dan tidak terganggu oleh gadget.';
                $saran = 'Dukung terus prestasi anak dan gunakan gadget hanya sebagai sarana hiburan sesekali.';
            } elseif ($p19 == 2 || $p19 == 3) { // Jarang/Kadang
                $tingkatKecanduan = 'Kecanduan Ringan';
                $alasan = 'Mulai terlihat gejala penurunan minat belajar akibat gadget.';
                $saran = 'Dampingi anak saat belajar dan jauhkan gadget dari ruang belajar.';
            } else { // Sering (4) / Selalu (5)
                $tingkatKecanduan = 'Kecanduan Berat';
                $alasan = 'Gadget telah mengganggu kewajiban utama anak (belajar) secara signifikan.';
                $saran = 'Perlu tindakan tegas. Sita gadget sementara waktu ujian atau belajar.';
            }

        } elseif ($p13 == 4) {
            // Sering (4) -> Masuk Node 1.2 (Cek P8)
            // P8: Tidak mengerjakan tugas gara-gara gadget
            if ($p8 == 1 || $p8 == 2) { // TP / Jarang
                $tingkatKecanduan = 'Tidak Kecanduan'; // (Sesuai Gambar 3.40)
                $alasan = 'Anak masih bertanggung jawab menyelesaikan tugas sekolahnya.';
                $saran = 'Berikan apresiasi atas tanggung jawab anak.';
            } elseif ($p8 == 5) { // Selalu
                $tingkatKecanduan = 'Kecanduan Ringan'; // (Sesuai Gambar 3.40)
                $alasan = 'Indikasi kelalaian tugas mulai menjadi kebiasaan.';
                $saran = 'Cek buku tugas anak setiap hari dan pastikan selesai sebelum bermain HP.';
            } else { // Sering (4) atau Kadang (3) -> Masuk Node 1.3 (Cek P20)
                // P20: Gadget membuat merasa lebih baik saat kacau (Pelarian Emosi)
                if ($p20 == 1 || $p20 == 2) { // TP / Jarang
                    $tingkatKecanduan = 'Tidak Kecanduan';
                    $alasan = 'Anak tidak menjadikan gadget sebagai pelarian emosional.';
                    $saran = 'Ajarkan cara mengelola emosi dengan bercerita atau olahraga.';
                } elseif ($p20 == 4) { // Sering
                    $tingkatKecanduan = 'Kecanduan Berat';
                    $alasan = 'Anak mengalami ketergantungan emosional pada gadget.';
                    $saran = 'Ajak anak bicara dari hati ke hati saat sedih, jangan biarkan menyendiri dengan gadget.';
                } elseif ($p20 == 5) { // Selalu
                    $tingkatKecanduan = 'Kecanduan Ringan';
                    $alasan = 'Ada kecenderungan menjadikan gadget penenang diri.';
                    $saran = 'Alihkan ke aktivitas fisik saat anak sedang bad mood.';
                } else { // Kadang-kadang (3) -> Masuk Node 1.4 (Cek P11)
                    // P11: Gadget lebih menyenangkan dari hal lain
                    if ($p11 == 1 || $p11 == 2) { // TP / Jarang
                        $tingkatKecanduan = 'Tidak Kecanduan';
                        $alasan = 'Anak masih bisa menikmati aktivitas lain di luar gadget.';
                        $saran = 'Perbanyak aktivitas outdoor atau hobi baru.';
                    } elseif ($p11 >= 4) { // Sering/Selalu
                        $tingkatKecanduan = 'Kecanduan Berat';
                        $alasan = 'Dunia nyata terasa membosankan bagi anak dibandingkan dunia maya.';
                        $saran = 'Lakukan "Puasa Gadget" (Dopamine Detox) selama beberapa hari.';
                    } else { // Kadang (3) -> Masuk Node 1.5 (Cek P6)
                        // P6: Gadget hal terpenting dalam hidup
                        if ($p6 == 1 || $p6 == 2) { // TP / Jarang
                            $tingkatKecanduan = 'Tidak Kecanduan';
                            $alasan = 'Gadget belum menjadi prioritas utama hidup anak.';
                            $saran = 'Pertahankan keseimbangan hidup anak.';
                        } elseif ($p6 >= 4) { // Sering/Selalu
                            $tingkatKecanduan = 'Kecanduan Berat';
                            $alasan = 'Anak merasa tidak bisa hidup tanpa gadget.';
                            $saran = 'Konsultasi dengan psikolog anak mungkin diperlukan.';
                        } else { // Kadang (3) -> Masuk Node 1.6 (Cek P2)
                            // P2: Durasi dalam seminggu
                            if ($p2 == 1 || $p2 == 2) { // 1 hari atau 2-3 hari
                                $tingkatKecanduan = 'Tidak Kecanduan';
                                $alasan = 'Frekuensi penggunaan gadget sangat rendah dan sehat.';
                                $saran = 'Bebaskan bermain sesuai jadwal yang sudah baik ini.';
                            } elseif ($p2 == 3) { // 4-5 Hari
                                $tingkatKecanduan = 'Kecanduan Ringan';
                                $alasan = 'Frekuensi bermain cukup sering (hampir setiap hari sekolah).';
                                $saran = 'Usahakan hari sekolah (Senin-Jumat) bebas gadget.';
                            } elseif ($p2 == 4) { // 6 Hari
                                $tingkatKecanduan = 'Kecanduan Berat';
                                $alasan = 'Hampir tiada hari tanpa gadget, risiko adiksi tinggi.';
                                $saran = 'Wajibkan hari libur gadget (misal: Hari Minggu tanpa HP).';
                            } else { // P2 == 5 (Setiap Hari) -> Masuk Node 1.7 (Cek P16)
                                // P16: Bohong ke orang tua soal gadget
                                if ($p16 == 1) { // TP
                                    $tingkatKecanduan = 'Tidak Kecanduan';
                                    $alasan = 'Meski main tiap hari, anak jujur dan terbuka (terkontrol).';
                                    $saran = 'Apresiasi kejujuran anak, tapi kurangi sedikit durasinya.';
                                } elseif ($p16 == 2 || $p16 == 3) { // Jarang/Kadang
                                    $tingkatKecanduan = 'Kecanduan Ringan';
                                    $alasan = 'Mulai muncul perilaku manipulatif/bohong kecil demi gadget.';
                                    $saran = 'Bangun komunikasi terbuka, jangan terlalu memarahinya agar dia tidak bohong lagi.';
                                } else { // Sering/Selalu
                                    $tingkatKecanduan = 'Kecanduan Berat';
                                    $alasan = 'Anak sering berbohong dan menyembunyikan aktivitas digitalnya.';
                                    $saran = 'Pasang aplikasi Parental Control untuk memantau aktivitas anak secara transparan.';
                                }
                            }
                        }
                    }
                }
            }
        }

        // --- SIMPAN KE DATABASE ---

        // Hitung total skor (Hanya untuk data statistik/history)
        $totalSkor = 0;
        for ($i = 1; $i <= 20; $i++) {
            $totalSkor += $request->input('p' . $i);
        }

        $dataToSave = $request->except('_token');
        $dataToSave['total_skor'] = $totalSkor;
        $dataToSave['tingkat_kecanduan'] = $tingkatKecanduan;
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
