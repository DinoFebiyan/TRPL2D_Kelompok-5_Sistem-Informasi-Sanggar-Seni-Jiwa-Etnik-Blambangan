<?php

namespace Tests\Unit;

use App\Models\Katalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-KTL-002 : Validasi katalog menolak format gambar salah, ukuran melebihi
 * batas, stok negatif, dan deskripsi kosong.
 */
class TC_KTL_002_ValidasiKatalogTest extends TestCase
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

    public static function dataTidakValid(): array
    {
        return [
            'nama kosong' => [['nama_katalog' => ''], 'nama_katalog'],
            'kategori kosong' => [['kategori' => ''], 'kategori'],
            'stok negatif' => [['stok' => -1], 'stok'],
            'stok bukan angka' => [['stok' => 'banyak'], 'stok'],
            'deskripsi kosong' => [['deskripsi' => ''], 'deskripsi'],
            'status tidak dikenal' => [['status' => 'rusak'], 'status'],
            'foto tidak ada' => [['foto' => null], 'foto'],
            'foto bukan gambar' => [['foto' => 'pdf'], 'foto.0'],
        ];
    }

    #[DataProvider('dataTidakValid')]
    public function test_ktl_002_validasi_katalog_menolak_data_tidak_valid(array $override, string $fieldError): void
    {
        if (($override['foto'] ?? null) === 'pdf') {
            $override['foto'] = [UploadedFile::fake()->create('dokumen.pdf', 10, 'application/pdf')];
        }

        $data = $this->dataKatalogValid($override);
        if (array_key_exists('foto', $override) && $override['foto'] === null) {
            unset($data['foto']);
        }

        $response = $this->actingAs($this->buatSuperadmin())
            ->from(route('superadmin.tambah-katalog'))
            ->post(route('superadmin.store-katalog'), $data);

        $response->assertSessionHasErrors($fieldError);
        $this->assertSame(0, Katalog::count());
    }

    public function test_ktl_002_foto_lebih_dari_4mb_ditolak(): void
    {
        $data = $this->dataKatalogValid([
            'foto' => [UploadedFile::fake()->image('besar.jpg')->size(5000)],
        ]);

        $response = $this->actingAs($this->buatSuperadmin())
            ->from(route('superadmin.tambah-katalog'))
            ->post(route('superadmin.store-katalog'), $data);

        $response->assertSessionHasErrors('foto.0');
        $this->assertSame(0, Katalog::count());
    }
}
