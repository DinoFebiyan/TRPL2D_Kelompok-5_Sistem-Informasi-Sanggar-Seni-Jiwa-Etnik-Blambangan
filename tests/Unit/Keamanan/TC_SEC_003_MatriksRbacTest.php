<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-SEC-003 : Matriks RBAC — Admin ditolak mengakses fitur eksklusif
 * Superadmin (kelola admin, katalog, log aktivitas) melalui URL langsung.
 */
class TC_SEC_003_MatriksRbacTest extends TestCase
{
    use DataUji;
    use RefreshDatabase;

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
