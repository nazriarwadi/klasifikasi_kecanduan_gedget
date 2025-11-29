@extends('layouts.main')

@section('container')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-5">

        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Data Tingkat Kecanduan Gadget</h2>
            <p class="text-muted w-75 mx-auto">
                Data statistik berikut menampilkan persentase tingkat kecanduan berdasarkan hasil tes yang telah dilakukan
                oleh responden.
            </p>
            <div class="mx-auto mt-3" style="width: 60px; height: 3px; background-color: var(--main-red);"></div>
        </div>

        <div class="row mb-5">

            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4"><i class="bi bi-funnel-fill text-custom-red me-2"></i>Filter Data</h5>

                        <form action="{{ route('data.grafik') }}" method="GET">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Kelas</label>
                                <select class="form-select rounded-3" name="kelas">
                                    <option value="">Semua Kelas</option>
                                    @for ($i = 3; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ $filterKelas == $i ? 'selected' : '' }}>Kelas
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Umur</label>
                                <select class="form-select rounded-3" name="umur">
                                    <option value="">Semua Umur</option>
                                    @for ($i = 8; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $filterUmur == $i ? 'selected' : '' }}>
                                            {{ $i }} Tahun</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Jenis Kelamin</label>
                                <select class="form-select rounded-3" name="jenis_kelamin">
                                    <option value="">Semua</option>
                                    <option value="Laki-laki" {{ $filterJK == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="Perempuan" {{ $filterJK == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-custom-red w-100 rounded-pill">Terapkan Filter</button>
                            @if (request()->has('kelas') || request()->has('umur'))
                                <a href="{{ route('data.grafik') }}"
                                    class="btn btn-link text-muted w-100 mt-2 text-decoration-none small">Reset Filter</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                            <div class="card-body p-4 d-flex align-items-center">
                                <div class="display-4 me-3"><i class="bi bi-people-fill"></i></div>
                                <div>
                                    <h2 class="fw-bold mb-0">{{ $totalResponden }}</h2>
                                    <p class="mb-0 opacity-75">Total Responden (Anak)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 bg-danger text-white h-100">
                            <div class="card-body p-4 d-flex align-items-center">
                                <div class="display-4 me-3"><i class="bi bi-exclamation-triangle-fill"></i></div>
                                <div>
                                    <h2 class="fw-bold mb-0">{{ $totalBerat }}</h2>
                                    <p class="mb-0 opacity-75">Kasus Kecanduan Berat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mt-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-center mb-4">Persentase Tingkat Kecanduan</h5>

                        <div style="height: 350px; width: 100%; display: flex; justify-content: center;">
                            @if ($totalResponden > 0)
                                <canvas id="addictionChart"></canvas>
                            @else
                                <div class="d-flex align-items-center justify-content-center text-muted h-100">
                                    <p>Belum ada data untuk filter ini.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const ctx = document.getElementById('addictionChart');

        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut', // Tipe Grafik: Donut (Lingkaran Bolong)
                data: {
                    labels: ['Tidak Kecanduan', 'Kecanduan Ringan', 'Kecanduan Berat'],
                    datasets: [{
                        label: 'Jumlah Anak',
                        // Ambil data dari Controller PHP
                        data: [
                            {{ $dataPie['Tidak Kecanduan'] }},
                            {{ $dataPie['Kecanduan Ringan'] }},
                            {{ $dataPie['Kecanduan Berat'] }}
                        ],
                        backgroundColor: [
                            '#198754', // Hijau (Aman)
                            '#ffc107', // Kuning (Ringan)
                            '#dc3545' // Merah (Berat)
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom', // Posisi label di bawah
                            labels: {
                                padding: 20,
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection
