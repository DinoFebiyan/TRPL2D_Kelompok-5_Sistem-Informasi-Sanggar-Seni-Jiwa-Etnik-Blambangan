<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personel extends Model
{
    protected $table = 'personel';

    // Opsi 1: Tambahkan semua kolom baru ke dalam array $fillable
    protected $fillable = [
        'nia',
        'nama',
        'jabatan',
        'no_handphone',
        'instagram',
        'peran',
        'status',
        'deskripsi',
        'prestasi',
        'masa_bakti',
        'galeri_id',
    ];

    // ATAU Opsi 2 (Alternatif): Menggunakan $guarded agar semua kolom otomatis boleh diisi
    // protected $guarded = [];

    /**
     * Relasi ke galeri khusus foto profil personel
     */
    public function galeri()
    {
        return $this->hasMany(Galeri::class, 'sumber_id')->where('kategori', 'profil');
    }
}