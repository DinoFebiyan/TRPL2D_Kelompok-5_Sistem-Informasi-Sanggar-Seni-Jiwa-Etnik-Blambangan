<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use App\Models\Event;
use App\Models\Berita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard()
    {
        // 1. Buat User dengan peran 'admin'
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'peran' => 'admin',
            'no_handphone' => '081234567892',
            'status' => 'aktif',
        ]);

        // 2. Buat data dummy Event Aktif (misal status 'belum selesai' atau sesuai kondisi controller dashboard admin)
        Event::create([
            'nama' => 'Pentas Seni Budaya',
            'deskripsi' => 'Pertunjukan seni tradisional.',
            'tanggal' => '2026-11-10',
            'jam' => '19:00',
            'lokasi' => 'Pendopo Banyuwangi',
            'kategori' => 'Lainnya',
            'status' => 'belum selesai', // atau status aktif lainnya
            'user_id' => $admin->id,
        ]);

        // 3. Buat data dummy Berita (misal status 'tayang')
        Berita::create([
            'judul' => 'Festival Etnik Nusantara',
            'isi_berita' => 'Berita seputar festival budaya...',
            'jenis_berita' => 'Pengumuman',
            'status' => 'tayang',
            'user_id' => $admin->id,
            'tgl_terbit' => '2026-10-24',
        ]);

        // 4. Lakukan login sebagai Admin dan akses dashboard admin
        $response = $this->actingAs($admin)->get('/admin/dashboard'); // Sesuaikan URL route jika berbeda

        // 5. Verifikasi halaman sukses diakses (HTTP Status 200)
        $response->assertStatus(200);

        // 6. Cek kesesuaian jumlah data (count) dengan variabel viewData yang dikirim controller
        $this->assertEquals(Event::where('status', 'belum selesai')->count(), $response->viewData('activeEvents'));
        $this->assertEquals(Berita::where('status', 'tayang')->count(), $response->viewData('totalBerita'));

        // 7. Verifikasi Quick Access Card (Buat Event, Buat Berita, dan Edit Profil)
        $response->assertSee(url('/admin/kelola-event/tambah-event')); // Sesuaikan URL path jika rute admin berbeda
        $response->assertSee(url('/admin/kelola-berita/tambah-berita')); 
        $response->assertSee(url('/admin/kelola-profil/edit-profil'));

        // 4. Langkah 3: Verifikasi menu eksklusif Superadmin TIDAK tampil (Kelola Admin, Log Aktivitas, dll)
        $response->assertDontSee('Kelola Admin');
        $response->assertDontSee('Kelola Katalog');
        $response->assertDontSee('Log Aktivitas');
        $response->assertDontSee('Aktivitas Terbaru');
    }
}