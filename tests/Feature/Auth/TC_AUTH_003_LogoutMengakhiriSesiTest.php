<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TC-AUTH-003 : Logout mengakhiri sesi aktif, mencabut akses
 * halaman administratif, dan mengarahkan kembali ke halaman login.
 */
class TC_AUTH_003_LogoutMengakhiriSesiTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_003_logout_mengakhiri_sesi_dan_mencabut_akses(): void
    {
        // ARRANGE: buat admin aktif.
        $admin = User::factory()->create(['peran' => 'admin', 'status' => 'aktif']);

        // actingAs = "pura-pura sudah login sebagai $admin" (tanpa mengisi form login).
        // Pastikan SEBELUM logout, halaman dashboard admin bisa dibuka (kode 200 = OK).
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();

        // ACT: klik logout.
        $response = $this->post('/logout');

        // ASSERT:
        $response->assertRedirect('/');                // setelah logout diarahkan ke halaman depan
        $this->assertGuest();                          // sekarang sudah menjadi tamu
        $this->get('/admin/dashboard')->assertForbidden();           // halaman admin ditolak (403)
        $this->get('/dashboard')->assertRedirect(route('login'));    // halaman lain diarahkan ke login
    }

    public function test_auth_003_logout_dari_halaman_lain_tetap_guest(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
