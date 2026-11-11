<?php

namespace Tests\Feature\Event;

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventPublikTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_event_page_search_filter_and_empty_state()
    {
        // Prasyarat: Buat user pembuat event (admin/superadmin)
        $admin = User::factory()->create();

        // Prasyarat 1: Sediakan 3 event sesuai data uji TC-EVT-005
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

        // Langkah 1: Buka halaman 'Event' tanpa login (Guest)
        $response = $this->get('/event');
        $response->assertStatus(200);
        $response->assertSee('Pentas Seni Gandrung Banyuwangi');
        $response->assertSee('Latihan Rutin Mingguan');
        $response->assertSee('Festival Tari 2025');

        // Langkah 2: Isi pencarian dengan nama event 'Gandrung'
        // (Sesuaikan parameter query GET, misal 'search' atau 'q' sesuai controller pencarian publik Anda)
        $searchResponse = $this->get('/event?search=Gandrung');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Pentas Seni Gandrung Banyuwangi');
        $searchResponse->assertDontSee('Latihan Rutin Mingguan');

        // Langkah 3: Ganti pencarian dengan kata kunci lokasi 'Banyuwangi'\
        $locationSearchResponse = $this->get('/event?search=Banyuwangi'); // atau parameter lokasi khusus
        $locationSearchResponse->assertStatus(200);
        $locationSearchResponse->assertSee('Pentas Seni Gandrung Banyuwangi');
        // dump($locationSearchResponse->content());

        // Langkah 4: Pilih filter status 'Selesai'[cite: 9]
        $filterStatusResponse = $this->get('/event?search=&status%5B%5D=selesai'); 
        $filterStatusResponse->assertStatus(200);
        $filterStatusResponse->assertSee('Festival Tari 2025');
        $filterStatusResponse->assertDontSee('Pentas Seni Gandrung Banyuwangi');

        // Langkah 5: Klik salah satu event untuk melihat detail event[cite: 9]
        $detailResponse = $this->get('/event/' . $event1->id); // Sesuaikan rute detail event publik
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Pentas Seni Gandrung Banyuwangi');
        $detailResponse->assertSee('15 November 2026');
        $detailResponse->assertSee('19:00');
        $detailResponse->assertSee('Pertunjukan tari tradisional.');
        $detailResponse->assertSee('Sanggar JEB, Banyuwangi');

        // Langkah 6: Kosongkan seluruh data event lalu buka kembali halaman kalender[cite: 9]
        Event::truncate(); // Menghapus seluruh data event di database
        dump(Event::count());
        $emptyResponse = $this->get('/event');
        $emptyResponse->assertStatus(200);
        $emptyResponse->assertSee('Belum ada event tersedia'); // Sesuaikan teks pesan kosong di view blade Anda
    }
}