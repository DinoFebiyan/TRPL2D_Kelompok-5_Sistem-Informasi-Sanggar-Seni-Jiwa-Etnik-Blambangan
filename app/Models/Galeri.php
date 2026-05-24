<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    // Mapping ke nama tabel bahasa Indonesia
    protected $table = 'galeri';

    protected $fillable = ['sumber_id','path', 'nama_file', 'kategori', 'is_watermark'];

    // Type Safety: Memastikan status watermark selalu terbaca sebagai true/false
    protected $casts = [
        'is_watermark' => 'boolean',
    ];
}