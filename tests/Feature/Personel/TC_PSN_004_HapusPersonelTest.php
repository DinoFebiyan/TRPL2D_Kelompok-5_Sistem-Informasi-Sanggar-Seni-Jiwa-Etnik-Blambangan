<?php

namespace Tests\Feature\Personel;

use App\Models\Galeri;
use App\Models\Personel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TC_PSN_004_HapusPersonelTest extends TestCase
{
    use RefreshDatabase;

    public function test_psn_004_admin_menghapus_pengurus_beserta_foto_galeri()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['peran' => 'admin']);

        $pengurus = Personel::create([
            'nia' => 'P-301',
            'nama' => 'Dihapus Saja',
            'jabatan' => 'Anggota',
            'no_handphone' => '081200000201',
            'peran' => 'pengurus',
            'status' => 'aktif',
        ]);

        Storage::disk('public')->put('personel/foto-pengurus.jpg', 'isi-gambar');

        $foto = Galeri::create([
            'sumber_id' => $pengurus->id,
            'path' => 'personel/foto-pengurus.jpg',
            'nama_file' => 'foto-pengurus.jpg',
            'kategori' => 'profil',
            'is_watermark' => false,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.hapus-pengurus', $pengurus->id));

        $response->assertRedirect(route('admin.kelola-profil'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('personel', ['id' => $pengurus->id]);
        $this->assertDatabaseMissing('galeri', ['id' => $foto->id]);
        Storage::disk('public')->assertMissing('personel/foto-pengurus.jpg');
    }

    public function test_psn_004_admin_menghapus_pelatih_beserta_foto_galeri()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['peran' => 'admin']);

        $pelatih = Personel::create([
            'nia' => 'L-301',
            'nama' => 'Pelatih Dihapus',
            'jabatan' => 'Pelatih',
            'no_handphone' => '081200000202',
            'peran' => 'pelatih',
            'status' => 'aktif',
        ]);

        Storage::disk('public')->put('personel/foto-pelatih.jpg', 'isi-gambar');

        $foto = Galeri::create([
            'sumber_id' => $pelatih->id,
            'path' => 'personel/foto-pelatih.jpg',
            'nama_file' => 'foto-pelatih.jpg',
            'kategori' => 'profil',
            'is_watermark' => false,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.hapus-pelatih', $pelatih->id));

        $response->assertRedirect(route('admin.kelola-profil'));

        $this->assertDatabaseMissing('personel', ['id' => $pelatih->id]);
        $this->assertDatabaseMissing('galeri', ['id' => $foto->id]);
        Storage::disk('public')->assertMissing('personel/foto-pelatih.jpg');
    }
}
