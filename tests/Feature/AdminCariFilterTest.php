<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TC-ADM-005 : Pencarian akun admin, termasuk hasil kosong
 * TC-ADM-006 : Filter akun admin berdasarkan status aktif / nonaktif
 *
 * Halaman "Kelola Admin" mengirim data ke tampilan dengan nama 'admins'.
 * Kita memeriksa isi data itu lewat assertViewHas('admins', ...).
 */
class AdminCariFilterTest extends TestCase
{
    use RefreshDatabase;

    private function buatSuperadmin(): User
    {
        return User::factory()->create(['peran' => 'superadmin', 'status' => 'aktif']);
    }

    // Menyiapkan 3 admin: dua aktif, satu nonaktif.
    private function siapkanTigaAdmin(): void
    {
        User::factory()->create(['peran' => 'admin', 'status' => 'aktif', 'name' => 'Budi Santoso', 'email' => 'budi@jeb.test']);
        User::factory()->create(['peran' => 'admin', 'status' => 'aktif', 'name' => 'Siti Aminah', 'email' => 'siti@jeb.test']);
        User::factory()->create(['peran' => 'admin', 'status' => 'nonaktif', 'name' => 'Rani Lestari', 'email' => 'rani@jeb.test']);
    }

    // ---------- TC-ADM-005 : pencarian ----------
    public function test_pencarian_berdasarkan_nama_menemukan_admin_yang_sesuai(): void
    {
        $this->siapkanTigaAdmin();

        // ACT: cari kata "Budi".
        $response = $this->actingAs($this->buatSuperadmin())
            ->get(route('superadmin.kelola-admin', ['search' => 'Budi']));

        // ASSERT: halaman terbuka (200) dan HANYA "Budi Santoso" yang muncul.
        $response->assertOk();
        $response->assertViewHas('admins', function ($admins) {
            return $admins->pluck('name')->all() === ['Budi Santoso'];
        });
    }

    public function test_pencarian_berdasarkan_email_menemukan_admin_yang_sesuai(): void
    {
        $this->siapkanTigaAdmin();

        $response = $this->actingAs($this->buatSuperadmin())
            ->get(route('superadmin.kelola-admin', ['search' => 'siti@jeb']));

        $response->assertViewHas('admins', function ($admins) {
            return $admins->pluck('name')->all() === ['Siti Aminah'];
        });
    }

    public function test_pencarian_tanpa_hasil_menampilkan_pesan_kosong(): void
    {
        $this->siapkanTigaAdmin();

        // ACT: kata kunci yang tidak cocok dengan siapa pun.
        $response = $this->actingAs($this->buatSuperadmin())
            ->get(route('superadmin.kelola-admin', ['search' => 'zzz-tidak-ada']));

        // ASSERT: halaman tetap terbuka (tidak error), daftar kosong, dan ada pesan "belum ada data".
        $response->assertOk();
        $response->assertViewHas('admins', fn($admins) => $admins->count() === 0);
        $response->assertSee('Belum ada data admin yang sesuai kriteria.');
    }

    // ---------- TC-ADM-006 : filter status ----------
    public function test_filter_status_aktif_hanya_menampilkan_admin_aktif(): void
    {
        $this->siapkanTigaAdmin();

        $response = $this->actingAs($this->buatSuperadmin())
            ->get(route('superadmin.kelola-admin', ['status' => ['aktif']]));

        // Hasilnya 2 admin aktif (diurutkan nama supaya perbandingan pasti).
        $response->assertViewHas('admins', function ($admins) {
            return $admins->pluck('name')->sort()->values()->all() === ['Budi Santoso', 'Siti Aminah'];
        });
    }

    public function test_filter_status_nonaktif_hanya_menampilkan_admin_nonaktif(): void
    {
        $this->siapkanTigaAdmin();

        $response = $this->actingAs($this->buatSuperadmin())
            ->get(route('superadmin.kelola-admin', ['status' => ['nonaktif']]));

        $response->assertViewHas('admins', function ($admins) {
            return $admins->pluck('name')->all() === ['Rani Lestari'];
        });
    }

    public function test_tanpa_filter_menampilkan_semua_admin_dan_statistik_benar(): void
    {
        $this->siapkanTigaAdmin();

        $this->actingAs($this->buatSuperadmin())
            ->get(route('superadmin.kelola-admin'))
            ->assertViewHas('admins', fn($admins) => $admins->count() === 3)   // 3 admin tampil
            ->assertViewHas('totalAdmin', 3)                                    // kartu total
            ->assertViewHas('activeAdmins', 2)                                  // kartu aktif
            ->assertViewHas('inactiveAdmins', 1);                               // kartu nonaktif
    }
}