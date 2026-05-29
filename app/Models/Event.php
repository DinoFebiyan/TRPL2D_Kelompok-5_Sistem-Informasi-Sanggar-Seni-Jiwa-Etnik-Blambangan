<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $table = 'event';

    // PERBAIKAN: Sesuaikan dengan nama kolom di migration Anda ('nama', bukan 'nama_event')
    // Serta hapus 'galeri_id' karena ID event dicatat di tabel galeri (sumber_id)
    protected $fillable = [
        'nama', 
        'deskripsi',
        'tanggal', 
        'jam', 
        'lokasi', 
        'kategori', 
        'status', 
        'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke User (Pembuat Event)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke Galeri (Multi-Foto Slideshow)
     */
    public function galeri(): HasMany
    {
        // PERBAIKAN: Hubungkan via sumber_id dan kunci kategori khusus 'event'
        return $this->hasMany(Galeri::class, 'sumber_id', 'id')->where('kategori', 'event');
    }
}