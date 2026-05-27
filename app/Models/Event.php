<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $table = 'event';

    
    protected $fillable = [
        'nama_event', 
        'deskripsi',
        'tanggal', 
        'jam', 
        'lokasi', 
        'kategori', 
        'deskripsi', 
        'status', 
        'user_id', 
        'galeri_id'
    ];

    
    protected $casts = [
        'tanggal' => 'date',
        'jam' => 'datetime:H:i',
    ];

    /**
     * Relasi ke User (Pembuat Event)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function galeri(): HasMany
    {
        return $this->hasMany(Galeri::class, 'sumber_id');
    }


    
}