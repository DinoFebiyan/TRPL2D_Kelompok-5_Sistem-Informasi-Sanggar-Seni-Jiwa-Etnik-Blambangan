<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * TC-AUTH-001 : Login berhasil dengan kredensial valid
 * dan redirect ke dashboard sesuai peran.
 */
class TC_AUTH_001_LoginValidRedirectPeranTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_001_halaman_login_dapat_diakses(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_auth_001_login_valid_admin_redirect_dashboard(): void
    {
        // Mock reCAPTCHA agar tes tidak memanggil API Google
        Http::fake(['https://www.google.com/*' => Http::response(['success' => true], 200)]);

        $user = User::factory()->create([
            'status' => 'aktif',
            'peran' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'g-recaptcha-response' => 'fake-captcha',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin/dashboard');
    }
}
