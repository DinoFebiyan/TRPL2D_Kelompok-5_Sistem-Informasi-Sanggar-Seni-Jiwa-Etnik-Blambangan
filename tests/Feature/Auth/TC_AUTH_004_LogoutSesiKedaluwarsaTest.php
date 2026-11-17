<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TC-AUTH-004 : Logout saat sesi sudah kedaluwarsa — sistem mendeteksi
 * sesi tidak ada dan mengarahkan ke form login tanpa error.
 */
class TC_AUTH_004_LogoutSesiKedaluwarsaTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_004_logout_saat_sesi_kedaluwarsa_diarahkan_ke_login(): void
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
