<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Galeri extends Model
{
    protected $table = 'galeri';

    protected $fillable = [
        'nama_file',
        'path',
        'kategori',
        'event_id', // Pastikan kolom ini ada di database dan di sini
    ];

    
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}