<?php

namespace Tests\Feature;

use App\Models\User;
use Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Halaman login dapat diakses
     */
    public function test_halaman_login_dapat_diakses(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test 2: Gagal login jika reCaptcha tidak dcentang/dikirim
     */
    public function test_gagal_login_jika_recaptcha_tidak_dicentang(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@jeb.id',
            'password' => '12345678',
        ]);
        $response->assertSessionHasErrors('g-recaptcha-response');
        $this->assertGuest();
    }

    /**
     * Test 3: Gagal login jika email tidak ditemukan
     */
    public function test_gagal_login_jika_email_tidak_ditemukan(): void
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

    /**
     * Test 4: Gagal login jika password salah
     */
    public function test_gagal_login_jika_password_salah(): void
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
}
