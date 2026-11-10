<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\DataUji;
use Tests\TestCase;

class KeamananTest extends TestCase
{
    use DataUji;
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

    public static function urlTerlindungi(): array
    {
        return [
            'dashboard superadmin' => ['/superadmin/dashboard'],
            'kelola katalog' => ['/superadmin/kelola-katalog'],
            'log aktivitas' => ['/superadmin/log-aktivitas'],
            'dashboard admin' => ['/admin/dashboard'],
            'kelola berita admin' => ['/admin/kelola-berita'],
        ];
    }

    #[DataProvider('urlTerlindungi')]
    public function test_sec_002_guest_ditolak_di_halaman_terlindungi(string $url): void
    {
        $response = $this->get($url);

        $this->assertDitolak($response);
    }

    public static function matriksRbac(): array
    {
        return [
            'superadmin -> dashboard superadmin' => ['superadmin', '/superadmin/dashboard', true],
            'superadmin -> kelola admin' => ['superadmin', '/superadmin/kelola-admin', true],
            'superadmin -> kelola katalog' => ['superadmin', '/superadmin/kelola-katalog', true],
            'superadmin -> log aktivitas' => ['superadmin', '/superadmin/log-aktivitas', true],

            'admin -> dashboard admin' => ['admin', '/admin/dashboard', true],
            'admin -> dashboard superadmin' => ['admin', '/superadmin/dashboard', false],
            'admin -> kelola admin' => ['admin', '/superadmin/kelola-admin', false],
            'admin -> kelola katalog' => ['admin', '/superadmin/kelola-katalog', false],
            'admin -> log aktivitas' => ['admin', '/superadmin/log-aktivitas', false],
        ];
    }

    #[DataProvider('matriksRbac')]
    public function test_sec_003_matriks_rbac(string $peran, string $url, bool $boleh): void
    {
        $user = User::factory()->create(['peran' => $peran]);

        $response = $this->actingAs($user)->get($url);

        if ($boleh) {
            $response->assertOk();
        } else {
            $this->assertDitolak($response);
        }
    }
}