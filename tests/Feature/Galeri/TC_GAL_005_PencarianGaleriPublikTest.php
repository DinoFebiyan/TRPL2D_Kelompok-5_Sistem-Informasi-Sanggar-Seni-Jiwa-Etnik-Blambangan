<?php

namespace Tests\Feature\Galeri;

use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TC_GAL_005_PencarianGaleriPublikTest extends TestCase
{
    use RefreshDatabase;

    private function buatFoto(string $namaFile): Galeri
    {
        return Galeri::create([
            'sumber_id' => 1,
            'path' => 'event/'.$namaFile,
            'nama_file' => $namaFile,
            'kategori' => 'event',
            'is_watermark' => true,
        ]);
    }

    public function test_gal_005_pencarian_menampilkan_hasil_sesuai_kata_kunci()
    {
        $this->buatFoto('pentas-gandrung-2026.jpg');
        $this->buatFoto('latihan-rutin.jpg');

        $response = $this->get(route('umum.galeri', ['search' => 'gandrung']));

        $response->assertStatus(200);
        $response->assertSee('pentas-gandrung-2026.jpg');
        $response->assertDontSee('latihan-rutin.jpg');
    }

    public function test_gal_005_pencarian_tanpa_hasil_menampilkan_pesan_kosong()
    {
        $this->buatFoto('pentas-gandrung-2026.jpg');

        $response = $this->get(route('umum.galeri', ['search' => 'zzzkosongtotal']));

        $response->assertStatus(200);
        $response->assertSee('Belum ada dokumentasi foto untuk kriteria pencarian ini.');
        $response->assertDontSee('pentas-gandrung-2026.jpg');
    }
}
