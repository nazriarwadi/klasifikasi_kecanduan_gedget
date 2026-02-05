@extends('layouts.main')

@section('container')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-5">

        {{-- HEADER --}}
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Analisis Grafik Personal</h2>
            <div class="mx-auto mt-3" style="width: 60px; height: 3px; background-color: var(--main-red);"></div>
        </div>

        {{--
            CONTAINER 1: TAMPILAN JIKA DATA ADA (SUDAH TES)
            PENTING: Tambahkan style="display: none;" agar defaultnya tersembunyi
            Kita beri ID "konten-grafik-user" untuk dikontrol JS
        --}}
        @if (isset($allowed) && $allowed)
            <div id="konten-grafik-user" style="display: none;">
                <div class="row justify-content-center">
                    <div class="col-md-10">

                        {{-- Info Card User --}}
                        <div class="alert alert-light border shadow-sm rounded-4 d-flex align-items-center mb-4 p-4">
                            <div class="bg-custom-red text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-person-circle fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Halo, {{ $hasil->nama }}!</h5>
                                <p class="mb-0 text-muted small">
                                    Berikut adalah visualisasi pola jawaban Anda.
                                    Tingkat Kecanduan: <span
                                        class="fw-bold text-custom-red">{{ $hasil->tingkat_kecanduan }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="row g-4">
                            {{-- GRAFIK --}}
                            <div class="col-md-7">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body p-4">
                                        <h5 class="fw-bold mb-4 text-center">Komposisi Jawaban Anda</h5>
                                        <div style="height: 300px; width: 100%;">
                                            <canvas id="personalChart"></canvas>
                                        </div>
                                        <div class="mt-3 text-center small text-muted">
                                            <em>Grafik ini menunjukkan seberapa sering Anda memilih skala 1 sampai 5.</em>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- PENJELASAN --}}
                            <div class="col-md-5">
                                <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white">
                                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                                        <h4 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Insight Data</h4>
                                        <p>Grafik di samping memetakan jawaban Anda ke dalam 5 skala intensitas.</p>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <span class="badge bg-white text-primary me-2">Skor 5</span>
                                                Intensitas Sangat Tinggi.
                                            </li>
                                            <li class="mb-2">
                                                <span class="badge bg-white text-primary me-2">Skor 4</span>
                                                Intensitas Tinggi.
                                            </li>
                                            {{-- BAGIAN SKOR 3 DITAMBAHKAN --}}
                                            <li class="mb-2">
                                                <span class="badge bg-white text-primary me-2">Skor 3</span>
                                                Intensitas Sedang (Waspada).
                                            </li>
                                            <li class="mb-2">
                                                <span class="badge bg-white text-primary me-2">Skor 1-2</span>
                                                Aman/Wajar.
                                            </li>
                                        </ul>
                                        <div class="mt-auto pt-3 border-top border-white border-opacity-25">
                                            <small>Semakin banyak porsi warna merah/oranye di grafik, semakin tinggi
                                                indikasi kecanduan Anda.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endif

        {{--
            CONTAINER 2: TAMPILAN JIKA BELUM TES (WARNING)
            Kita beri ID "konten-belum-tes"
            Default style="display: none;" nanti JS yang menentukan kapan muncul
        --}}
        <div id="konten-belum-tes" style="display: none;">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow rounded-4 text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-bar-chart-line display-1 text-muted opacity-25"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Data Grafik Tidak Tersedia</h4>
                        <p class="text-muted mb-4">
                            Anda belum melakukan tes kecanduan gadget atau sesi Anda telah berakhir.
                            <br>Silahkan lakukan tes ulang untuk melihat analisis data.
                        </p>
                        <a href="{{ route('tes.kecanduan') }}" class="btn btn-custom-red rounded-pill px-5 py-2">
                            <i class="bi bi-clipboard-check me-2"></i>Mulai Tes Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- LOGIKA JAVASCRIPT UTAMA --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Cek Penanda Session Browser
            const statusTesAktif = sessionStorage.getItem('status_tes_aktif');

            // Ambil Elemen Container
            const divGrafik = document.getElementById('konten-grafik-user');
            const divWarning = document.getElementById('konten-belum-tes');

            // 2. Logika Penentuan Tampilan
            // Syarat Tampil:
            // a. PHP mengirim data (divGrafik ada/tidak null)
            // b. SessionStorage bernilai 'true' (Browser tidak di-close)

            if (divGrafik && statusTesAktif === 'true') {
                // KONDISI: Session Valid & Data Ada -> TAMPILKAN GRAFIK
                divGrafik.style.display = 'block';
                divWarning.style.display = 'none';

                // Jalankan Render Chart hanya jika divGrafik ditampilkan
                renderChart();
            } else {
                // KONDISI: Session Hilang / Close Browser -> TAMPILKAN WARNING
                // Walaupun PHP mengirim data (divGrafik ada), kita paksa sembunyi
                if (divGrafik) divGrafik.remove(); // Hapus elemen grafik dari DOM agar aman
                divWarning.style.display = 'block';
            }
        });

        // Fungsi Render Chart dipisah agar rapi
        function renderChart() {
            const ctx = document.getElementById('personalChart');
            if (ctx) {
                // Pastikan variabel PHP chartData tersedia
                @if (isset($chartData))
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($chartData['labels']) !!},
                            datasets: [{
                                label: 'Frekuensi Jawaban',
                                data: {!! json_encode($chartData['values']) !!},
                                backgroundColor: ['#198754', '#20c997', '#ffc107', '#fd7e14', '#dc3545'],
                                borderRadius: 5,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                @endif
            }
        }
    </script>
@endsection
