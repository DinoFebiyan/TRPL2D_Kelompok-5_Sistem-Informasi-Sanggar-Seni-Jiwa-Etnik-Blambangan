<?php

namespace Tests\Feature;

use App\Models\Galeri;
use App\Models\Katalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-KTL-004 : Superadmin menghapus item katalog
 * beserta seluruh foto galeri terkait.
 */
class TC_KTL_004_HapusItemKatalogTest extends TestCase
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

    public function test_ktl_004_superadmin_menghapus_item_katalog(): void
    {
        Storage::fake('public');

        $superadmin = $this->buatSuperadmin();
        $katalog = $this->buatKatalog(['user_id' => $superadmin->id]);

        Storage::disk('public')->put('katalog/foto-lama.jpg', 'dummy');
        $galeri = Galeri::create([
            'sumber_id' => $katalog->id,
            'path' => 'katalog/foto-lama.jpg',
            'nama_file' => 'foto-lama.jpg',
            'kategori' => 'katalog',
            'is_watermark' => true,
        ]);

        $response = $this->actingAs($superadmin)
            ->delete(route('superadmin.hapus-katalog', $katalog->id));

        $response->assertRedirect(route('superadmin.kelola-katalog'));

        $this->assertDatabaseMissing(Katalog::class, ['id' => $katalog->id]);
        $this->assertDatabaseMissing(Galeri::class, ['id' => $galeri->id]);
        Storage::disk('public')->assertMissing('katalog/foto-lama.jpg');
    }
}
