<?php

namespace Tests\Unit\Berita;

use App\Http\Controllers\BeritaController;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\BeritaTestCase;

/**
 * TC-BRT-002 (Unit, Mudah) - Validasi form berita:
 * judul kosong, isi kosong, jenis/status tidak sah, judul 256 karakter.
 */
class TC_BRT_002_ValidasiFormBeritaTest extends BeritaTestCase
{
    private BeritaController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->buatAdmin());
        $this->controller = app(BeritaController::class);
    }

    public static function inputTidakSah(): array
    {
        return [
            'langkah 1: judul kosong' => [['judul' => ''], 'judul'],
            'langkah 2: isi kosong' => [['isi' => ''], 'isi'],
            'langkah 3: jenis tidak sah (Hiburan)' => [['jenis_berita' => 'Hiburan'], 'jenis_berita'],
            'langkah 4: status tidak sah (draft123)' => [['status' => 'draft123'], 'status'],
            'langkah 5: judul 256 karakter' => [['judul' => str_repeat('a', 256)], 'judul'],
        ];
    }

    #[DataProvider('inputTidakSah')]
    public function test_brt_002_input_tidak_sah_ditolak_tanpa_tersimpan(array $override, string $fieldError): void
    {
        $request = $this->requestDengan($this->dataBerita($override), ['foto' => [$this->foto()]]);

        $errors = $this->errorValidasi(fn () => $this->controller->store($request));

        $this->assertArrayHasKey($fieldError, $errors, "Field '{$fieldError}' seharusnya ditolak validasi.");

        // Langkah 6: tidak ada record berita/galeri/log maupun berkas foto baru
        $this->assertSame(0, Berita::count());
        $this->assertSame(0, Galeri::count());
        $this->assertSame(0, LogAktivitas::count());
        $this->assertSame([], Storage::disk('public')->allFiles('berita'));
    }

    public function test_brt_002_judul_tepat_255_karakter_diterima(): void
    {
        $judul = str_repeat('a', 255);
        $request = $this->requestDengan($this->dataBerita(['judul' => $judul]), ['foto' => [$this->foto()]]);

        $errors = $this->errorValidasi(fn () => $this->controller->store($request));

        $this->assertArrayNotHasKey('judul', $errors);
        $this->assertDatabaseHas('berita', ['judul' => $judul]);
    }

    #[Group('selisih-spesifikasi')]
    public function test_brt_002_pesan_validasi_sesuai_test_case(): void
    {
        // Expected result TC-BRT-002: pesan validasi berbahasa Indonesia.
        $kosongJudul = $this->errorValidasi(fn () => $this->controller->store(
            $this->requestDengan($this->dataBerita(['judul' => '']), ['foto' => [$this->foto()]])
        ));
        $this->assertSame('Judul tidak boleh kosong', $kosongJudul['judul'][0] ?? null);

        $kosongIsi = $this->errorValidasi(fn () => $this->controller->store(
            $this->requestDengan($this->dataBerita(['isi' => '']), ['foto' => [$this->foto()]])
        ));
        $this->assertSame('Isi berita tidak boleh kosong', $kosongIsi['isi'][0] ?? null);

        $panjang = $this->errorValidasi(fn () => $this->controller->store(
            $this->requestDengan($this->dataBerita(['judul' => str_repeat('a', 256)]), ['foto' => [$this->foto()]])
        ));
        $this->assertStringContainsString('255', $panjang['judul'][0] ?? '');
        $this->assertStringContainsStringIgnoringCase('judul', $panjang['judul'][0] ?? '');
    }
}
