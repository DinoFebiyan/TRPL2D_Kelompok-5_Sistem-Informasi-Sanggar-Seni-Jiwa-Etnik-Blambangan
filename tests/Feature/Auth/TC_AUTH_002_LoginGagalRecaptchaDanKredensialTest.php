<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * TC-AUTH-002 : Login gagal — recaptcha tidak dicentang,
 * email tidak terdaftar, password salah, tanpa sesi terbentuk.
 */
class TC_AUTH_002_LoginGagalRecaptchaDanKredensialTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_002_halaman_login_dapat_diakses(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_auth_002_gagal_login_jika_recaptcha_tidak_dicentang(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@jeb.id',
            'password' => '12345678',
        ]);
        $response->assertSessionHasErrors('g-recaptcha-response');
        $this->assertGuest();
    }

    public function test_auth_002_gagal_login_jika_email_tidak_ditemukan(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true], 200),
        ]);

        $response = $this->post('/login', [
            'email' => 'emailyangtidakada@jeb.com',
            'password' => '12345678',
            'g-recaptcha-response' => 'token-dummy'
        ]);

        // Menggunakan assertSessionHasErrors() tanpa parameter spesifik
        // karena Laravel menangkap gagal login sebagai error umum autentikasi
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_auth_002_gagal_login_jika_password_salah(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true], 200),
        ]);

        User::factory()->create([
            'email' => 'admin@jeb.com',
            'password' => bcrypt('password123'), // Password yang benar di database
            'peran' => 'admin',
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@jeb.com',
            'password' => 'salahpasswordnya', // Password yang salah
            'g-recaptcha-response' => 'token-dummy',
        ]);

        // Memastikan terdapat error pada session (biasanya terikat ke field email/password)
        $response->assertSessionHasErrors();

        // Memastikan pengguna gagal terautentikasi (tetap sebagai guest)
        $this->assertGuest();
    }

    public function test_auth_002_password_salah_tidak_membentuk_sesi(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'g-recaptcha-response' => 'fake-captcha',
        ]);

        $this->assertGuest();
    }
}
