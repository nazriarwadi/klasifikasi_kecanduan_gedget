@extends('layouts.main')

@section('container')
    <div class="row justify-content-center py-5">
        <div class="col-lg-9 text-center">

            <div>
                <h2 class="fw-bold mb-2">Hasil Tes Kecanduan</h2>
                <p class="text-muted mb-4 fst-italic">
                    <i class="bi bi-cpu me-1"></i> Model <strong>Decision Tree C5.0</strong> telah memproses jawaban Anda.
                </p>
            </div>

            @php
                $bgClass = '';
                $icon = '';
                $textClass = '';

                // Logika Tampilan (Warna & Ikon)
                if ($hasil->tingkat_kecanduan == 'Tidak Kecanduan') {
                    $bgClass = 'bg-success';
                    $textClass = 'text-success';
                    $icon = '😊';
                } elseif ($hasil->tingkat_kecanduan == 'Kecanduan Ringan') {
                    $bgClass = 'bg-warning';
                    $textClass = 'text-warning';
                    $icon = '😐';
                } else {
                    $bgClass = 'bg-danger';
                    $textClass = 'text-danger';
                    $icon = '😫';
                }
            @endphp

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-4">

                <div class="{{ $bgClass }} text-white py-4">
                    <h1 class="display-1">{{ $icon }}</h1>
                    <h3 class="fw-bold mt-2 text-uppercase">{{ $hasil->tingkat_kecanduan }}</h3>
                </div>

                <div class="card-body p-5 text-start">

                    <div class="row mb-4 border-bottom pb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Nama Lengkap:</small>
                            <h5 class="fw-bold">{{ $hasil->nama }}</h5>
                        </div>
                        <div class="col-3">
                            <small class="text-muted d-block">Umur:</small>
                            <h5 class="fw-bold">{{ $hasil->umur }} Thn</h5>
                        </div>
                        <div class="col-3">
                            <small class="text-muted d-block">Kelas:</small>
                            <h5 class="fw-bold">{{ $hasil->kelas }} SD</h5>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <h5 class="text-muted mb-2">Total Skor Algoritma:</h5>
                        <h1 class="display-4 fw-bold {{ $textClass }} mb-0">{{ $hasil->total_skor }} <span
                                class="fs-5 text-muted">/ 100</span></h1>
                    </div>

                    <div class="alert alert-primary border-0 mt-4 shadow-sm"
                        style="background-color: #e7f1ff; color: #0c5460;">
                        <h5 class="fw-bold"><i class="bi bi-search me-2"></i>Analisis Model C5.0:</h5>
                        <p class="mb-0 text-justify">{{ $hasil->alasan }}</p>
                    </div>

                    <div class="alert alert-light border mt-3">
                        <h5 class="fw-bold text-muted"><i class="bi bi-lightbulb-fill me-2 text-warning"></i>Rekomendasi
                            Tindakan:</h5>
                        <p class="mb-0 text-muted text-justify">{{ $hasil->saran }}</p>
                    </div>

                </div>
            </div>

            <div class="mt-4">

                <a href="{{ route('tes.cetak', $hasil->id) }}"
                    class="btn btn-primary btn-lg px-4 me-2 mb-2 rounded-pill shadow-sm">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Download PDF
                </a>

                <a href="{{ route('data.grafik') }}"
                    class="btn btn-info btn-lg px-4 me-2 mb-2 rounded-pill shadow-sm text-white">
                    <i class="bi bi-bar-chart-fill me-2"></i> Data Grafik
                </a>

                <a href="{{ route('tes.kecanduan') }}"
                    class="btn btn-outline-secondary btn-lg px-4 me-2 mb-2 rounded-pill">
                    <i class="bi bi-arrow-repeat me-2"></i> Ulangi Tes
                </a>

                <a href="{{ route('home') }}" class="btn btn-custom-red btn-lg px-4 mb-2 rounded-pill shadow-sm">
                    <i class="bi bi-house-door-fill me-2"></i> Beranda
                </a>

            </div>

        </div>
    </div>
@endsection
