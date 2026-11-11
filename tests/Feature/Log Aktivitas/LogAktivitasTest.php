<?php

namespace Tests\Feature;

use App\Models\LogAktivitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\DataUji;
use Tests\TestCase;


class LogAktivitasTest extends TestCase
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

    
    public function test_log_003_admin_tidak_dapat_mengakses_log_aktivitas(): void
    {
        $response = $this->actingAs($this->buatAdmin())
            ->get(route('superadmin.log-aktivitas'));

        $response->assertForbidden();
    }
}