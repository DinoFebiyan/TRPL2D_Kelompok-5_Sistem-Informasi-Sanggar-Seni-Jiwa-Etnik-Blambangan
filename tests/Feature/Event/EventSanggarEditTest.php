<?php

namespace Tests\Feature\Event;

use App\Models\User;
use App\Models\Event;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventSanggarEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_existing_event_data_and_poster()
    {
        Storage::fake('public');

        // 1. Buat User Superadmin / Admin (Prasyarat)
        $admin = User::factory()->create();

        // 2. Buat data Event awal sesuai prasyarat TC-EVT-003 (Tanggal awal: 15-11-2026, 19:00)
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

        // Buat data galeri awal (poster lama) yang terhubung ke event
        $oldPoster = UploadedFile::fake()->image('poster_gandrung.jpg');
        $path = $oldPoster->store('galeri/event', 'public');
        Galeri::create([
            'sumber_id' => $event->id,
            'path' => $path,
            'nama_file' => 'poster_gandrung.jpg',
            'kategori' => 'event',
            'is_watermark' => 1,
        ]);

        // Langkah 1: Akses halaman form edit event berdasarkan route name
        $editPageResponse = $this->actingAs($admin)->get(route('superadmin.edit-event', $event->id));
        $editPageResponse->assertStatus(200);
        $editPageResponse->assertSee('Pentas Seni Gandrung Banyuwangi');

        // Langkah 2 & 3: Siapkan data uji baru sesuai Test Data TC-EVT-003
        $newPoster = UploadedFile::fake()->image('poster_gandrung_v2.jpg', 800, 600)->size(1200); // 1.2 MB

        $updateData = [
            'nama' => 'Pentas Seni Gandrung Banyuwangi',
            'deskripsi' => 'Pentas tari Gandrung oleh Sanggar JEB (Update Terbaru).',
            'tanggal' => '2026-11-20',   // Perubahan tanggal baru
            'jam' => '20:00',            // Perubahan jam baru
            'lokasi' => 'Pendopo Banyuwangi', // Perubahan lokasi baru
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai',
            'foto' => [$newPoster],      // Mengunggah poster baru
        ];

        // Kirim request pembaruan data (PUT method sesuai rute update)
        $response = $this->actingAs($admin)->put(route('superadmin.update-event', $event->id), $updateData);

        // Memastikan data event di database telah diperbarui
        $this->assertDatabaseHas('event', [
            'id' => $event->id,
            'tanggal' => '2026-11-20 00:00:00', // Sesuaikan dengan format penyimpanan tanggal di SQLite
            'lokasi' => 'Pendopo Banyuwangi',
            'jam' => '20:00',
        ]);

        // Memastikan poster baru tersimpan di galeri dengan status watermark (is_watermark = 1)
        $this->assertDatabaseHas('galeri', [
            'sumber_id' => $event->id,
            'kategori' => 'event',
            'is_watermark' => 1,
        ]);

        // Memastikan log aktivitas pencatatan pembaruan event tercatat
        $this->assertDatabaseHas('log_aktivitas', [
            'user_id' => $admin->id,
        ]);

        // Langkah 4: Buka halaman kalender / event publik dan verifikasi data baru
        $publicCalendarResponse = $this->get('/event');
        $publicCalendarResponse->assertStatus(200);
        $publicCalendarResponse->assertSee('Pendopo Banyuwangi'); // Lokasi baru tampil
        $publicCalendarResponse->assertDontSee('Sanggar JEB, Banyuwangi'); // Lokasi lama tidak tampil
        $publicCalendarResponse->assertSee('20 Nov 2026'); // Tanggal baru tampil
        $publicCalendarResponse->assertDontSee('15 Nov 2026'); // Tanggal
    }
}