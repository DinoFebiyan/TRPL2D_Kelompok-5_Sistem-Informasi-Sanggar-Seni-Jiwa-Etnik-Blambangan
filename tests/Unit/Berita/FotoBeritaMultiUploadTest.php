<?php

namespace Tests\Unit\Berita;

use App\Http\Controllers\BeritaController;
use App\Models\Berita;
use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Group;
use Tests\BeritaTestCase;

/**
 * TC-BRT-006 (Unit, Sedang) - Multi-foto berita, batas 2 MB per file (TD-N07)
 * dan penghapusan foto tertentu secara selektif.
 */
class FotoBeritaMultiUploadTest extends BeritaTestCase
{
    private const MB_1 = 1048576;       // foto_1mb.jpg
    private const TEPAT_2MB = 2097152;  // foto_2mb_tepat.jpg  (batas valid)
    private const LEBIH_1_BYTE = 2097153; // foto_2mb_plus1.jpg (tidak valid)

    private BeritaController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->buatAdmin());
        $this->controller = app(BeritaController::class);
    }

    private function simpanBeritaDuaFoto(): Berita
    {
        $this->controller->store($this->requestDengan(
            $this->dataBerita(['judul' => 'Berita Multi Foto']),
            ['foto' => [
                $this->foto('foto_1mb.jpg', self::MB_1),
                $this->foto('foto_2mb_tepat.jpg', self::TEPAT_2MB),
            ]]
        ));

        return Berita::where('judul', 'Berita Multi Foto')->firstOrFail();
    }

    public function test_langkah1_foto_1mb_dan_tepat_2mb_diterima_berwatermark_tersimpan_di_galeri(): void
    {
        $berita = $this->simpanBeritaDuaFoto();

        $galeri = Galeri::where('sumber_id', $berita->id)->get();

        $this->assertCount(2, $galeri);
        foreach ($galeri as $foto) {
            $this->assertSame('berita', $foto->kategori);
            $this->assertTrue((bool) $foto->is_watermark);
            Storage::disk('public')->assertExists($foto->path);
        }
    }

    #[Group('selisih-spesifikasi')]
    public function test_langkah2_foto_2mb_plus_1_byte_ditolak_foto_lama_tetap_utuh(): void
    {
        // CATATAN: BeritaController memakai aturan 'max:4096' (4 MB), bukan 2 MB (TD-N07).
        $berita = $this->simpanBeritaDuaFoto();
        $idLama = Galeri::where('sumber_id', $berita->id)->pluck('id')->all();

        $request = $this->requestDengan(
            $this->dataBerita(['judul' => $berita->judul]),
            ['foto' => [$this->foto('foto_2mb_plus1.jpg', self::LEBIH_1_BYTE)]]
        );

        $errors = $this->errorValidasi(fn () => $this->controller->update($request, (string) $berita->id));

        $this->assertArrayHasKey('foto.0', $errors, '2 MB + 1 byte seharusnya ditolak.');
        $this->assertEqualsCanonicalizing($idLama, Galeri::where('sumber_id', $berita->id)->pluck('id')->all());
        $this->assertCount(2, Galeri::where('sumber_id', $berita->id)->get());
    }

    public function test_langkah3_hapus_foto_2_secara_selektif_foto_1_dan_berita_tetap_ada(): void
    {
        $berita = $this->simpanBeritaDuaFoto();
        [$foto1, $foto2] = Galeri::where('sumber_id', $berita->id)->orderBy('id')->get()->all();

        $this->controller->update(
            $this->requestDengan($this->dataBerita(['judul' => $berita->judul]) + ['hapus_foto' => [$foto2->id]]),
            (string) $berita->id
        );

        // foto 2: record + berkas terhapus
        $this->assertDatabaseMissing('galeri', ['id' => $foto2->id]);
        Storage::disk('public')->assertMissing($foto2->path);

        // foto 1 & berita tetap ada
        $this->assertDatabaseHas('galeri', ['id' => $foto1->id]);
        Storage::disk('public')->assertExists($foto1->path);
        $this->assertDatabaseHas('berita', ['id' => $berita->id]);
    }

    public function test_langkah4_relasi_berita_hanya_memuat_foto_1_setelah_foto_2_dihapus(): void
    {
        $berita = $this->simpanBeritaDuaFoto();
        [$foto1, $foto2] = Galeri::where('sumber_id', $berita->id)->orderBy('id')->get()->all();

        $this->controller->update(
            $this->requestDengan($this->dataBerita(['judul' => $berita->judul]) + ['hapus_foto' => [$foto2->id]]),
            (string) $berita->id
        );

        $galeriTersisa = $berita->fresh()->galeri;
        $this->assertCount(1, $galeriTersisa);
        $this->assertSame($foto1->id, $galeriTersisa->first()->id);
    }

    public function test_hapus_foto_milik_berita_lain_tidak_menghapus_foto_tersebut(): void
    {
        $berita = $this->simpanBeritaDuaFoto();
        $beritaLain = $this->buatBerita(['judul' => 'Berita Lain'], 1);
        $fotoLain = $beritaLain->galeri()->first();

        $this->controller->update(
            $this->requestDengan($this->dataBerita(['judul' => $berita->judul]) + ['hapus_foto' => [$fotoLain->id]]),
            (string) $berita->id
        );

        $this->assertDatabaseHas('galeri', ['id' => $fotoLain->id]);
        Storage::disk('public')->assertExists($fotoLain->path);
    }
}
