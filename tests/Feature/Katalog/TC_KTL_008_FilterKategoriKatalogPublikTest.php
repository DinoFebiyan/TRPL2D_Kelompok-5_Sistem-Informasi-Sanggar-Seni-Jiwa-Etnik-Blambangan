<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-KTL-008 : Filter kategori katalog publik (Kostum Tari, Aksesoris,
 * Properti, Lainnya) beserta kombinasi dengan pencarian.
 */
class TC_KTL_008_FilterKategoriKatalogPublikTest extends TestCase
{
    use DataUji;
    use RefreshDatabase;

    public function test_ktl_008_filter_kategori_katalog_publik(): void
    {
        $this->buatKatalog(['nama_katalog' => 'Kostum Gandrung', 'kategori' => 'Kostum']);
        $this->buatKatalog(['nama_katalog' => 'Udeng Blambangan', 'kategori' => 'Aksesoris']);

        $response = $this->get(route('umum.katalog', ['kategori' => ['Aksesoris']]));

        $response->assertOk();
        $response->assertSee('Udeng Blambangan');
        $response->assertDontSee('Kostum Gandrung');
    }
}
