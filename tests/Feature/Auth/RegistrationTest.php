<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
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
