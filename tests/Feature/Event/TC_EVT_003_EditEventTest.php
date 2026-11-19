<?php

namespace Tests\Feature\Event;

use App\Models\User;
use App\Models\Event;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TC_EVT_003_EditEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_evt_003_admin_mengedit_data_event_dan_poster()
    {
        Storage::fake('public');

        $admin = User::factory()->create();

        $event = Event::create([
            'nama' => 'Pentas Seni Gandrung Banyuwangi',
            'deskripsi' => 'Pentas tari Gandrung oleh Sanggar JEB.',
            'tanggal' => '2026-11-15',
            'jam' => '19:00',
            'lokasi' => 'Sanggar JEB, Banyuwangi',
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai',
            'user_id' => $admin->id,
        ]);

        $oldPoster = UploadedFile::fake()->image('poster_gandrung.jpg');
        $path = $oldPoster->store('galeri/event', 'public');
        Galeri::create([
            'sumber_id' => $event->id,
            'path' => $path,
            'nama_file' => 'poster_gandrung.jpg',
            'kategori' => 'event',
            'is_watermark' => 1,
        ]);

        $editPageResponse = $this->actingAs($admin)->get(route('superadmin.edit-event', $event->id));
        $editPageResponse->assertStatus(200);
        $editPageResponse->assertSee('Pentas Seni Gandrung Banyuwangi');

        $newPoster = UploadedFile::fake()->image('poster_gandrung_v2.jpg', 800, 600)->size(1200);

        $updateData = [
            'nama' => 'Pentas Seni Gandrung Banyuwangi',
            'deskripsi' => 'Pentas tari Gandrung oleh Sanggar JEB (Update Terbaru).',
            'tanggal' => '2026-11-20',
            'jam' => '20:00',
            'lokasi' => 'Pendopo Banyuwangi',
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai',
            'foto' => [$newPoster],
        ];

        $response = $this->actingAs($admin)->put(route('superadmin.update-event', $event->id), $updateData);

        $this->assertDatabaseHas('event', [
            'id' => $event->id,
            'tanggal' => '2026-11-20 00:00:00',
            'lokasi' => 'Pendopo Banyuwangi',
            'jam' => '20:00',
        ]);

        $this->assertDatabaseHas('galeri', [
            'sumber_id' => $event->id,
            'kategori' => 'event',
            'is_watermark' => 1,
        ]);

        $this->assertDatabaseHas('log_aktivitas', [
            'user_id' => $admin->id,
        ]);

        $publicCalendarResponse = $this->get('/event');
        $publicCalendarResponse->assertStatus(200);
        $publicCalendarResponse->assertSee('Pendopo Banyuwangi');
        $publicCalendarResponse->assertDontSee('Sanggar JEB, Banyuwangi');
        $publicCalendarResponse->assertSee('20 Nov 2026');
        $publicCalendarResponse->assertDontSee('15 Nov 2026');
    }
}
