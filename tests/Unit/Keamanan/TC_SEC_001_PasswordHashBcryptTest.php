<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * TC-SEC-001 : Seluruh password tersimpan sebagai hash bcrypt
 * (bukan plaintext) setelah pembuatan akun.
 */
class TC_SEC_001_PasswordHashBcryptTest extends TestCase
{
    use RefreshDatabase;

    public function test_sec_001_password_tersimpan_ter_hash_bcrypt(): void
    {
        $plain = 'Rahasia123!';
        $user = User::factory()->create(['peran' => 'admin', 'password' => $plain]);

        $tersimpan = $user->fresh()->password;

        $this->assertNotSame($plain, $tersimpan, 'Password tidak boleh tersimpan plain-text');
        $this->assertStringStartsWith('$2y$', $tersimpan, 'Format hash harus bcrypt');
        $this->assertTrue(Hash::check($plain, $tersimpan));
        $this->assertFalse(Hash::check('salah-password', $tersimpan));
    }
}
