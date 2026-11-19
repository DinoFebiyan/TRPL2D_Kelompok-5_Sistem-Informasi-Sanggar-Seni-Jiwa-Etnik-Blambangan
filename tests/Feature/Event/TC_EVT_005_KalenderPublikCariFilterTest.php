<?php

namespace Tests\Feature\Event;

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TC_EVT_005_KalenderPublikCariFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_evt_005_halaman_publik_pencarian_filter_dan_pesan_kosong()
    {
        $admin = User::factory()->create();

        $event1 = Event::create([
            'nama' => 'Pentas Seni Gandrung Banyuwangi',
            'deskripsi' => 'Pertunjukan tari tradisional.',
            'tanggal' => '2026-11-15',
            'jam' => '19:00',
            'lokasi' => 'Sanggar JEB, Banyuwangi',
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai',
            'user_id' => $admin->id,
        ]);

        $event2 = Event::create([
            'nama' => 'Latihan Rutin Mingguan',
            'deskripsi' => 'Latihan tari rutin.',
            'tanggal' => '2026-11-18',
            'jam' => '15:00',
            'lokasi' => 'Studio Utama',
            'kategori' => 'Latihan',
            'status' => 'belum selesai',
            'user_id' => $admin->id,
        ]);

        $event3 = Event::create([
            'nama' => 'Festival Tari 2025',
            'deskripsi' => 'Festival tari tahunan.',
            'tanggal' => '2025-12-10',
            'jam' => '09:00',
            'lokasi' => 'GOR Blambangan',
            'kategori' => 'Pertunjukan',
            'status' => 'selesai',
            'user_id' => $admin->id,
        ]);

        $response = $this->get('/event');
        $response->assertStatus(200);
        $response->assertSee('Pentas Seni Gandrung Banyuwangi');
        $response->assertSee('Latihan Rutin Mingguan');
        $response->assertSee('Festival Tari 2025');

        $searchResponse = $this->get('/event?search=Gandrung');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Pentas Seni Gandrung Banyuwangi');
        $searchResponse->assertDontSee('Latihan Rutin Mingguan');

        $locationSearchResponse = $this->get('/event?search=Banyuwangi');
        $locationSearchResponse->assertStatus(200);
        $locationSearchResponse->assertSee('Pentas Seni Gandrung Banyuwangi');
        // dump($locationSearchResponse->content());

        $filterStatusResponse = $this->get('/event?search=&status%5B%5D=selesai'); 
        $filterStatusResponse->assertStatus(200);
        $filterStatusResponse->assertSee('Festival Tari 2025');
        $filterStatusResponse->assertDontSee('Pentas Seni Gandrung Banyuwangi');

        $detailResponse = $this->get('/event/' . $event1->id);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Pentas Seni Gandrung Banyuwangi');
        $detailResponse->assertSee('15 November 2026');
        $detailResponse->assertSee('19:00');
        $detailResponse->assertSee('Pertunjukan tari tradisional.');
        $detailResponse->assertSee('Sanggar JEB, Banyuwangi');

        Event::truncate();
        dump(Event::count());
        $emptyResponse = $this->get('/event');
        $emptyResponse->assertStatus(200);
        $emptyResponse->assertSee('Belum ada event tersedia');
    }
}
