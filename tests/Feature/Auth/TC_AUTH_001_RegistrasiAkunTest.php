<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TC_AUTH_001_RegistrasiAkunTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_001_halaman_registrasi_tampil(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_auth_001_pengguna_baru_dapat_registrasi(): void
    {
        Http::fake(['https://www.google.com/*' => Http::response(['success' => true], 200)]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'peran' => 'user',
            'no_handphone' => '08123456789',
            'g-recaptcha-response' => 'fake-captcha',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
    }
}
