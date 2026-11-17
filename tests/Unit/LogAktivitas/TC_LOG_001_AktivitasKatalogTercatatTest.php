<?php

namespace Tests\Unit;

use App\Models\LogAktivitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-LOG-001 : Aktivitas tambah, ubah, dan hapus pada modul utama
 * tercatat otomatis pada log aktivitas (diuji lewat modul katalog).
 */
class TC_LOG_001_AktivitasKatalogTercatatTest extends TestCase
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
