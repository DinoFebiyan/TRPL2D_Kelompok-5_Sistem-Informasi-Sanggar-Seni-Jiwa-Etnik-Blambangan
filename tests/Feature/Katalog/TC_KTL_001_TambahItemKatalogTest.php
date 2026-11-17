<?php

namespace Tests\Feature;

use App\Models\Galeri;
use App\Models\Katalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-KTL-001 : Superadmin menambah item katalog valid
 * (nama, kategori, stok, deskripsi, status, foto) dengan foto ber-watermark otomatis.
 */
class TC_KTL_001_TambahItemKatalogTest extends TestCase
{
    use DataUji;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->siapkanFolderKatalog();
    }

    protected function tearDown(): void
    {
        $this->bersihkanFolderKatalog();
        parent::tearDown();
    }

    public function test_ktl_001_superadmin_menambah_item_katalog(): void
    {
        $superadmin = $this->buatSuperadmin();

        $response = $this->actingAs($superadmin)
            ->post(route('superadmin.store-katalog'), $this->dataKatalogValid());

        $response->assertRedirect(route('superadmin.kelola-katalog'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas(Katalog::class, [
            'nama_katalog' => 'Kostum Gandrung',
            'kategori' => 'Kostum',
            'stok' => 5,
            'status' => 'tersedia',
            'user_id' => $superadmin->id,
        ]);

        $this->assertDatabaseHas(Galeri::class, [
            'kategori' => 'katalog',
            'is_watermark' => true,
        ]);
    }
}
