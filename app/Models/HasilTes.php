<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilTes extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika nama modelnya singular dari nama tabel)
    protected $table = 'hasil_tes';

    // Mengizinkan semua kolom diisi secara massal
    // (Ini lebih cepat daripada menulis $fillable untuk 20+ kolom)
    protected $guarded = ['id'];
}
