<?php

namespace Tests\Feature;

use App\Models\Galeri;
use App\Models\Katalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\DataUji;
use Tests\TestCase;


class KatalogKelolaTest extends TestCase
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

    
    public function test_ktl_005_admin_biasa_ditolak_menambah_katalog(): void
    {
        $response = $this->actingAs($this->buatAdmin())
            ->post(route('superadmin.store-katalog'), $this->dataKatalogValid());

        $response->assertForbidden();                
        $this->assertSame(0, Katalog::count());       
    }
}