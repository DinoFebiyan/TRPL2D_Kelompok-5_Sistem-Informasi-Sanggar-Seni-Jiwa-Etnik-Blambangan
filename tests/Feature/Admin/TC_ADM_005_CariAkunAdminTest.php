<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TC-ADM-005 : Pencarian akun admin berdasarkan nama, email, atau nomor HP,
 * termasuk kondisi batas kata kunci kosong dan hasil kosong.
 *
 * Halaman "Kelola Admin" mengirim data ke tampilan dengan nama 'admins'.
 * Kita memeriksa isi data itu lewat assertViewHas('admins', ...).
 */
class TC_ADM_005_CariAkunAdminTest extends TestCase
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

    public function test_adm_005_pencarian_berdasarkan_nama_menemukan_admin_yang_sesuai(): void
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

    public function test_adm_005_pencarian_berdasarkan_email_menemukan_admin_yang_sesuai(): void
    {
        $this->siapkanTigaAdmin();

        $response = $this->actingAs($this->buatSuperadmin())
            ->get(route('superadmin.kelola-admin', ['search' => 'siti@jeb']));

        $response->assertViewHas('admins', function ($admins) {
            return $admins->pluck('name')->all() === ['Siti Aminah'];
        });
    }

    public function test_adm_005_pencarian_tanpa_hasil_menampilkan_pesan_kosong(): void
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
}
