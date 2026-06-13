<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengaturanSistem extends Model
{
    protected $table = 'pengaturan_sistem';

    protected $fillable = [
        'link_maps',
        'link_wa',
        'link_email',
        'link_instagram',
        'link_tiktok',
    ];

    /**
     * Relasi mengambil kumpulan foto slide header utama.
     * Menggunakan foreign key sumber_id yang diarahkan ke id tabel ini.
     */
    public function headers(): HasMany
    {
        return $this->hasMany(Galeri::class, 'sumber_id', 'id')->where('kategori', 'header');
    }
}
