<?php

namespace Tests\Feature\Berita;

use App\Models\Galeri;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Storage;
use Tests\BeritaTestCase;

/**
 * TC-BRT-004 (Feature, Sedang) - Menghapus berita beserta record galeri & berkas fisik.
 */
class TC_BRT_004_HapusBeritaFotoTest extends BeritaTestCase
{
    public function test_brt_004_hapus_berita_menghapus_galeri_dan_berkas_fisik(): void
    {
        $admin = $this->buatAdmin();
        $berita = $this->buatBerita(['judul' => 'Berita Hapus Uji', 'user_id' => $admin->id]);

        // Prasyarat: 2 foto (hapus_1.jpg, hapus_2.jpg), path dicatat
        $path = [];
        foreach (['hapus_1.jpg', 'hapus_2.jpg'] as $nama) {
            $path[$nama] = 'berita/'.$nama;
            Storage::disk('public')->put($path[$nama], $this->jpegBytes());
            Galeri::create([
                'sumber_id' => $berita->id, 'path' => $path[$nama], 'nama_file' => $nama,
                'kategori' => 'berita', 'is_watermark' => true,
            ]);
            Storage::disk('public')->assertExists($path[$nama]);
        }

        // Langkah 2: konfirmasi penghapusan
        $this->actingAs($admin)
            ->delete(route('admin.hapus-berita', $berita->id))
            ->assertRedirect(route('admin.kelola-berita'))
            ->assertSessionHas('success');

        // Langkah 3: tidak tampil di daftar admin maupun publik
        $this->get(route('admin.kelola-berita'))->assertOk()->assertDontSee('Berita Hapus Uji');
        $this->assertDatabaseMissing('berita', ['id' => $berita->id]);

        // Langkah 4: record galeri hilang
        $this->assertSame(0, Galeri::where('sumber_id', $berita->id)->where('kategori', 'berita')->count());

        // Langkah 5: berkas fisik hilang
        foreach ($path as $p) {
            Storage::disk('public')->assertMissing($p);
        }

        // Log
        $this->assertTrue(LogAktivitas::where('aktivitas', 'Menghapus berita: Berita Hapus Uji')->exists());

        // Publik
        $this->keluarSebagaiPengunjung();
        $daftar = $this->get(route('umum.berita'))->assertOk();
        $this->assertSame(0, $daftar->viewData('beritas')->total());
        $this->get(route('umum.berita-detail', $berita->id))->assertNotFound();
    }

    public function test_brt_004_hapus_berita_tidak_menghapus_foto_berita_lain(): void
    {
        $admin = $this->buatAdmin();
        $target = $this->buatBerita(['judul' => 'Target', 'user_id' => $admin->id], 2);
        $lain = $this->buatBerita(['judul' => 'Lain', 'user_id' => $admin->id], 1);
        $fotoLain = $lain->galeri()->first();

        $this->actingAs($admin)->delete(route('admin.hapus-berita', $target->id));

        $this->assertDatabaseHas('galeri', ['id' => $fotoLain->id]);
        Storage::disk('public')->assertExists($fotoLain->path);
        $this->assertDatabaseHas('berita', ['id' => $lain->id]);
    }

    public function test_brt_004_hapus_berita_tanpa_foto_tetap_berhasil(): void
    {
        $admin = $this->buatAdmin();
        $berita = $this->buatBerita(['user_id' => $admin->id]);

        $this->actingAs($admin)->delete(route('admin.hapus-berita', $berita->id))->assertRedirect();

        $this->assertDatabaseMissing('berita', ['id' => $berita->id]);
    }

    public function test_brt_004_hapus_berita_tidak_ada_menghasilkan_404(): void
    {
        $this->actingAs($this->buatAdmin())->delete(route('admin.hapus-berita', 9999))->assertNotFound();
    }

    public function test_brt_004_pengunjung_tidak_dapat_menghapus_berita(): void
    {
        $berita = $this->buatBerita();

        $this->delete(route('admin.hapus-berita', $berita->id))->assertForbidden();

        $this->assertDatabaseHas('berita', ['id' => $berita->id]);
    }
}
