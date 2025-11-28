<!DOCTYPE html>
<html>

<head>
    <title>Laporan Hasil Tes GadgetCare</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #E55353;
            /* Merah GadgetCare */
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 150px;
        }

        .result-box {
            border: 1px solid #333;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .score {
            font-size: 40px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .category {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 15px;
            display: inline-block;
            color: white;
            border-radius: 5px;
        }

        /* Warna Kategori Manual untuk PDF */
        .cat-aman {
            background-color: #28a745;
        }

        .cat-ringan {
            background-color: #ffc107;
            color: black;
        }

        .cat-berat {
            background-color: #dc3545;
        }

        .analysis-section {
            margin-bottom: 20px;
        }

        .analysis-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
            padding-bottom: 5px;
            font-size: 16px;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
        }

        .timestamp {
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>GadgetCare</h1>
        <p>Sistem Klasifikasi Tingkat Kecanduan Gadget Pada Anak</p>
        <p><em>Menggunakan Algoritma Decision Tree C5.0</em></p>
    </div>

    <h3 style="margin-bottom: 10px;">Data Responden</h3>
    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>: {{ $hasil->nama }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td>: {{ $hasil->kelas }} (Sekolah Dasar)</td>
        </tr>
        <tr>
            <td class="label">Umur</td>
            <td>: {{ $hasil->umur }} Tahun</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td>: {{ $hasil->jenis_kelamin }}</td>
        </tr>
    </table>

    <div class="result-box">
        <div>Total Skor Algoritma:</div>
        <div class="score">{{ $hasil->total_skor }} / 100</div>

        @php
            $class = 'cat-berat'; // Default
            if ($hasil->tingkat_kecanduan == 'Tidak Kecanduan') {
                $class = 'cat-aman';
            } elseif ($hasil->tingkat_kecanduan == 'Kecanduan Ringan') {
                $class = 'cat-ringan';
            }
        @endphp

        <div class="category {{ $class }}">
            {{ $hasil->tingkat_kecanduan }}
        </div>
    </div>

    <div class="analysis-section">
        <div class="analysis-title">Analisis Model C5.0:</div>
        <p style="text-align: justify; line-height: 1.5;">
            {{ $hasil->alasan }}
        </p>
    </div>

    <div class="analysis-section">
        <div class="analysis-title">Rekomendasi Tindakan:</div>
        <p style="text-align: justify; line-height: 1.5;">
            {{ $hasil->saran }}
        </p>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y') }}</p>
        <br><br><br>
        <p>( Orang Tua / Wali )</p>
    </div>

    <div class="timestamp">
        Dokumen ini digenerate secara otomatis oleh Sistem GadgetCare.
    </div>

</body>

</html>
