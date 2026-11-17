<?php

namespace Tests\Feature\Personel;

use App\Models\Galeri;
use App\Models\Personel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TC_PSN_001_TambahPengurusPelatihTest extends TestCase
{
    use RefreshDatabase;

    public function test_psn_001_admin_menambahkan_pengurus_beserta_foto()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['peran' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.store-pengurus'), [
            'nia' => 'P-101',
            'nama' => 'Surya Pengurus',
            'jabatan' => 'Ketua Sanggar',
            'no_hp' => '081234567890',
            'instagram' => '@surya.jeb',
            'status' => 'aktif',
            'deskripsi' => 'Pengurus sejak 2010.',
            'prestasi' => 'Juara 1 Festival Gandrung 2025',
            'masa_bakti' => '2010 - 2026',
            'foto' => [UploadedFile::fake()->image('pengurus.jpg', 600, 800)],
        ]);

        $response->assertRedirect(route('admin.kelola-profil'));
        $response->assertSessionHas('success');

        $personel = Personel::where('nia', 'P-101')->first();
        $this->assertNotNull($personel);
        $this->assertSame('Surya Pengurus', $personel->nama);
        $this->assertSame('pengurus', $personel->peran);
        $this->assertSame('aktif', $personel->status);

        $foto = Galeri::where('sumber_id', $personel->id)->where('kategori', 'profil')->first();
        $this->assertNotNull($foto);
        $this->assertFalse((bool) $foto->is_watermark);
        Storage::disk('public')->assertExists($foto->path);

        $this->assertDatabaseHas('log_aktivitas', [
            'user_id' => $admin->id,
            'aktivitas' => 'Menambahkan pengurus baru: Surya Pengurus',
        ]);
    }

    public function test_psn_001_admin_menambahkan_pelatih_beserta_foto()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['peran' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.store-pelatih'), [
            'nia' => 'L-101',
            'nama' => 'Rina Pelatih',
            'jabatan' => 'Pelatih Tari',
            'no_hp' => '082234567890',
            'status' => 'aktif',
            'deskripsi' => 'Pelatih tari Gandrung.',
            'foto' => [UploadedFile::fake()->image('pelatih.jpg', 600, 800)],
        ]);

        $response->assertRedirect(route('admin.kelola-profil'));

        $pelatih = Personel::where('nia', 'L-101')->first();
        $this->assertNotNull($pelatih);
        $this->assertSame('pelatih', $pelatih->peran);

        $this->assertDatabaseHas('galeri', [
            'sumber_id' => $pelatih->id,
            'kategori' => 'profil',
        ]);
    }
}
