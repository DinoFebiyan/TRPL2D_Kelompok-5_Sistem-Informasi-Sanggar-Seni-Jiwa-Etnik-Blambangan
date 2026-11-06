<?php

namespace Tests\Feature\Event;

use App\Models\User;
use App\Models\Event;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventSanggarDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_event_and_its_associated_poster_and_log()
    {
        Storage::fake('public');
        // dump(Storage::disk('public')->allFiles());

        // 1. Buat User Superadmin / Admin (Prasyarat)
        $admin = User::factory()->create();

        // 2. Buat data event 'Event Hapus Uji' sesuai prasyarat kasus uji
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

        // Buat file poster dummy dan simpan ke disk public & tabel galeri
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

        // Memastikan data awal dan file fisik benar-benar ada sebelum dihapus
        Storage::disk('public')->assertExists($path);
        $this->assertDatabaseHas('event', ['id' => $event->id]);
        $this->assertDatabaseHas('galeri', ['sumber_id' => $event->id]);
                

        // Langkah 1: Kirim request hapus event
        $response = $this->actingAs($admin)->delete(route('superadmin.hapus-event', $event->id));

        // Langkah 2: Periksa daftar event admin (data event sudah hilang dari database)
        $this->assertDatabaseMissing('event', [
            'id' => $event->id,
        ]);

        // Langkah 3 & Validasi Pencarian: Periksa halaman publik dan fitur pencarian event
        // (Ganti parameter 'search' dengan nama parameter query GET di controller Anda, misal: 'q', 'keyword', atau 'search')
        $publicSearchResponse = $this->get('/event?search=Event Hapus Uji');
        $publicSearchResponse->assertStatus(200);
        $publicSearchResponse->assertDontSee('25 Nov 2026');

        // Langkah 4: Periksa tabel galeri dan folder storage (berkas fisik dan record galeri terhapus)
        $this->assertDatabaseMissing('galeri', [
            'sumber_id' => $event->id,
        ]);
        Storage::disk('public')->assertMissing($path);
    }
}