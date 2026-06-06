<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otp';

    // Sesuaikan dengan migration: field-nya 'kode' bukan 'otp_code'
    protected $fillable = ['user_id', 'kode', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

