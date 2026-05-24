<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';

    // PERBAIKAN: Hapus 'galeri_id' karena ID berita yang disimpan di tabel galeri (sumber_id)
    protected $fillable = ['judul', 'isi_berita', 'jenis_berita', 'status', 'user_id'];

    protected $casts = [
        'tgl_terbit' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * PERBAIKAN: Karena tabel galeri menyimpan 'sumber_id', 
     * Berita memiliki (hasMany/hasOne) foto di dalam galeri.
     */
    public function galeri()
    {
        return $this->hasMany(Galeri::class, 'sumber_id', 'id')->where('kategori', 'berita');
    }
}