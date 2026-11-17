<?php

namespace Tests\Feature;

use App\Models\Katalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-KTL-005 : Admin biasa ditolak mengakses manajemen katalog
 * (hak khusus Superadmin), baik melalui menu maupun URL langsung.
 */
class TC_KTL_005_AdminDitolakKelolaKatalogTest extends TestCase
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

    public function test_ktl_005_admin_biasa_ditolak_menambah_katalog(): void
    {
        $response = $this->actingAs($this->buatAdmin())
            ->post(route('superadmin.store-katalog'), $this->dataKatalogValid());

        $response->assertForbidden();
        $this->assertSame(0, Katalog::count());
    }
}
