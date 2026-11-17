<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use App\Models\Event;
use App\Models\Katalog;
use App\Models\Berita;
use App\Models\LogAktivitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TC_DSH_001_DashboardSuperadminTest extends TestCase
{
    use RefreshDatabase;

    public function test_dsh_001_dashboard_superadmin_tampil_lengkap()
    {
        $superadmin = User::factory()->create();
        // dump($superadmin);
        Event::create([
            'nama' => 'Pentas Seni Blambangan',
            'deskripsi' => 'Festival seni tari tradisional.',
            'tanggal' => '2026-11-01',
            'jam' => '19:00',
            'lokasi' => 'Gedung Seni Banyuwangi',
            'kategori' => 'Lainnya',
            'status' => 'belum selesai',
            'user_id' => $superadmin->id,
        ]);

        Katalog::create([
            'nama_katalog' => 'Kostum Gandrung',
            'kategori' => 'Pakaian Adat',
            'stok' => 10,
            'deskripsi' => 'Kostum penari tradisional gandrung.',
            'status' => 'tersedia',
            'user_id' => $superadmin->id,
        ]);

        Berita::create([
            'judul' => 'Festival Budaya Nusantara 2026',
            'isi_berita' => 'Detail informasi mengenai festival budaya...',
            'jenis_berita' => 'Pengumuman',
            'status' => 'tayang',
            'user_id' => $superadmin->id,
            'tgl_terbit' => '2026-10-24',
        ]);

        LogAktivitas::create([
            'user_id' => $superadmin->id,
            'aktivitas' => 'Menambahkan event baru',
            'waktu' => now(),
        ]);

        $response = $this->actingAs($superadmin)->get('/superadmin/dashboard');

        // dump($response->status());
        $response->assertStatus(200);

        $this->assertEquals(Event::count(), $response->viewData('eventMendatangCount'));
        $this->assertEquals(Katalog::count(), $response->viewData('totalKatalogCount'));
        $this->assertEquals(Berita::where('status', 'tayang')->count(), $response->viewData('beritaTayangCount'));
        $this->assertEquals(User::where('peran', 'admin')->count(), $response->viewData('totalAdmin'));
        dump(Event::count());
        dump($response->viewData('eventMendatangCount'));
        // dump($response->viewData('eventMendatangCount'));
        // dump(Event::count());

        $response->assertSee(url('/superadmin/kelola-event'));
        $response->assertSee(url('/superadmin/kelola-katalog'));
        $response->assertSee(url('/superadmin/kelola-berita'));
        $response->assertSee(url('/superadmin/kelola-admin'));
        dump(url('/superadmin/kelola-event'));
        // dump($response->content());
        // dump(url('/superadmin/kelola-event'));
        $response->assertSee('Menambahkan event baru');
        $response->assertSee('Pentas Seni Blambangan');
        dump($response->content());
    }
}
