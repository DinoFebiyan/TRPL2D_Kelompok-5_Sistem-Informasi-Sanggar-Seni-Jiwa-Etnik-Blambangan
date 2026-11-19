<?php

namespace Tests\Feature\Event;

use App\Models\User;
use App\Models\Event;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TC_EVT_004_HapusEventPosterTest extends TestCase
{
    use RefreshDatabase;

    public function test_evt_004_hapus_event_menghapus_poster_dan_tercatat_log()
    {
        Storage::fake('public');
        // dump(Storage::disk('public')->allFiles());

        $admin = User::factory()->create();

        $event = Event::create([
            'nama' => 'Event Hapus Uji',
            'deskripsi' => 'Deskripsi event untuk diuji hapus.',
            'tanggal' => '2026-11-25',
            'jam' => '10:00',
            'lokasi' => 'Sanggar JEB',
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai',
            'user_id' => $admin->id,
        ]);

        $poster = UploadedFile::fake()->image('poster_hapus.jpg');
        $path = $poster->store('galeri/event', 'public');

        Galeri::create([
            'sumber_id' => $event->id,
            'path' => $path,
            'nama_file' => 'poster_hapus.jpg',
            'kategori' => 'event',
            'is_watermark' => 1,
        ]);
        dump($path);

        Storage::disk('public')->assertExists($path);
        $this->assertDatabaseHas('event', ['id' => $event->id]);
        $this->assertDatabaseHas('galeri', ['sumber_id' => $event->id]);
                

        $response = $this->actingAs($admin)->delete(route('superadmin.hapus-event', $event->id));

        $this->assertDatabaseMissing('event', [
            'id' => $event->id,
        ]);

        $publicSearchResponse = $this->get('/event?search=Event Hapus Uji');
        $publicSearchResponse->assertStatus(200);
        $publicSearchResponse->assertDontSee('25 Nov 2026');

        $this->assertDatabaseMissing('galeri', [
            'sumber_id' => $event->id,
        ]);
        Storage::disk('public')->assertMissing($path);
    }
}
