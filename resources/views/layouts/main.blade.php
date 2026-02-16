<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GadgetCare - Sistem Klasifikasi</title>

    <link rel="icon" href="{{ asset('img/logo_gedgetCare.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-custom-red py-3">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('img/logo_gedgetCare.png') }}" alt="Logo" width="40" height="40"
                    class="d-inline-block align-text-top me-2">
                GadgetCare
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('tes.kecanduan') ? 'active' : '' }}"
                            href="{{ route('tes.kecanduan') }}">Tes Kecanduan</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('data.grafik*') ? 'active' : '' }}"
                            href="{{ route('data.grafik') }}">
                            Data Grafik (Personal)
                        </a>
                    </li>

                    {{-- MENU BARU: STATISTIK GLOBAL --}}
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('statistik.global*') ? 'active' : '' }}"
                            href="{{ route('statistik.global') }}">
                            Statistik Global
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('informasi') ? 'active' : '' }}"
                            href="{{ route('informasi') }}">Informasi Sistem</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('container')
    </div>

    <footer class="bg-custom-red text-white text-center py-3">
        <small>@2026 GadgetCare, All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
