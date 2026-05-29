<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Personel extends Model
{
    protected $table = 'personel';

    // Bersihkan 'galeri_id' dari fillable jika Anda menggunakan sistem satu personel banyak foto di tabel galeri
    protected $fillable = ['nama', 'jabatan', 'no_handphone', 'instagram', 'peran', 'status'];

    /**
     * Hubungkan Personel ke tabel Galeri (Satu personel memiliki banyak foto)
     */
    public function galeri(): HasMany
    {
        // Menghubungkan lewat kolom 'sumber_id' dan memfilter hanya kategori 'profil'
        return $this->hasMany(Galeri::class, 'sumber_id', 'id')->where('kategori', 'profil');
    }
}