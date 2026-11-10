<?php

namespace Tests\Feature\Berita;

use App\Models\Galeri;
use Tests\BeritaTestCase;

/**
 * TC-BRT-007 (Feature, Sedang) - Berita publik tanpa login:
 * hanya berita Tayang, pencarian, dan filter jenis.
 */
class BeritaPublikTest extends BeritaTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $admin = $this->buatAdmin();
        $this->buatBerita(['judul' => 'Juara Festival Tari', 'isi_berita' => 'Sanggar meraih penghargaan.', 'jenis_berita' => 'Prestasi', 'status' => 'tayang', 'user_id' => $admin->id]);
        $this->buatBerita(['judul' => 'Jadwal Latihan Baru', 'isi_berita' => 'Latihan setiap Sabtu.', 'jenis_berita' => 'Pengumuman', 'status' => 'tayang', 'user_id' => $admin->id]);
        // Berita tidak tayang: sengaja Prestasi & memuat kata "Juara" agar menguji pencarian + filter
        $this->buatBerita(['judul' => 'Draf Internal', 'isi_berita' => 'Rencana juara tahun depan.', 'jenis_berita' => 'Prestasi', 'status' => 'tidak ditayangkan', 'user_id' => $admin->id]);
    }

    private function judulDi($response): array
    {
        return $response->viewData('beritas')->pluck('judul')->sort()->values()->all();
    }

    public function test_langkah1_pengunjung_hanya_melihat_berita_tayang(): void
    {
        $res = $this->get(route('umum.berita'))->assertOk();

        $this->assertSame(['Jadwal Latihan Baru', 'Juara Festival Tari'], $this->judulDi($res));
        $res->assertSee('Juara Festival Tari')->assertSee('Jadwal Latihan Baru')->assertDontSee('Draf Internal');
    }

    public function test_langkah2_pencarian_menemukan_judul_atau_isi_dan_tidak_membocorkan_draf(): void
    {
        $this->buatBerita(['judul' => 'Pengumuman Umum', 'isi_berita' => 'Selamat kepada juara lomba.', 'status' => 'tayang']);

        $res = $this->get(route('umum.berita', ['search' => 'Juara']))->assertOk();

        // cocok pada judul ("Juara Festival Tari") ATAU isi ("...juara lomba."); draf internal tetap tersembunyi
        $this->assertSame(['Juara Festival Tari', 'Pengumuman Umum'], $this->judulDi($res));
    }

    public function test_pencarian_tanpa_hasil_menghasilkan_daftar_kosong(): void
    {
        $res = $this->get(route('umum.berita', ['search' => 'tidak-ada-kata-ini']))->assertOk();

        $this->assertSame([], $this->judulDi($res));
    }

    public function test_langkah3_filter_jenis_prestasi_hanya_memuat_berita_prestasi_tayang(): void
    {
        $res = $this->get(route('umum.berita', ['kategori' => ['Prestasi']]))->assertOk();

        $this->assertSame(['Juara Festival Tari'], $this->judulDi($res));
        foreach ($res->viewData('beritas') as $berita) {
            $this->assertSame('Prestasi', $berita->jenis_berita);
        }
    }

    public function test_langkah4_detail_menampilkan_judul_isi_dan_foto_berwatermark(): void
    {
        $berita = \App\Models\Berita::where('judul', 'Juara Festival Tari')->first();
        $dinoGanteng = Galeri::create([
            'sumber_id' => $berita->id, 'path' => 'berita/juara_wm.jpg', 'nama_file' => 'juara_wm.jpg',
            'kategori' => 'berita', 'is_watermark' => true,
        ]);

        $this->get(route('umum.berita-detail', $berita->id))
            ->dump()
            ->assertOk()
            ->assertSee('Juara Festival Tari')
            ->assertSee('Sanggar meraih penghargaan.')
            ->assertSee('storage/berita/juara_wm.jpg', false);

            dump($dinoGanteng);
    }

    public function test_detail_berita_tidak_tayang_tidak_dapat_diakses(): void
    {
        $draf = \App\Models\Berita::where('judul', 'Draf Internal')->first();

        $this->get(route('umum.berita-detail', $draf->id))->assertNotFound();
    }
}
