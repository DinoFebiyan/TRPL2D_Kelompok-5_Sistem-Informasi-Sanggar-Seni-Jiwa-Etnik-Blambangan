<?php

namespace Tests\Feature\Berita;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Group;
use Tests\BeritaTestCase;

/**
 * TC-BRT-008 (Feature, Sedang) - Halaman berita publik pada kondisi batas:
 * data kosong, filter tanpa hasil, dan kegagalan koneksi database.
 */
class BeritaPublikBatasTest extends BeritaTestCase
{
    private function matikanDatabase(): void
    {
        config(['database.connections.mati' => [
            'driver' => 'sqlite',
            'database' => '/tidak/ada/mati.sqlite',
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]]);
        config(['database.default' => 'mati']);
        DB::purge('mati');
    }

    private function hidupkanDatabase(): void
    {
        config(['database.default' => 'sqlite']);
        DB::purge('mati');
    }

    // --- Kondisi 1: tabel berita kosong -----------------------------------

    public function test_langkah1_tabel_berita_kosong_halaman_tetap_tampil_tanpa_error(): void
    {
        $res = $this->get(route('umum.berita'))->assertOk();

        $this->assertSame(0, $res->viewData('beritas')->total());
        $res->assertSee('Menampilkan 0 berita');
    }

    // --- Kondisi 2: filter jenis tanpa hasil -------------------------------

    public function test_langkah2_filter_kolaborasi_tanpa_berita_menghasilkan_daftar_kosong(): void
    {
        $this->buatBerita(['judul' => 'Hanya Prestasi', 'jenis_berita' => 'Prestasi', 'status' => 'tayang']);

        $res = $this->get(route('umum.berita', ['kategori' => ['Kolaborasi']]))->assertOk();

        $this->assertSame(0, $res->viewData('beritas')->total());
        $res->assertDontSee('Hanya Prestasi');
    }

    #[Group('selisih-spesifikasi')]
    public function test_langkah1_2_pesan_kosong_sesuai_test_case(): void
    {
        // View saat ini: "Belum ada data berita atau artikel yang sesuai dengan kriteria pencarian Anda."
        $this->get(route('umum.berita'))->assertSee('Belum ada berita tersedia');

        $this->buatBerita(['jenis_berita' => 'Prestasi', 'status' => 'tayang']);
        $this->get(route('umum.berita', ['kategori' => ['Kolaborasi']]))->assertSee('Belum ada berita tersedia');
    }

    // --- Kondisi 3: database tidak dapat diakses --------------------------

    #[Group('selisih-spesifikasi')]
    public function test_langkah3_database_mati_menampilkan_pesan_ramah_tanpa_stack_trace(): void
    {
        $this->matikanDatabase();

        try {
            $res = $this->get(route('umum.berita'));
        } finally {
            $this->hidupkanDatabase();
        }

        $res->assertSee('Terjadi kesalahan sistem, silakan coba lagi nanti');
        $res->assertDontSee('QueryException');
        $res->assertDontSee('Stack trace');
        $this->assertNotSame(500, $res->getStatusCode(), 'TC: tanpa error 500 mentah.');
    }

    public function test_langkah4_halaman_kembali_normal_setelah_database_hidup(): void
    {
        $this->buatBerita(['judul' => 'Berita Pulih', 'status' => 'tayang']);

        $this->matikanDatabase();
        try {
            $this->get(route('umum.berita'));
        } finally {
            $this->hidupkanDatabase();
        }

        $res = $this->get(route('umum.berita'))->assertOk();
        $this->assertSame(['Berita Pulih'], $res->viewData('beritas')->pluck('judul')->all());
    }
}
