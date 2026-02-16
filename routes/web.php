<?php

use App\Http\Controllers\DataGrafikController;
use App\Http\Controllers\TesKecanduanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatistikGlobalController;

Route::prefix('/')->group(function () {

    Route::view('/', 'home')->name('home');
    Route::view('/beranda', 'home');
    Route::view('/informasi', 'informasi')->name('informasi');

    // ROUTE TES KECANDUAN (Panggil Controller)
    Route::get('/tes-kecanduan', [TesKecanduanController::class, 'index'])->name('tes.kecanduan');
    Route::post('/tes-submit', [TesKecanduanController::class, 'store'])->name('tes.simpan');
    Route::get('/tes-hasil/{id}', [TesKecanduanController::class, 'showResult'])->name('tes.hasil');
    Route::get('/data-grafik', [DataGrafikController::class, 'index'])->name('data.grafik');
    // Route Download PDF
    Route::get('/tes-cetak/{id}', [TesKecanduanController::class, 'cetakPdf'])->name('tes.cetak');
    // Route Lihat Hasil Tes Terakhir
    Route::get('/tes/hasil-terakhir', [TesKecanduanController::class, 'lihatHasilTerakhir'])->name('tes.hasil.terakhir');
    Route::get('/statistik-global', [StatistikGlobalController::class, 'index'])->name('statistik.global');

});
