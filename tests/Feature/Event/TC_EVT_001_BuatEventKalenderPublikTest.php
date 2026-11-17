<?php

namespace Tests\Feature\Event;

use App\Models\User;
use App\Models\Event;
use App\Models\Galeri;
use App\Models\LogAktivitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TC_EVT_001_BuatEventKalenderPublikTest extends TestCase
{
    use RefreshDatabase;

    public function test_evt_001_admin_membuat_event_valid_berwatermark_tampil_kalender_publik()
    {
        Storage::fake('public');

        // 1. Buat User Superadmin / Admin
        $admin = User::factory()->create();

        // 2. Akses halaman form tambah event menggunakan route name yang sesuai[cite: 12]
        $createPageResponse = $this->actingAs($admin)->get(route('superadmin.tambah-event'));
        $createPageResponse->assertStatus(200);

        // Menyiapkan file poster dummy untuk diunggah
        $poster = UploadedFile::fake()->image('poster_gandrung.jpg')->size(1400);

        // 3. Data Uji sesuai spesifikasi form dan aturan validasi controller
        $postData = [
            'nama' => 'Pentas Seni Gandrung Banyuwangi',
            'deskripsi' => 'Pentas tari Gandrung oleh Sanggar JEB.',
            'tanggal' => '2026-11-15',
            'jam' => '19:00',
            'lokasi' => 'Sanggar JEB, Banyuwangi',
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai', 
            'foto' => [$poster],        
        ];

        // 4. Kirim data form ke endpoint store[cite: 12]
        $response = $this->actingAs($admin)->post(route('superadmin.store-event'), $postData);


        // 5. Memastikan data event berhasil masuk ke database
        $this->assertDatabaseHas('event', [
            'nama' => 'Pentas Seni Gandrung Banyuwangi',
            'lokasi' => 'Sanggar JEB, Banyuwangi',
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai',
            'user_id' => $admin->id,
        ]);

        $event = Event::where('nama', 'Pentas Seni Gandrung Banyuwangi')->first();

        dump(Event::all());
        // 6. Memastikan galeri/foto tersimpan, terhubung ke event, dan memiliki status watermark (is_watermark = 1)
        $this->assertDatabaseHas('galeri', [
            'sumber_id' => $event->id,
            'kategori' => 'event',
            'is_watermark' => 1, 
        ]);

        // 7. Memastikan log aktivitas tercatat di database
        $this->assertDatabaseHas('log_aktivitas', [
            'user_id' => $admin->id,
            'aktivitas' => 'Menambahkan event baru: Pentas Seni Gandrung Banyuwangi',
        ]);

        // 8. Memastikan event baru tampil pada halaman kalender / event publik
        $publicCalendarResponse = $this->get('/event');
        $publicCalendarResponse->assertStatus(200);
        $publicCalendarResponse->assertSee('Pentas Seni Gandrung Banyuwangi');
        $publicCalendarResponse->assertSee('15 Nov 2026');
    }
}
