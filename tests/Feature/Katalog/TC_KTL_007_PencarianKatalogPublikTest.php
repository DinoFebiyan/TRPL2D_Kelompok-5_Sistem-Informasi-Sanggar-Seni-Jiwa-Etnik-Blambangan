<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-KTL-007 : Pencarian katalog publik berdasarkan nama/deskripsi
 * (tidak peka huruf besar-kecil, hasil kosong, karakter khusus).
 */
class TC_KTL_007_PencarianKatalogPublikTest extends TestCase
{
    use DataUji;
    use RefreshDatabase;

    public function test_ktl_007_pencarian_katalog_publik(): void
    {
        $this->buatKatalog(['nama_katalog' => 'Kostum Gandrung']);
        $this->buatKatalog(['nama_katalog' => 'Udeng Blambangan', 'kategori' => 'Aksesoris']);

        $response = $this->get(route('umum.katalog', ['search' => 'Udeng']));

        $response->assertOk();
        $response->assertSee('Udeng Blambangan');
        $response->assertDontSee('Kostum Gandrung');
    }
}
