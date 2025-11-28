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
        Schema::table('hasil_tes', function (Blueprint $table) {
            $table->text('alasan')->nullable()->after('tingkat_kecanduan'); // Menampung Analisis
            $table->text('saran')->nullable()->after('alasan'); // Menampung Saran Spesifik
        });
    }

    public function down()
    {
        Schema::table('hasil_tes', function (Blueprint $table) {
            $table->dropColumn(['alasan', 'saran']);
        });
    }
};
