<?php

namespace Tests\Feature;

use App\Models\LogAktivitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;

/**
 * TC-LOG-002 : Superadmin dapat melihat, mencari, dan memfilter
 * log aktivitas berdasarkan jenis operasi serta kata kunci.
 */
class TC_LOG_002_MonitorLogAktivitasTest extends TestCase
{
    use DataUji;
    use RefreshDatabase;

    public function test_log_002_superadmin_memantau_log_aktivitas(): void
    {
        $superadmin = $this->buatSuperadmin();

        LogAktivitas::create([
            'user_id' => $superadmin->id,
            'aktivitas' => 'Menambahkan katalog produk baru: Kostum Gandrung',
            'waktu' => now(),
        ]);

        $response = $this->actingAs($superadmin)->get(route('superadmin.log-aktivitas'));

        $response->assertOk();
        $response->assertSee('Menambahkan katalog produk baru: Kostum Gandrung');
    }
}
