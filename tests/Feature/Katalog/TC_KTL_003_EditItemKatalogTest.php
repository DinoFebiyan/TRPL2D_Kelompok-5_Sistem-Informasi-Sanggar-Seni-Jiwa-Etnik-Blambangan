<?php

namespace Tests\Feature;

use App\Models\Katalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-KTL-003 : Superadmin mengedit item katalog serta menambah/mengganti foto,
 * dan perubahan tampil pada katalog publik.
 */
class TC_KTL_003_EditItemKatalogTest extends TestCase
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

    public function test_ktl_003_superadmin_mengedit_item_katalog(): void
    {
        $superadmin = $this->buatSuperadmin();
        $katalog = $this->buatKatalog(['user_id' => $superadmin->id]);

        $data = $this->dataKatalogValid([
            'nama_katalog' => 'Kostum Gandrung Revisi',
            'stok' => 9,
            'status' => 'tidak tersedia',
        ]);
        unset($data['foto']);

        $response = $this->actingAs($superadmin)
            ->put(route('superadmin.update-katalog', $katalog->id), $data);

        $response->assertRedirect(route('superadmin.kelola-katalog'));

        $this->assertDatabaseHas(Katalog::class, [
            'id' => $katalog->id,
            'nama_katalog' => 'Kostum Gandrung Revisi',
            'stok' => 9,
            'status' => 'tidak tersedia',
        ]);
        $this->assertDatabaseMissing(Katalog::class, [
            'id' => $katalog->id,
            'nama_katalog' => 'Kostum Gandrung',
        ]);
    }
}
