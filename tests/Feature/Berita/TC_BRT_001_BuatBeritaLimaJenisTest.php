<?php

namespace Tests\Feature\Berita;

use App\Models\Galeri;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Storage;
use Tests\BeritaTestCase;

/**
 * TC-BRT-001 (Feature, Sedang) - Membuat berita valid untuk 5 jenis.
 */
class TC_BRT_001_BuatBeritaLimaJenisTest extends BeritaTestCase
{
    private const JENIS = ['Pengumuman', 'Prestasi', 'Kolaborasi', 'Event', 'Lainnya'];

    private function judul(string $jenis): string
    {
        return 'Pentas Gandrung di Festival Banyuwangi - '.$jenis;
    }

    public function test_brt_001_admin_membuat_berita_lima_jenis_tampil_publik_tercatat_log(): void
    {
        $admin = $this->buatAdmin();
        $asli = [];

        // Langkah 1-4: buat berita untuk tiap jenis
        foreach (self::JENIS as $jenis) {
            $foto = $this->foto('foto_berita.jpg', 1468006); // ~1,4 MB
            $asli[$jenis] = md5_file($foto->getRealPath());

            $this->actingAs($admin)
                ->post(route('admin.store-berita'), [
                    'judul' => $this->judul($jenis),
                    'isi' => 'Sanggar JEB tampil pada festival budaya ...',
                    'jenis_berita' => $jenis,
                    'status' => 'tayang',
                    'foto' => [$foto],
                ])
                ->assertRedirect(route('admin.kelola-berita'))
                ->assertSessionHas('success')
                ->assertSessionHasNoErrors();

            $this->assertDatabaseHas('berita', [
                'judul' => $this->judul($jenis),
                'jenis_berita' => $jenis,
                'status' => 'tayang',
                'user_id' => $admin->id,
            ]);
        }

        $this->assertDatabaseCount('berita', 5);

        // Langkah 3: foto tersimpan di galeri (kategori 'berita'), berwatermark
        foreach (self::JENIS as $jenis) {
            $berita = \App\Models\Berita::where('judul', $this->judul($jenis))->firstOrFail();
            $galeri = Galeri::where('sumber_id', $berita->id)->where('kategori', 'berita')->get();

            $this->assertCount(1, $galeri, "Foto berita {$jenis}");
            $this->assertTrue((bool) $galeri[0]->is_watermark);
            Storage::disk('public')->assertExists($galeri[0]->path);

            // berkas hasil = gambar valid yang sudah diproses (bukan salinan byte asli)
            $hasil = Storage::disk('public')->path($galeri[0]->path);
            $this->assertNotFalse(getimagesize($hasil));
            $this->assertNotSame($asli[$jenis], md5_file($hasil), 'Watermark seharusnya mengubah berkas.');
        }

        // Langkah 5: halaman publik tanpa login
        $this->keluarSebagaiPengunjung();
        $publik = $this->get(route('umum.berita'))->assertOk();
        $this->assertSame(5, $publik->viewData('beritas')->total());
        foreach (self::JENIS as $jenis) {
            $berita = \App\Models\Berita::where('judul', $this->judul($jenis))->first();
            $publik->assertSee($this->judul($jenis));
            $publik->assertSee('storage/'.$berita->galeri->first()->path, false);
        }

        // Langkah 6: log aktivitas (5 entri) terlihat oleh superadmin
        $this->assertSame(5, LogAktivitas::where('aktivitas', 'like', 'Menambahkan berita baru:%')->count());

        $this->actingAs($this->buatSuperadmin())
            ->get(route('superadmin.log-aktivitas'))
            ->assertOk()
            ->assertSee($this->judul('Pengumuman'));
    }

    public function test_brt_001_superadmin_juga_dapat_membuat_berita(): void
    {
        $this->actingAs($this->buatSuperadmin())
            ->post(route('superadmin.store-berita'), $this->dataBerita(['judul' => 'Berita Superadmin']) + [
                'foto' => [$this->foto()],
            ])
            ->assertRedirect(route('superadmin.kelola-berita'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('berita', ['judul' => 'Berita Superadmin']);
    }

    public function test_brt_001_pengunjung_tanpa_login_tidak_dapat_menyimpan_berita(): void
    {
        $this->post(route('admin.store-berita'), $this->dataBerita() + ['foto' => [$this->foto()]])
            ->assertForbidden();

        $this->assertDatabaseCount('berita', 0);
    }

    public function test_brt_001_isi_berita_disanitasi_dari_script_berbahaya(): void
    {
        $this->actingAs($this->buatAdmin())
            ->post(route('admin.store-berita'), $this->dataBerita([
                'judul' => 'Berita XSS',
                'isi' => '<p>Aman</p><script>alert(1)</script>',
            ]) + ['foto' => [$this->foto()]]);

        $isi = \App\Models\Berita::where('judul', 'Berita XSS')->value('isi_berita');
        $this->assertStringContainsString('Aman', $isi);
        $this->assertStringNotContainsString('<script', $isi);
    }
}
