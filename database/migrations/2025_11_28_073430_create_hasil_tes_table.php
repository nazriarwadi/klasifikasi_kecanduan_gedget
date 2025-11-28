<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('hasil_tes', function (Blueprint $table) {
            $table->id();

            // 1. Data Diri
            $table->string('nama');
            $table->string('kelas'); // Disimpan string agar fleksibel (misal: "6")
            $table->integer('umur');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);

            // 2. Jawaban Pertanyaan (Bobot 1-5)
            // Kita gunakan loop agar codingan lebih ringkas daripada nulis 20 baris
            for ($i = 1; $i <= 20; $i++) {
                $table->tinyInteger('p' . $i); // Menggunakan tinyInteger karena nilainya kecil (1-5)
            }

            // 3. Hasil Perhitungan (Opsional tapi sangat disarankan)
            // Disimpan agar history tes bisa dilihat kembali tanpa hitung ulang
            $table->integer('total_skor')->nullable();
            $table->string('tingkat_kecanduan')->nullable(); // Contoh: "Kecanduan Berat"

            $table->timestamps(); // Created_at dan Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_tes');
    }
};
