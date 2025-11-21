<?php

use Illuminate\Support\Facades\Route;

// Grouping agar lebih terorganisir
Route::prefix('/')->group(function () {

    // Halaman Beranda (Handle '/' dan '/beranda')
    Route::view('/', 'home')->name('home');
    Route::view('/beranda', 'home');

    // Halaman Informasi
    Route::view('/informasi', 'informasi')->name('informasi');

    // Halaman Lainnya (Pastikan file view-nya sudah ada)
    // Route::view('/tes-kecanduan', 'tes')->name('tes');
    // Route::view('/data-grafik', 'grafik')->name('grafik');
});
