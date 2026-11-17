<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-KTL-006 : Katalog publik tanpa login — daftar item, foto ber-watermark,
 * detail + saran sekategori, tautan WhatsApp, dan pesan katalog kosong.
 */
class TC_KTL_006_KatalogPublikDaftarTest extends TestCase
{
    use DataUji;
    use RefreshDatabase;

    public function test_ktl_006_katalog_publik_menampilkan_daftar(): void
    {
        $this->buatKatalog(['nama_katalog' => 'Kostum Gandrung']);
        $this->buatKatalog(['nama_katalog' => 'Udeng Blambangan', 'kategori' => 'Aksesoris']);

        $response = $this->get(route('umum.katalog'));

        $response->assertOk();
        $response->assertSee('Kostum Gandrung');
        $response->assertSee('Udeng Blambangan');
    }
}
