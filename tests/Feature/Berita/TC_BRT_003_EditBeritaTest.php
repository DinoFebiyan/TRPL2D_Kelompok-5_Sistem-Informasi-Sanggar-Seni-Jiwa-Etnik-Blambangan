<?php

namespace Tests\Feature\Berita;

use App\Models\LogAktivitas;
use Tests\BeritaTestCase;

/**
 * TC-BRT-003 (Feature, Sedang) - Mengedit berita; perubahan langsung tampil di publik.
 */
class TC_BRT_003_EditBeritaTest extends BeritaTestCase
{
    private const JUDUL_LAMA = 'Pentas Gandrung di Festival Banyuwangi';
    private const JUDUL_BARU = 'Pentas Gandrung di Festival Banyuwangi (Diperbarui)';
    private const ISI_BARU = 'Isi berita diperbarui dengan jadwal tambahan.';

    public function test_brt_003_admin_mengedit_berita_dan_perubahan_tampil_di_publik(): void
    {
        $admin = $this->buatAdmin();
        $berita = $this->buatBerita([
            'judul' => self::JUDUL_LAMA,
            'jenis_berita' => 'Pengumuman',
            'status' => 'tayang',
            'user_id' => $admin->id,
        ]);

        // Langkah 1: form edit menampilkan data saat ini
        $this->actingAs($admin)
            ->get(route('admin.edit-berita', $berita->id))
            ->assertOk()
            ->assertSee(self::JUDUL_LAMA);

        // Langkah 2-3: ubah judul, isi, jenis lalu simpan
        $this->put(route('admin.update-berita', $berita->id), [
            'judul' => self::JUDUL_BARU,
            'isi' => self::ISI_BARU,
            'jenis_berita' => 'Prestasi',
            'status' => 'tayang',
        ])
            ->assertRedirect(route('admin.kelola-berita'))
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('berita', [
            'id' => $berita->id,
            'judul' => self::JUDUL_BARU,
            'isi_berita' => self::ISI_BARU,
            'jenis_berita' => 'Prestasi',
        ]);
        $this->assertTrue(
            LogAktivitas::where('user_id', $admin->id)->where('aktivitas', 'like', 'Memperbarui data berita:%')->exists()
        );

        // Langkah 4: halaman publik tanpa login
        $this->keluarSebagaiPengunjung();

        $daftar = $this->get(route('umum.berita'))->assertOk()->assertSee(self::JUDUL_BARU);
        $item = $daftar->viewData('beritas')->first();
        $this->assertSame('Prestasi', $item->jenis_berita);

        $this->get(route('umum.berita-detail', $berita->id))
            ->assertOk()
            ->assertSee(self::JUDUL_BARU)
            ->assertSee(self::ISI_BARU)
            ->assertSee('Prestasi');
    }

    public function test_brt_003_edit_data_tidak_valid_ditolak(): void
    {
        $admin = $this->buatAdmin();
        $berita = $this->buatBerita(['judul' => self::JUDUL_LAMA, 'user_id' => $admin->id]);

        $this->actingAs($admin)
            ->put(route('admin.update-berita', $berita->id), [
                'judul' => '',
                'isi' => self::ISI_BARU,
                'jenis_berita' => 'Prestasi',
                'status' => 'tayang',
            ])
            ->assertSessionHasErrors('judul');

        $this->assertDatabaseHas('berita', ['id' => $berita->id, 'judul' => self::JUDUL_LAMA]);
    }

    public function test_brt_003_edit_berita_tidak_ada_menghasilkan_404(): void
    {
        $this->actingAs($this->buatAdmin())
            ->put(route('admin.update-berita', 9999), $this->dataBerita())
            ->assertNotFound();
    }

    public function test_brt_003_pengunjung_tidak_dapat_mengedit_berita(): void
    {
        $berita = $this->buatBerita(['judul' => self::JUDUL_LAMA]);

        $this->put(route('admin.update-berita', $berita->id), $this->dataBerita(['judul' => 'Diretas']))
            ->assertForbidden();

        $this->assertDatabaseHas('berita', ['id' => $berita->id, 'judul' => self::JUDUL_LAMA]);
    }
}
