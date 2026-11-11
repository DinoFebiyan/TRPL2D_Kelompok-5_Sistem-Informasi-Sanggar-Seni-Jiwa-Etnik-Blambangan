<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TC-AUTH-003 : Logout mengakhiri sesi dan mencabut akses
 * TC-AUTH-004 : Logout saat sesi sudah kedaluwarsa
 *
 * Jenis: Feature test.
 */
class AuthLogoutTest extends TestCase
{
    use RefreshDatabase;

    // ---------- TC-AUTH-003 ----------
    public function test_logout_mengakhiri_sesi_dan_mencabut_akses(): void
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

    // ---------- TC-AUTH-004 ----------
    public function test_logout_saat_sesi_kedaluwarsa_diarahkan_ke_login(): void
    {
        // ARRANGE: login dulu, lalu "hapus" sesinya untuk mensimulasikan sesi yang sudah habis.
        $admin = User::factory()->create(['peran' => 'admin', 'status' => 'aktif']);
        $this->actingAs($admin);
        $this->flushSession();                                   // kosongkan seluruh data sesi
        $this->app['auth']->guard('web')->logout();              // pengguna tidak lagi terautentikasi

        // ACT: tetap menekan tombol logout padahal sesi sudah habis.
        $response = $this->post('/logout');

        // ASSERT: aman = diarahkan ke halaman login (bukan error 500) dan tetap tamu.
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}