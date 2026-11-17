<?php

namespace Tests\Feature\Galeri;

use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TC_GAL_003_GaleriPublikKecualiLogoPembinaTest extends TestCase
{
    use RefreshDatabase;

    private function buatFoto(string $kategori, string $namaFile, string $path): Galeri
    {
        return Galeri::create([
            'sumber_id' => 1,
            'path' => $path,
            'nama_file' => $namaFile,
            'kategori' => $kategori,
            'is_watermark' => true,
        ]);
    }

    public function test_gal_003_galeri_publik_menampilkan_media_umum()
    {
        $this->buatFoto('event', 'pentas-gandrung-2026.jpg', 'event/pentas-gandrung-2026.jpg');
        $this->buatFoto('berita', 'dokumentasi-kelompok.jpg', 'berita/dokumentasi-kelompok.jpg');

        $response = $this->get(route('umum.galeri'));

        $response->assertStatus(200);
        $response->assertSee('pentas-gandrung-2026.jpg');
        $response->assertSee('dokumentasi-kelompok.jpg');
    }

    public function test_gal_003_galeri_publik_menyembunyikan_logo_dan_foto_pembina()
    {
        $this->buatFoto('logo', 'logo-rahasia-sanggar.jpg', 'galeri/logo-rahasia-sanggar.jpg');
        $this->buatFoto('pembina', 'foto-pembina-rahasia.jpg', 'galeri/foto-pembina-rahasia.jpg');
        $this->buatFoto('header', 'header-slideshow.jpg', 'header/header-slideshow.jpg');
        $this->buatFoto('profil', 'personel-foto.jpg', 'personel/personel-foto.jpg');
        $this->buatFoto('event', 'pentas-terbuka.jpg', 'event/pentas-terbuka.jpg');

        $response = $this->get(route('umum.galeri'));

        $response->assertStatus(200);
        $response->assertSee('pentas-terbuka.jpg');
        $response->assertDontSee('logo-rahasia-sanggar.jpg');
        $response->assertDontSee('foto-pembina-rahasia.jpg');
        $response->assertDontSee('header-slideshow.jpg');
        $response->assertDontSee('personel-foto.jpg');
    }
}
