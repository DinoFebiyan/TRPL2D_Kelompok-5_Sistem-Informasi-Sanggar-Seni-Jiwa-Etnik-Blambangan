<?php

namespace Tests\Feature\Event;

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventSanggarValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_rejects_empty_required_fields()
    {
        // Prasyarat: Login sebagai admin/superadmin
        $admin = User::create([
            'name' => 'Admin Sanggar',
            'email' => 'admin@sanggar.com',
            'password' => bcrypt('password'),
            'peran' => 'superadmin',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
        ]);

        // Langkah 1: Kosongkan nama dan lokasi (field wajib) lalu kirim request simpan[cite: 8]
        $response = $this->actingAs($admin)->post(route('superadmin.store-event'), [
            'nama' => '',
            'deskripsi' => 'Deskripsi uji validasi kosong.',
            'tanggal' => '2026-11-25',
            'jam' => '10:00',
            'lokasi' => '',
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai',
        ]);

        // Memastikan divalidasi dan dikembalikan dengan pesan error pada field terkait
        $response->assertSessionHasErrors(['nama', 'lokasi']);
        
        // dump($response->getSession()->get('errors')->all());
        // Langkah 4: Memastikan tidak ada data event yang tersimpan di database[cite: 8]
        $this->assertDatabaseCount('event', 0);
    }

    public function test_event_rejects_invalid_date_format()
    {
        $admin = User::create([
            'name' => 'Admin Sanggar',
            'email' => 'admin@sanggar.com',
            'password' => bcrypt('password'),
            'peran' => 'superadmin',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
        ]);

        // Langkah 2: Isi tanggal dengan format salah (misal: 32/13/2026)[cite: 8]
        $response = $this->actingAs($admin)->post(route('superadmin.store-event'), [
            'nama' => 'Event Tanggal Salah',
            'deskripsi' => 'Deskripsi uji format tanggal.',
            'tanggal' => '32/13/2026',
            'jam' => '10:00',
            'lokasi' => 'Sanggar JEB',
            'kategori' => 'Pertunjukan',
            'status' => 'belum selesai',
        ]);

        // Memastikan tanggal tidak valid ditolak oleh validasi
        $response->assertSessionHasErrors(['tanggal']);
        
        // Langkah 4: Pastikan tidak ada data event baru yang tersimpan[cite: 8]
        $this->assertDatabaseCount('event', 0);
    }

    public function test_event_tolak_kategori_invalid()
    {
        $admin = User::factory()->create();

        // Langkah 3: Ubah value kategori menjadi di luar pilihan resmi (misal: 'Seminar')[cite: 8]
        $response = $this->actingAs($admin)->post(route('superadmin.store-event'), [
            'nama' => 'Event Kategori Salah',
            'deskripsi' => 'Deskripsi uji kategori invalid.',
            'tanggal' => '2026-11-25',
            'jam' => '10:00',
            'lokasi' => 'Sanggar JEB',
            'kategori' => 'Seminar',
            'status' => 'belum selesai',
        ]);

        // Memastikan ditolak oleh validasi sisi server tanpa error 500 (mengembalikan error session)[cite: 8]
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['kategori']);
        
        // Langkah 4: Memastikan tabel event tetap kosong[cite: 8]
        $this->assertDatabaseCount('event', 0);
    }
}