<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Galeri extends Model
{
    protected $table = 'galeri';

    protected $fillable = [
        'sumber_id',
        'path',
        'nama_file',
        'kategori',
        'is_watermark',
        'created_at',
        'updated_at'
    ];

    
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'sumber_id');
    }

        public function berita(): BelongsTo
        {
            return $this->belongsTo(Berita::class, 'sumber_id');
        }

        public function katalog(): BelongsTo
        {
            return $this->belongsTo(Katalog::class, 'sumber_id');
        }

        public function admin(): BelongsTo
        {
            return $this->belongsTo(User::class, 'sumber_id');
        }

        public function profilSanggar(): BelongsTo
        {
            return $this->belongsTo(ProfilSanggar::class, 'sumber_id');
        }

        
};