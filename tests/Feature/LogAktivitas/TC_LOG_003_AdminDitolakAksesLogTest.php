<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-LOG-003 : Admin (dan pengunjung tanpa login) tidak dapat mengakses
 * halaman log aktivitas, baik melalui menu maupun URL langsung.
 */
class TC_LOG_003_AdminDitolakAksesLogTest extends TestCase
{
    use DataUji;
    use RefreshDatabase;

    public function test_log_003_admin_tidak_dapat_mengakses_log_aktivitas(): void
    {
        $response = $this->actingAs($this->buatAdmin())
            ->get(route('superadmin.log-aktivitas'));

        $response->assertForbidden();
    }
}
