<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TC_EVT_006_FilterStatusEventPublikTest extends TestCase
{
    use RefreshDatabase;

    public function test_evt_006_pencarian_dan_filter_status_event_publik()
    {

        $admin = User::factory()->create();

        $event1 = Event::create([
            'user_id' => $admin->id,
            'nama' => 'Latihan Rutin Mingguan',
            'deskripsi' => 'Latihan rutin tari.',
            'tanggal' => '2026-11-20',
            'jam' => '15:00',
            'lokasi' => 'Studio Sanggar',
            'kategori' => 'Pelatihan',
            'status' => 'belum selesai',
        ]);

        $event2 = Event::create([
            'user_id' => $admin->id,
            'nama' => 'Festival Tari 2025',
            'deskripsi' => 'Festival akhir tahun.',
            'tanggal' => '2025-12-10',
            'jam' => '08:00',
            'lokasi' => 'Pendopo Kabupaten',
            'kategori' => 'Pertunjukan',
            'status' => 'selesai',
        ]);

        $responseFilter1 = $this->get('/event?search=&status%5B%5D=belum+selesai');
        $responseFilter1->assertStatus(200);
        $responseFilter1->assertSee('Latihan Rutin Mingguan');
        $responseFilter1->assertDontSee('Festival Tari 2025');
        
        $responseFilter2 = $this->get('/event?search=&status%5B%5D=selesai');
        $responseFilter2->assertStatus(200);
        $responseFilter2->assertSee('Festival Tari 2025');
        $responseFilter2->assertDontSee('Latihan Rutin Mingguan');
        // dump($responseFilter2->content());
        // dump($responseFilter2->status());

        $responseComb1 = $this->get('/event?search=Latihan&status%5B%5D=belum+selesai');
        $responseComb1->assertStatus(200);
        $responseComb1->assertSee('Latihan Rutin Mingguan');

        $responseComb2 = $this->get('/event?search=Latihan&status%5B%5D=selesai');
        $responseComb2->assertStatus(200);
        $responseComb2->assertDontSee('Latihan Rutin Mingguan');
        $responseComb2->assertDontSee('Festival Tari 2025');
        $responseComb2->assertSee('Belum ada event tersedia'); 

        $responseReset = $this->get('/event');
        $responseReset->assertStatus(200);
        $responseReset->assertSee('Latihan Rutin Mingguan');
        $responseReset->assertSee('Festival Tari 2025');
    }
}
