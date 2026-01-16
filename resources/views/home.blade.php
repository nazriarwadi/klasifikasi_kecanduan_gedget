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

                {{-- 2. Tombol Lihat Hasil --}}
                {{-- Kita tambahkan ID 'btn-lihat-hasil' untuk target JavaScript --}}
                @if (session()->has('id_tes_terakhir'))
                    <a href="{{ route('tes.hasil.terakhir') }}" id="btn-lihat-hasil"
                        class="btn btn-outline-danger btn-lg mt-3 ms-2 rounded-pill px-4" style="display: none;">
                        {{-- Default di-hidden dulu agar tidak kedip --}}
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

    {{-- SCRIPT PENGONTROL TOMBOL --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil elemen tombol
            var btnHasil = document.getElementById('btn-lihat-hasil');

            // Cek apakah tombol ada (artinya session PHP masih ada)
            if (btnHasil) {
                // Cek apakah session browser (tab) masih menyimpan status tes
                if (sessionStorage.getItem('status_tes_aktif') === 'true') {
                    // Jika YA (User hanya refresh atau pindah menu), Munculkan tombol
                    btnHasil.style.display = 'inline-block';
                } else {
                    // Jika TIDAK (User baru buka browser/tab baru), Hapus tombol
                    // Ini menjaga agar user lain tidak melihat tombol ini
                    btnHasil.remove();

                    // Opsional: Anda bisa memanggil route untuk menghapus session server via AJAX
                    // agar lebih aman, tapi menghapus tombol via JS sudah cukup untuk UI.
                }
            }
        });
    </script>
@endsection
