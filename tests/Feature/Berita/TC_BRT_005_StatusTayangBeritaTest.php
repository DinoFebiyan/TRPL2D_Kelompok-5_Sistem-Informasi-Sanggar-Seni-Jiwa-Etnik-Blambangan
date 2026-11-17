<?php

namespace Tests\Feature\Berita;

use Tests\BeritaTestCase;

/**
 * TC-BRT-005 (Feature, Sedang) - Status Tayang / Tidak tayang menentukan tampil di publik.
 */
class TC_BRT_005_StatusTayangBeritaTest extends BeritaTestCase
{
    private function ubahStatus(\App\Models\Berita $berita, string $status): void
    {
        $this->put(route('admin.update-berita', $berita->id), [
            'judul' => $berita->judul,
            'isi' => $berita->isi_berita,
            'jenis_berita' => $berita->jenis_berita,
            'status' => $status,
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.kelola-berita'));
    }

    public function test_brt_005_perubahan_status_tayang_langsung_berlaku_di_publik(): void
    {
        $admin = $this->buatAdmin();
        $berita = $this->buatBerita(['judul' => 'Berita Status Uji', 'status' => 'tayang', 'user_id' => $admin->id]);

        // Langkah 1: ubah ke Tidak tayang
        $this->actingAs($admin);
        $this->ubahStatus($berita, 'tidak ditayangkan');
        $this->assertDatabaseHas('berita', ['id' => $berita->id, 'status' => 'tidak ditayangkan']);

        // Langkah 2: tidak muncul di daftar maupun pencarian
        $this->keluarSebagaiPengunjung();
        $this->assertSame(0, $this->get(route('umum.berita'))->viewData('beritas')->total());
        $this->assertSame(
            0,
            $this->get(route('umum.berita', ['search' => 'Berita Status Uji']))->viewData('beritas')->total()
        );

        // Langkah 3: akses langsung URL detail ditolak (tidak ditemukan)
        $this->get(route('umum.berita-detail', $berita->id))->assertNotFound();

        // Langkah 4: kembalikan ke Tayang
        $this->actingAs($admin);
        $this->ubahStatus($berita, 'tayang');
        $this->assertDatabaseHas('berita', ['id' => $berita->id, 'status' => 'tayang']);

        // Langkah 5: tampil kembali dan detail dapat dibuka
        $this->keluarSebagaiPengunjung();
        $daftar = $this->get(route('umum.berita'))->assertOk();
        $this->assertSame(['Berita Status Uji'], $daftar->viewData('beritas')->pluck('judul')->all());
        $this->get(route('umum.berita-detail', $berita->id))->assertOk()->assertSee('Berita Status Uji');
    }

    public function test_brt_005_nilai_status_tidak_sah_ditolak(): void
    {
        $admin = $this->buatAdmin();
        $berita = $this->buatBerita(['status' => 'tayang', 'user_id' => $admin->id]);

        $this->actingAs($admin)
            ->put(route('admin.update-berita', $berita->id), [
                'judul' => $berita->judul, 'isi' => $berita->isi_berita,
                'jenis_berita' => $berita->jenis_berita, 'status' => 'draft123',
            ])
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('berita', ['id' => $berita->id, 'status' => 'tayang']);
    }
}
