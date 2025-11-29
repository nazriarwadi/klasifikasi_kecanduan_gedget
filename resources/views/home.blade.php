@extends('layouts.main')

@section('container')
    <section class="hero-section">
        <div class="row align-items-center w-100">
            <div class="col-md-6">
                <h1 class="fw-bold display-4">
                    Selamat Datang di <br> Website <span class="text-custom-red">GadgetCare</span>
                </h1>
                <p class="lead mt-3 text-muted">
                    Website ini akan membantu anda dalam menilai tingkat kecanduan gadget dan memberikan solusi terbaik.
                </p>

                {{-- 1. Tombol Mulai Tes (Selalu Muncul) --}}
                <a href="{{ route('tes.kecanduan') }}" class="btn btn-custom-red btn-lg mt-3 rounded-pill px-4">
                    Mulai Tes Sekarang
                </a>

                {{-- 2. Tombol Lihat Hasil (Hanya Muncul Jika User Pernah Tes / Ada Session) --}}
                @if (session()->has('id_tes_terakhir'))
                    <a href="{{ route('tes.hasil.terakhir') }}"
                        class="btn btn-outline-danger btn-lg mt-3 ms-2 rounded-pill px-4">
                        <i class="bi bi-clock-history me-1"></i> Lihat Hasil Tes
                    </a>
                @endif

            </div>

            <div class="col-md-6 text-center">
                <img src="{{ asset('img/kids-illustration.png') }}" alt="Anak Bahagia" class="img-fluid"
                    style="max-height: 400px;">
            </div>
        </div>
    </section>
@endsection
