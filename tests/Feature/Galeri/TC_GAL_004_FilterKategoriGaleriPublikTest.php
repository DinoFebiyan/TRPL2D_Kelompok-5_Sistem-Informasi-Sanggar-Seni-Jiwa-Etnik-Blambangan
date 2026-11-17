<?php

namespace Tests\Feature\Galeri;

use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TC_GAL_004_FilterKategoriGaleriPublikTest extends TestCase
{
    use RefreshDatabase;

    private function buatFoto(string $kategori, string $namaFile): Galeri
    {
        return Galeri::create([
            'sumber_id' => 1,
            'path' => $kategori.'/'.$namaFile,
            'nama_file' => $namaFile,
            'kategori' => $kategori,
            'is_watermark' => true,
        ]);
    }

    public function test_gal_004_filter_kategori_event_hanya_menampilkan_event()
    {
        $this->buatFoto('event', 'pentas-gandrung.jpg');
        $this->buatFoto('berita', 'liputan-media.jpg');
        $this->buatFoto('katalog', 'kostum-nagasekha.jpg');

        $response = $this->get(route('umum.galeri', ['kategori' => ['event']]));

        $response->assertStatus(200);
        $response->assertSee('pentas-gandrung.jpg');
        $response->assertDontSee('liputan-media.jpg');
        $response->assertDontSee('kostum-nagasekha.jpg');
    }

    public function test_gal_004_filter_kategori_berita_dan_katalog()
    {
        $this->buatFoto('event', 'pentas-gandrung.jpg');
        $this->buatFoto('berita', 'liputan-media.jpg');
        $this->buatFoto('katalog', 'kostum-nagasekha.jpg');

        $response = $this->get(route('umum.galeri', ['kategori' => ['berita', 'katalog']]));

        $response->assertStatus(200);
        $response->assertSee('liputan-media.jpg');
        $response->assertSee('kostum-nagasekha.jpg');
        $response->assertDontSee('pentas-gandrung.jpg');
    }

    public function test_gal_004_tanpa_filter_menampilkan_semua_kategori_umum()
    {
        $this->buatFoto('event', 'pentas-gandrung.jpg');
        $this->buatFoto('berita', 'liputan-media.jpg');

        $response = $this->get(route('umum.galeri'));

        $response->assertStatus(200);
        $response->assertSee('pentas-gandrung.jpg');
        $response->assertSee('liputan-media.jpg');
    }
}
