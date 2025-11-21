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
                <a href="#" class="btn btn-custom-red btn-lg mt-3 rounded-pill px-4">Mulai Tes Sekarang</a>
            </div>

            <div class="col-md-6 text-center">
                <img src="{{ asset('img/kids-illustration.png') }}" alt="Anak Bahagia" class="img-fluid"
                    style="max-height: 400px;">
            </div>
        </div>
    </section>
@endsection
