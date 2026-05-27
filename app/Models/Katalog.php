<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Katalog extends Model
{
    protected $table = 'katalog';

    // PERBAIKAN: Gunakan nama kolom 'nama_katalog' untuk menampung isian input form 'nama_tari'
    protected $fillable = ['nama_katalog', 'kategori', 'stok', 'deskripsi', 'status', 'user_id'];

    protected $casts = [
        'stok' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    // PERBAIKAN: Diubah menjadi HasMany agar satu produk katalog bisa memiliki banyak foto header sekaligus
    public function galeri(): HasMany
    {
        return $this->hasMany(Galeri::class, 'sumber_id', 'id')->where('kategori', 'katalog');
    }
}