<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-SEC-002 : Middleware auth mengalihkan akses langsung ke URL halaman
 * administratif tanpa login menuju form login tanpa menampilkan data.
 */
class TC_SEC_002_MiddlewareAuthGuestTest extends TestCase
{
    use DataUji;
    use RefreshDatabase;

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
}
