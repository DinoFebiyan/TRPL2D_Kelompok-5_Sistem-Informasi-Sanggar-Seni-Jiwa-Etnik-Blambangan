<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;


class KatalogPublikTest extends TestCase
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

   
    public function test_ktl_007_pencarian_katalog_publik(): void
    {
        $this->buatKatalog(['nama_katalog' => 'Kostum Gandrung']);
        $this->buatKatalog(['nama_katalog' => 'Udeng Blambangan', 'kategori' => 'Aksesoris']);

        $response = $this->get(route('umum.katalog', ['search' => 'Udeng']));

        $response->assertOk();
        $response->assertSee('Udeng Blambangan');
        $response->assertDontSee('Kostum Gandrung');
    }

    
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