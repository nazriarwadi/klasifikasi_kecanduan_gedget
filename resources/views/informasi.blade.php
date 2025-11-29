@extends('layouts.main')

@section('container')
    <div class="py-5">

        <div class="text-center mb-5">
            <h2 class="fw-bold display-5">Informasi Sistem</h2>
            <p class="text-muted">Edukasi dan informasi mengenai penggunaan gadget pada anak</p>
            <div class="mx-auto mt-3" style="width: 60px; height: 3px; background-color: var(--main-red);"></div>
        </div>

        <div class="row align-items-center mb-5 pb-3">
            <div class="col-md-7">
                <h3 class="fw-bold mb-3"><span class="text-custom-red">Bahaya Gadget</span> Untuk Anak</h3>
                <p class="lead text-muted">Penggunaan gadget yang berlebihan dapat menghambat tumbuh kembang anak secara
                    fisik dan mental.</p>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><i class="bi bi-exclamation-circle-fill text-danger me-2"></i> <strong>Gangguan
                            Tidur:</strong> Cahaya biru layar menghambat hormon melatonin.</li>
                    <li class="mb-2"><i class="bi bi-exclamation-circle-fill text-danger me-2"></i> <strong>Masalah
                            Penglihatan:</strong> Risiko mata lelah hingga rabun jauh.</li>
                    <li class="mb-2"><i class="bi bi-exclamation-circle-fill text-danger me-2"></i> <strong>Kurang
                            Sosialisasi:</strong> Anak menjadi tertutup dan sulit berinteraksi langsung.</li>
                    <li class="mb-2"><i class="bi bi-exclamation-circle-fill text-danger me-2"></i> <strong>Emosi Tidak
                            Stabil:</strong> Mudah marah (tantrum) jika gadget diambil.</li>
                </ul>
            </div>
            <div class="col-md-5 text-center">
                <img src="{{ asset('img/bahaya_gadget.png') }}" class="img-fluid rounded shadow-sm" alt="Bahaya Gadget">
            </div>
        </div>

        <div class="row align-items-center mb-5 pb-3 bg-light p-4 rounded-4">
            <div class="col-md-5 text-center order-md-1 order-2">
                <img src="{{ asset('img/kecanduan.png') }}" class="img-fluid rounded shadow-sm" alt="Solusi Gadget">
            </div>
            <div class="col-md-7 order-md-2 order-1 ps-md-5">
                <h3 class="fw-bold mb-3">Cara Agar Anak <span class="text-custom-red">Tidak Kecanduan</span></h3>
                <p class="text-muted">Peran orang tua sangat vital dalam mengontrol kebiasaan digital anak.</p>
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-custom-red text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                        style="width: 30px; height: 30px;">1</div>
                    <div class="ms-3">
                        <strong>Batasi Waktu Layar:</strong> Buat jadwal tegas (misal: maksimal 1-2 jam sehari).
                    </div>
                </div>
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-custom-red text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                        style="width: 30px; height: 30px;">2</div>
                    <div class="ms-3">
                        <strong>Perbanyak Aktivitas Fisik:</strong> Ajak anak bermain di luar, olahraga, atau membaca buku
                        fisik.
                    </div>
                </div>
                <div class="d-flex align-items-start mb-3">
                    <div class="bg-custom-red text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                        style="width: 30px; height: 30px;">3</div>
                    <div class="ms-3">
                        <strong>Jadilah Contoh (Role Model):</strong> Orang tua juga harus membatasi penggunaan HP di depan
                        anak.
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <h3 class="fw-bold text-center mb-4">Kategori Tingkat Kecanduan</h3>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-3">
                        <div class="card-body">
                            <div class="display-4 text-success mb-3">😊</div>
                            <h5 class="card-title fw-bold text-success">Tidak Kecanduan</h5>
                            <p class="card-text text-muted small">Anak menggunakan gadget dalam batas wajar dan tidak
                                mengganggu aktivitas harian, tidur, atau sosialisasi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-3">
                        <div class="card-body">
                            <div class="display-4 text-warning mb-3">😐</div>
                            <h5 class="card-title fw-bold text-warning">Kecanduan Ringan</h5>
                            <p class="card-text text-muted small">Mulai ada tanda ketergantungan, sesekali menunda tugas
                                atau tidur demi gadget, namun masih bisa dikontrol.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center p-3">
                        <div class="card-body">
                            <div class="display-4 text-danger mb-3">😫</div>
                            <h5 class="card-title fw-bold text-danger">Kecanduan Berat</h5>
                            <p class="card-text text-muted small">Gadget menjadi pusat hidup. Menarik diri dari lingkungan,
                                emosi meledak jika dilarang, dan gangguan fisik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-custom-red text-white rounded-4 p-5 text-center mb-5">
            <h3 class="fw-bold">Bagaimana Sistem Ini Bekerja?</h3>
            <p class="mt-3 w-75 mx-auto">
                Sistem <strong>GadgetCare</strong> menggunakan teknologi <em>Machine Learning</em> dengan algoritma
                <strong>Decision Tree C5.0</strong>. Sistem menganalisis jawaban kuesioner berdasarkan pola data historis
                untuk mengklasifikasikan tingkat kecanduan secara akurat dan objektif.
            </p>
        </div>

    </div>
@endsection
