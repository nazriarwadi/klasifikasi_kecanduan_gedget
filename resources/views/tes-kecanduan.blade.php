@extends('layouts.main')

@section('container')
    <div class="row justify-content-center py-5">
        <div class="col-lg-10">

            <h2 class="text-center fw-bold mb-4">Tes Kecanduan Gadget</h2>

            <!-- PROGRESS BAR (Hanya muncul saat masuk sesi pertanyaan) -->
            <div id="progress-container" class="d-none flex-column align-items-center mb-5">
                <div class="progress" style="height: 8px; width: 200px; background-color: #e9ecef;">
                    <div id="progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
                </div>
                <small id="progress-text" class="text-muted mt-2 fw-bold">Part 1 / 4</small>
            </div>

            <!-- FORM UTAMA (Satu Form untuk Semua) -->
            <form action="{{ route('tes.simpan') }}" method="POST" id="wizardForm">
                @csrf

                <!-- ================= STEP 0: DATA DIRI ================= -->
                <div class="step-section" id="step-0">
                    <h4 class="mb-4 fw-bold">Lengkapi Data Diri</h4>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control p-3 rounded-3" name="nama" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Kelas</label>
                        <select class="form-select p-3 rounded-3" name="kelas" required>
                            <option value="" selected disabled>Pilih Kelas</option>
                            @for ($i = 3; $i <= 6; $i++)
                                <option value="{{ $i }}">{{ $i }} (Sekolah Dasar)</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Umur</label>
                        <select class="form-select p-3 rounded-3" name="umur" required>
                            <option value="" selected disabled>Pilih Umur</option>
                            @for ($i = 8; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ $i }} Tahun</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Jenis Kelamin</label>
                        <select class="form-select p-3 rounded-3" name="jenis_kelamin" required>
                            <option value="" selected disabled>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <!-- Type Button agar tidak submit, onclick panggil fungsi next() -->
                        <button type="button" class="btn btn-custom-red px-5 py-2 rounded-3" onclick="nextStep(0)">
                            Next
                        </button>
                    </div>
                </div>

                <!-- ================= STEP 1: PART 1 (Soal 1-5) ================= -->
                <div class="step-section d-none" id="step-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @php
                            $pilihanGanda = [];

                            if ($i == 1) {
                                // KHUSUS NO 1
                                $pilihanGanda = [
                                    '1 jam' => 1,
                                    '2 jam' => 2,
                                    '3 jam' => 3,
                                    '4 jam' => 4,
                                    '5 jam' => 5,
                                ];
                            } elseif ($i == 2) {
                                // KHUSUS NO 2
                                $pilihanGanda = [
                                    '1 hari' => 1,
                                    '2–3 hari' => 2,
                                    '4–5 hari' => 3,
                                    '6 hari' => 4,
                                    'Setiap hari (7 hari)' => 5,
                                ];
                            } else {
                                // FREKUENSI BIASA (No 3, 4, 5)
                                $pilihanGanda = [
                                    'Tidak pernah' => 1,
                                    'Jarang' => 2,
                                    'Kadang-kadang' => 3,
                                    'Sering' => 4,
                                    'Selalu' => 5,
                                ];
                            }
                        @endphp

                        <div class="mb-5">
                            <p class="fw-bold mb-2">{{ $i }}. {{ $pertanyaan[$i] ?? 'Pertanyaan ' . $i }}</p>
                            <div class="d-flex flex-wrap gap-4">
                                @foreach ($pilihanGanda as $label => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="p{{ $i }}"
                                            id="p{{ $i }}_{{ $val }}" value="{{ $val }}"
                                            required>
                                        <label class="form-check-label"
                                            for="p{{ $i }}_{{ $val }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endfor

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-4 rounded-3"
                            onclick="prevStep(1)">Kembali</button>
                        <button type="button" class="btn btn-custom-red px-5 rounded-3" onclick="nextStep(1)">Next (Part
                            2)</button>
                    </div>
                </div>

                <div class="step-section d-none" id="step-2">
                    @for ($i = 6; $i <= 10; $i++)
                        @php
                            $pilihanGanda = [];

                            if ($i == 6) {
                                // SKALA PERSETUJUAN (No 6)
                                $pilihanGanda = [
                                    'Tidak pernah' => 1,
                                    'Jarang' => 2,
                                    'Kadang-kadang' => 3,
                                    'Sering' => 4,
                                    'Selalu' => 5,
                                ];
                            } else {
                                // FREKUENSI BIASA (No 7, 8, 9, 10)
                                $pilihanGanda = [
                                    'Tidak pernah' => 1,
                                    'Jarang' => 2,
                                    'Kadang-kadang' => 3,
                                    'Sering' => 4,
                                    'Selalu' => 5,
                                ];
                            }
                        @endphp

                        <div class="mb-5">
                            <p class="fw-bold mb-2">{{ $i }}. {{ $pertanyaan[$i] ?? 'Pertanyaan ' . $i }}</p>
                            <div class="d-flex flex-wrap gap-4">
                                @foreach ($pilihanGanda as $label => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="p{{ $i }}"
                                            id="p{{ $i }}_{{ $val }}" value="{{ $val }}"
                                            required>
                                        <label class="form-check-label"
                                            for="p{{ $i }}_{{ $val }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endfor

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-4 rounded-3"
                            onclick="prevStep(2)">Kembali</button>
                        <button type="button" class="btn btn-custom-red px-5 rounded-3" onclick="nextStep(2)">Next (Part
                            3)</button>
                    </div>
                </div>

                <div class="step-section d-none" id="step-3">
                    @for ($i = 11; $i <= 15; $i++)
                        @php
                            $pilihanGanda = [];

                            if ($i == 11 || $i == 15) {
                                // SKALA PERSETUJUAN (No 11, 15)
                                $pilihanGanda = [
                                    'Tidak pernah' => 1,
                                    'Jarang' => 2,
                                    'Kadang-kadang' => 3,
                                    'Sering' => 4,
                                    'Selalu' => 5,
                                ];
                            } else {
                                // FREKUENSI BIASA (No 12, 13, 14)
                                $pilihanGanda = [
                                    'Tidak pernah' => 1,
                                    'Jarang' => 2,
                                    'Kadang-kadang' => 3,
                                    'Sering' => 4,
                                    'Selalu' => 5,
                                ];
                            }
                        @endphp

                        <div class="mb-5">
                            <p class="fw-bold mb-2">{{ $i }}. {{ $pertanyaan[$i] ?? 'Pertanyaan ' . $i }}</p>
                            <div class="d-flex flex-wrap gap-4">
                                @foreach ($pilihanGanda as $label => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="p{{ $i }}"
                                            id="p{{ $i }}_{{ $val }}" value="{{ $val }}"
                                            required>
                                        <label class="form-check-label"
                                            for="p{{ $i }}_{{ $val }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endfor

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-4 rounded-3"
                            onclick="prevStep(3)">Kembali</button>
                        <button type="button" class="btn btn-custom-red px-5 rounded-3" onclick="nextStep(3)">Next (Part
                            4)</button>
                    </div>
                </div>

                <div class="step-section d-none" id="step-4">
                    @for ($i = 16; $i <= 20; $i++)
                        @php
                            $pilihanGanda = [];

                            if ($i == 20) {
                                // SKALA PERSETUJUAN (No 20)
                                $pilihanGanda = [
                                    'Tidak pernah' => 1,
                                    'Jarang' => 2,
                                    'Kadang-kadang' => 3,
                                    'Sering' => 4,
                                    'Selalu' => 5,
                                ];
                            } else {
                                // FREKUENSI BIASA (No 16, 17, 18, 19)
                                $pilihanGanda = [
                                    'Tidak pernah' => 1,
                                    'Jarang' => 2,
                                    'Kadang-kadang' => 3,
                                    'Sering' => 4,
                                    'Selalu' => 5,
                                ];
                            }
                        @endphp

                        <div class="mb-5">
                            <p class="fw-bold mb-2">{{ $i }}. {{ $pertanyaan[$i] ?? 'Pertanyaan ' . $i }}</p>
                            <div class="d-flex flex-wrap gap-4">
                                @foreach ($pilihanGanda as $label => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="p{{ $i }}"
                                            id="p{{ $i }}_{{ $val }}" value="{{ $val }}"
                                            required>
                                        <label class="form-check-label"
                                            for="p{{ $i }}_{{ $val }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endfor

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-4 rounded-3"
                            onclick="prevStep(4)">Kembali</button>
                        <button type="submit" class="btn btn-custom-red px-5 rounded-3">Kirim Jawaban Dan Lihat Hasil
                            Tes</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- JAVASCRIPT UNTUK PINDAH HALAMAN TANPA RELOAD -->
    <script>
        function nextStep(currentStep) {
            // 1. Validasi: Cek apakah input di step ini sudah diisi semua
            if (!validateStep(currentStep)) {
                alert("Harap lengkapi semua isian sebelum melanjutkan!");
                return;
            }

            // 2. Sembunyikan step sekarang
            document.getElementById('step-' + currentStep).classList.add('d-none');

            // 3. Tampilkan step selanjutnya
            let nextStep = currentStep + 1;
            document.getElementById('step-' + nextStep).classList.remove('d-none');

            // 4. Update Progress Bar
            updateProgress(nextStep);

            // Scroll ke atas agar user nyaman
            window.scrollTo(0, 0);
        }

        function prevStep(currentStep) {
            document.getElementById('step-' + currentStep).classList.add('d-none');
            let prevStep = currentStep - 1;
            document.getElementById('step-' + prevStep).classList.remove('d-none');
            updateProgress(prevStep);
            window.scrollTo(0, 0);
        }

        function updateProgress(step) {
            // Jika masih di Data Diri (step 0), sembunyikan progress bar
            if (step === 0) {
                document.getElementById('progress-container').classList.remove('d-flex');
                document.getElementById('progress-container').classList.add('d-none');
                return;
            }

            // Tampilkan progress bar untuk step 1-4
            document.getElementById('progress-container').classList.remove('d-none');
            document.getElementById('progress-container').classList.add('d-flex');

            let percent = (step / 4) * 100;
            document.getElementById('progress-bar').style.width = percent + '%';
            document.getElementById('progress-text').innerText = 'Part ' + step + ' / 4';
        }

        // Fungsi Validasi Sederhana
        function validateStep(step) {
            let inputs = document.getElementById('step-' + step).querySelectorAll('input, select');
            let valid = true;

            // Cek input text/select
            inputs.forEach(input => {
                if (input.type !== 'radio' && !input.value) valid = false;
            });

            // Cek Radio Button (Khusus soal)
            if (step > 0) {
                // Hitung soal di step ini (misal step 1: soal 1-5)
                let start = (step - 1) * 5 + 1;
                let end = step * 5;

                for (let i = start; i <= end; i++) {
                    let radioGroup = document.getElementsByName('p' + i);
                    let checked = false;
                    for (let radio of radioGroup) {
                        if (radio.checked) checked = true;
                    }
                    if (!checked) valid = false;
                }
            }

            return valid;
        }
    </script>
@endsection
