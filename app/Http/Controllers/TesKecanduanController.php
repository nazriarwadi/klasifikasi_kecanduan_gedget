<?php

namespace App\Http\Controllers;
use App\Models\HasilTes;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TesKecanduanController extends Controller
{
    // MENYIMPAN DAFTAR PERTANYAAN DI SINI (SESUAI TABEL ATRIBUT)
    private $pertanyaan = [
        1 => 'Dalam sehari, biasanya Ananda memakai gadget selama',
        2 => 'Dalam seminggu, Ananda menggunakan gadget selama',
        3 => 'Ananda suka main gadget terus sampai lupa waktu',
        4 => 'Ananda merasa kesal ketika Ananda tidak dapat menggunakan gadget',
        5 => 'Ananda merasa kesal ketika diminta untuk berhenti menggunakan gadget',
        6 => 'Menggunakan gadget adalah hal terpenting dalam hidup Ananda',
        7 => 'Ananda suka susah fokus belajar karena kepikiran ingin main gadget',
        8 => 'Ananda pernah tidak mengerjakan tugas atau kegiatan lain gara-gara bermain gadget',
        9 => 'Ananda jadi susah tidur atau tidur tidak nyenyak setelah main gadget',
        10 => 'Ananda sering tidur larut malam gara-gara main gadget',
        11 => 'Menggunakan gadget lebih menyenangkan dari pada melakukan hal lain',
        12 => 'Ananda kehilangan minat pada hobi atau aktivitas lain karena Ananda lebih suka menggunakan gadget',
        13 => 'Ananda lebih sering main gadget dari pada ngobrol atau menghabiskan waktu bareng keluarga',
        14 => 'Ananda lebih suka main gadget sendirian dari pada main sama teman',
        15 => 'Menggunakan gadget membantu Ananda melupakan masalah',
        16 => 'Ananda pernah bohong ke orang tua soal apa yang Ananda buka atau lakukan di gadget',
        17 => 'Orang tua Ananda mencoba untuk menghentikan atau membatasi Ananda menggunakan gadget, tetapi gagal',
        18 => 'Ananda tidak bisa mengontrol penggunaan gadget',
        19 => 'Ananda lebih suka main gadget dari pada belajar',
        20 => 'Menggunakan gadget membuat Ananda merasa lebih baik ketika perasaan Ananda sedang kacau',
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
            $alasan = 'Ananda memiliki ikatan emosi yang kuat dengan keluarga. Ini adalah tanda yang sangat positif bahwa dunia nyata dan kehangatan rumah masih jauh lebih menarik baginya dibandingkan layar gadget.';
            $saran = 'Pertahankan momen kebersamaan ini, Bunda/Ayah. Tetap libatkan Ananda dalam obrolan ringan atau aktivitas fisik bersama untuk menjaga keseimbangan tumbuh kembang sosialnya.';

        } elseif ($p13 == 5) {
            // Selalu (5) -> Di skripsi Gambar 3.37 ini masuk Kecanduan Ringan
            $tingkatKecanduan = 'Kecanduan Ringan';
            $alasan = 'Ananda terlihat sangat lekat dengan gadgetnya hingga interaksi keluarga mulai berkurang signifikan. Namun, perilaku ini mungkin hanyalah fase kebosanan atau pengalihan yang masih bisa kita arahkan kembali.';
            $saran = 'Cobalah buat kesepakatan "Zona Bebas Gadget" di rumah, misalnya saat makan atau santai di ruang keluarga. Ajak Ananda bermain board game atau memasak bersama untuk mengalihkan fokusnya.';

        } elseif ($p13 == 3) {
            // Kadang-kadang (3) -> Masuk Node 1.1 (Cek P19)
            // P19: Lebih suka main gadget daripada belajar
            if ($p19 == 1) { // Tidak Pernah
                $tingkatKecanduan = 'Tidak Kecanduan';
                $alasan = 'Ananda mampu menempatkan prioritas dengan sangat baik. Bagi Ananda, gadget hanyalah sarana hiburan sejenak, bukan pengganggu kewajiban utamanya untuk belajar dan berkembang.';
                $saran = 'Berikan apresiasi atas kedisiplinannya ya, Bunda. Jadikan waktu bermain gadget sebagai "hadiah kecil" setelah ia tuntas belajar agar ia makin termotivasi.';
            } elseif ($p19 == 2 || $p19 == 3) { // Jarang/Kadang
                $tingkatKecanduan = 'Kecanduan Ringan';
                $alasan = 'Mulai terlihat tanda bahwa gadget menggoda fokus belajarnya. Ananda terkadang merasa materi pelajaran kalah menarik dibandingkan stimulasi visual dari layar gadget.';
                $saran = 'Dampingi Ananda saat belajar dengan metode yang lebih interaktif. Pastikan gadget disimpan di ruangan lain (silent mode) saat jam belajar dimulai untuk mengurangi distrak.';
            } else { // Sering (4) / Selalu (5)
                $tingkatKecanduan = 'Kecanduan Berat';
                $alasan = 'Motivasi akademik Ananda sedang tergerus oleh daya tarik gadget. Ia merasa kepuasan instan dari dunia digital jauh lebih menyenangkan daripada proses belajar di sekolah.';
                $saran = 'Perlu tindakan segera. Terapkan aturan tegas namun penuh kasih: "No Study, No Gadget". Jika perlu, konsultasikan dengan gurunya untuk mencari cara mengembalikan semangat belajarnya.';
            }

        } elseif ($p13 == 4) {
            // Sering (4) -> Masuk Node 1.2 (Cek P8)
            // P8: Tidak mengerjakan tugas gara-gara gadget
            if ($p8 == 1 || $p8 == 2) { // TP / Jarang
                $tingkatKecanduan = 'Tidak Kecanduan';
                $alasan = 'Meskipun sering bermain gadget, Ananda hebat karena tetap bertanggung jawab menuntaskan tugas-tugasnya. Ia memiliki kemampuan kontrol diri (self-regulation) yang cukup baik.';
                $saran = 'Berikan pujian spesifik atas tanggung jawabnya. Tetap awasi durasi mainnya agar kesehatan mata dan postur tubuhnya tetap terjaga.';
            } elseif ($p8 == 5) { // Selalu
                $tingkatKecanduan = 'Kecanduan Ringan';
                $alasan = 'Ananda mulai kesulitan mengatur manajemen waktu. Gadget sering membuatnya lupa waktu atau menunda-nunda kewajiban utamanya hingga terabaikan.';
                $saran = 'Bantu Ananda membuat jadwal harian visual yang ditempel di dinding. Ingatkan dengan lembut namun tegas saat waktunya beralih dari gadget ke aktivitas lain.';
            } else { // Sering (4) atau Kadang (3) -> Masuk Node 1.3 (Cek P20)
                // P20: Gadget membuat merasa lebih baik saat kacau (Pelarian Emosi)
                if ($p20 == 1 || $p20 == 2) { // TP / Jarang
                    $tingkatKecanduan = 'Tidak Kecanduan';
                    $alasan = 'Ananda bermain gadget murni untuk kesenangan, bukan sebagai pelarian dari perasaan sedih atau marah. Regulasi emosinya masih tergolong stabil.';
                    $saran = 'Pastikan konten yang ditonton bernilai positif. Ajak ia bercerita tentang apa yang ia mainkan agar komunikasi dua arah tetap terjalin.';
                } elseif ($p20 == 4) { // Sering
                    $tingkatKecanduan = 'Kecanduan Berat';
                    $alasan = 'Gadget telah menjadi "obat penenang" bagi Ananda. Ia bergantung pada layar untuk merasa nyaman, yang menghambat kemampuannya untuk mengelola emosi negatif secara mandiri.';
                    $saran = 'Hadirkan pelukan dan telinga untuk mendengar saat ia sedih. Ajarkan cara menenangkan diri (coping mechanism) tanpa layar, seperti menarik napas dalam, menggambar, atau bercerita.';
                } elseif ($p20 == 5) { // Selalu
                    $tingkatKecanduan = 'Kecanduan Ringan';
                    $alasan = 'Ada kecenderungan Ananda mencari gadget saat merasa tidak nyaman atau bosan. Ini adalah mekanisme pertahanan diri yang perlu diluruskan pelan-pelan.';
                    $saran = 'Alihkan perhatiannya ke aktivitas sensorik (seperti main pasir, air, atau olahraga ringan) saat ia terlihat bad mood, alih-alih langsung menyodorkan gadget.';
                } else { // Kadang-kadang (3) -> Masuk Node 1.4 (Cek P11)
                    // P11: Gadget lebih menyenangkan dari hal lain
                    if ($p11 == 1 || $p11 == 2) { // TP / Jarang
                        $tingkatKecanduan = 'Tidak Kecanduan';
                        $alasan = 'Dunia nyata masih menarik bagi Ananda. Ia masih bisa menikmati bermain fisik, bersepeda, atau aktivitas non-digital lainnya dengan gembira.';
                        $saran = 'Fasilitasi hobi non-digitalnya. Semakin ia sibuk dengan aktivitas fisik yang seru, semakin lupa ia dengan keberadaan gadgetnya.';
                    } elseif ($p11 >= 4) { // Sering/Selalu
                        $tingkatKecanduan = 'Kecanduan Berat';
                        $alasan = 'Ananda mengalami "banjir dopamin" dari gadget, sehingga aktivitas dunia nyata terasa membosankan, lambat, dan hambar baginya.';
                        $saran = 'Lakukan "Detoks Dopamin" sederhana. Kurangi stimulasi layar secara drastis selama beberapa hari dan ajak ia kembali berinteraksi dengan alam (nature) untuk mereset fokusnya.';
                    } else { // Kadang (3) -> Masuk Node 1.5 (Cek P6)
                        // P6: Gadget hal terpenting dalam hidup
                        if ($p6 == 1 || $p6 == 2) { // TP / Jarang
                            $tingkatKecanduan = 'Tidak Kecanduan';
                            $alasan = 'Gadget bukan segalanya bagi Ananda. Ia sadar bahwa ada hal-hal lain yang lebih penting dalam hidupnya, seperti keluarga, teman, dan sekolah.';
                            $saran = 'Terus tanamkan nilai-nilai keluarga. Jadikan waktu makan bersama sebagai momen sakral tanpa gangguan teknologi apapun.';
                        } elseif ($p6 >= 4) { // Sering/Selalu
                            $tingkatKecanduan = 'Kecanduan Berat';
                            $alasan = 'Gadget sudah menjadi pusat semesta Ananda. Ia merasa hampa, cemas, atau bingung berlebihan jika dijauhkan dari perangkatnya.';
                            $saran = 'Ini membutuhkan kesabaran ekstra. Kurangi durasi secara bertahap (tapering off) dan ganti waktu tersebut dengan aktivitas bonding yang kuat dengan Ayah/Bunda.';
                        } else { // Kadang (3) -> Masuk Node 1.6 (Cek P2)
                            // P2: Durasi dalam seminggu
                            if ($p2 == 1 || $p2 == 2) { // 1 hari atau 2-3 hari
                                $tingkatKecanduan = 'Tidak Kecanduan';
                                $alasan = 'Frekuensi penggunaan gadget Ananda masih sangat minim, sehat, dan dalam batas yang aman untuk usianya.';
                                $saran = 'Pertahankan aturan yang sudah ada. Ananda sudah berada di jalur pengasuhan digital yang tepat.';
                            } elseif ($p2 == 3) { // 4-5 Hari
                                $tingkatKecanduan = 'Kecanduan Ringan';
                                $alasan = 'Intensitas bermain mulai rutin hampir setiap hari. Jika dibiarkan tanpa jeda, ini bisa menjadi kebiasaan yang mengkristal dan sulit diubah.';
                                $saran = 'Tetapkan "Hari Libur Gadget" (Screen-Free Days), misalnya Senin sampai Kamis puasa gadget, baru boleh bermain saat akhir pekan.';
                            } elseif ($p2 == 4) { // 6 Hari
                                $tingkatKecanduan = 'Kecanduan Berat';
                                $alasan = 'Ananda hampir tidak pernah lepas dari gadget setiap harinya. Ini pola yang berisiko tinggi bagi perkembangan otak dan kemampuan sosialnya.';
                                $saran = 'Wajib potong durasi. Harus ada hari di mana ia benar-benar lepas dari layar untuk "mengistirahatkan" sistem sarafnya dari stimulasi digital.';
                            } else { // P2 == 5 (Setiap Hari) -> Masuk Node 1.7 (Cek P16)
                                // P16: Bohong ke orang tua soal gadget
                                if ($p16 == 1) { // TP
                                    $tingkatKecanduan = 'Tidak Kecanduan';
                                    $alasan = 'Walau sering main, Ananda jujur dan terbuka kepada orang tua. Komunikasi yang baik ini adalah kunci pengawasan yang efektif.';
                                    $saran = 'Hargai kejujurannya. Ajak diskusi tentang konten apa yang ia tonton agar Ananda merasa didengar, bukan dihakimi.';
                                } elseif ($p16 == 2 || $p16 == 3) { // Jarang/Kadang
                                    $tingkatKecanduan = 'Kecanduan Ringan';
                                    $alasan = 'Ada rasa takut atau enggan diawasi, sehingga Ananda mulai menyembunyikan aktivitas gadgetnya atau berbohong kecil.';
                                    $saran = 'Hindari memarahinya dengan keras saat ia jujur. Bangun rasa aman agar ia mau bercerita terbuka kembali kepada Bunda/Ayah.';
                                } else { // Sering/Selalu
                                    $tingkatKecanduan = 'Kecanduan Berat';
                                    $alasan = 'Hubungan saling percaya mulai rusak karena gadget. Ananda memilih berbohong dan manipulatif demi mempertahankan akses ke dunia mayanya.';
                                    $saran = 'Pasang aplikasi Parental Control secara transparan. Jelaskan baik-baik bahwa ini adalah bentuk perlindungan dan kasih sayang orang tua, bukan pengekangan.';
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
