<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TC-ADM-006 : Filter akun admin berdasarkan status aktif/nonaktif,
 * termasuk kombinasinya dengan pencarian.
 */
class TC_ADM_006_FilterStatusAkunAdminTest extends TestCase
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

    public function test_adm_006_filter_status_aktif_hanya_menampilkan_admin_aktif(): void
    {
        $this->siapkanTigaAdmin();

        $response = $this->actingAs($this->buatSuperadmin())
            ->get(route('superadmin.kelola-admin', ['status' => ['aktif']]));

        // Hasilnya 2 admin aktif (diurutkan nama supaya perbandingan pasti).
        $response->assertViewHas('admins', function ($admins) {
            return $admins->pluck('name')->sort()->values()->all() === ['Budi Santoso', 'Siti Aminah'];
        });
    }

    public function test_adm_006_filter_status_nonaktif_hanya_menampilkan_admin_nonaktif(): void
    {
        $this->siapkanTigaAdmin();

        $response = $this->actingAs($this->buatSuperadmin())
            ->get(route('superadmin.kelola-admin', ['status' => ['nonaktif']]));

        $response->assertViewHas('admins', function ($admins) {
            return $admins->pluck('name')->all() === ['Rani Lestari'];
        });
    }

    public function test_adm_006_tanpa_filter_menampilkan_semua_admin_dan_statistik_benar(): void
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
