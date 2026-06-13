<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Mass Assignment Protection: Mencegah perubahan kolom sensitif secara ilegal
    protected $fillable = [
        'name',
        'email',
        'password',
        'peran',
        'no_handphone',
        'status',
    ];

    // Data Privacy: Menyembunyikan password saat data dikonversi ke Array/JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Security Casting: Otomatis menghash password dan mengonversi tipe data waktu
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /** * Hubungan (Relationships):
     * Seorang User (Admin) bisa membuat banyak data konten.
     */
    public function galeri()
    {
        return $this->hasOne(Galeri::class, 'sumber_id', 'id')->where('kategori', 'admin');
    }

    public function berita()
    {
        return $this->hasMany(Berita::class, 'user_id', 'id');
    }

    public function event()
    {
        return $this->hasMany(Event::class, 'user_id', 'id');
    }

    public function katalog()
    {
        return $this->hasMany(Katalog::class, 'user_id', 'id');
    }
}
