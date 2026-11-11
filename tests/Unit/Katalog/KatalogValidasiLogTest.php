<?php

namespace Tests\Unit;

use App\Models\Katalog;
use App\Models\LogAktivitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\DataUji;
use Tests\TestCase;


class KatalogValidasiLogTest extends TestCase
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

    public function test_log_001_tambah_katalog_tercatat_di_log(): void
    {
        $superadmin = $this->buatSuperadmin();

        $this->actingAs($superadmin)
            ->post(route('superadmin.store-katalog'), $this->dataKatalogValid());

        $this->assertDatabaseHas(LogAktivitas::class, [
            'user_id' => $superadmin->id,
            'aktivitas' => 'Menambahkan katalog produk baru: Kostum Gandrung',
        ]);
    }

    public function test_log_001_ubah_katalog_tercatat_di_log(): void
    {
        $superadmin = $this->buatSuperadmin();
        $katalog = $this->buatKatalog(['user_id' => $superadmin->id]);

        $data = $this->dataKatalogValid(['nama_katalog' => 'Kostum Baru']);
        unset($data['foto']);

        $this->actingAs($superadmin)
            ->put(route('superadmin.update-katalog', $katalog->id), $data);

        $this->assertDatabaseHas(LogAktivitas::class, [
            'user_id' => $superadmin->id,
            'aktivitas' => 'Memperbarui data katalog produk: Kostum Baru',
        ]);
    }

    public function test_log_001_hapus_katalog_tercatat_di_log(): void
    {
        $superadmin = $this->buatSuperadmin();
        $katalog = $this->buatKatalog(['user_id' => $superadmin->id]);

        $this->actingAs($superadmin)
            ->delete(route('superadmin.hapus-katalog', $katalog->id));

        $this->assertDatabaseHas(LogAktivitas::class, [
            'user_id' => $superadmin->id,
            'aktivitas' => 'Menghapus data katalog: Kostum Gandrung',
        ]);
    }
}