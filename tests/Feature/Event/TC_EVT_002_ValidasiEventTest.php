<?php

namespace Tests\Feature\Event;

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TC_EVT_002_ValidasiEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_evt_002_menolak_field_wajib_kosong()
    {
        $admin = User::create([
            'name' => 'Admin Sanggar',
            'email' => 'admin@sanggar.com',
            'password' => bcrypt('password'),
            'peran' => 'superadmin',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->post(route('superadmin.store-event'), [
            'nama' => '',
            'deskripsi' => 'Deskripsi uji validasi kosong.',
            'tanggal' => '2026-11-25',
            'jam' => '10:00',
            'lokasi' => '',
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai',
        ]);

        $response->assertSessionHasErrors(['nama', 'lokasi']);
        
        // dump($response->getSession()->get('errors')->all());
        $this->assertDatabaseCount('event', 0);
    }

    public function test_evt_002_menolak_format_tanggal_salah()
    {
        $admin = User::create([
            'name' => 'Admin Sanggar',
            'email' => 'admin@sanggar.com',
            'password' => bcrypt('password'),
            'peran' => 'superadmin',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->post(route('superadmin.store-event'), [
            'nama' => 'Event Tanggal Salah',
            'deskripsi' => 'Deskripsi uji format tanggal.',
            'tanggal' => '32/13/2026',
            'jam' => '10:00',
            'lokasi' => 'Sanggar JEB',
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai',
        ]);

        $response->assertSessionHasErrors(['tanggal']);
        
        $this->assertDatabaseCount('event', 0);
    }

    public function test_evt_002_menolak_kategori_invalid()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('superadmin.store-event'), [
            'nama' => 'Event Kategori Salah',
            'deskripsi' => 'Deskripsi uji kategori invalid.',
            'tanggal' => '2026-11-25',
            'jam' => '10:00',
            'lokasi' => 'Sanggar JEB',
            'kategori' => 'Seminar',
            'status' => 'belum selesai',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['kategori']);
        
        $this->assertDatabaseCount('event', 0);
    }
}