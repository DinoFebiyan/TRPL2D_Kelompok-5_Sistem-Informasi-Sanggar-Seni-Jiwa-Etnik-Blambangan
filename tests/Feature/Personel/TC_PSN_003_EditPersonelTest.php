<?php

namespace Tests\Feature\Personel;

use App\Models\Personel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TC_PSN_003_EditPersonelTest extends TestCase
{
    use RefreshDatabase;

    public function test_psn_003_admin_mengedit_data_pengurus()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['peran' => 'admin']);

        $pengurus = Personel::create([
            'nia' => 'P-201',
            'nama' => 'Nama Lama',
            'jabatan' => 'Anggota',
            'no_handphone' => '081200000101',
            'peran' => 'pengurus',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.update-pengurus', $pengurus->id), [
            'nia' => 'P-201',
            'nama' => 'Nama Baru',
            'jabatan' => 'Ketua Sanggar',
            'no_hp' => '081234567890',
            'status' => 'nonaktif',
            'deskripsi' => 'Deskripsi diperbarui.',
            'prestasi' => 'Juara 2 Kabupaten 2024',
        ]);

        $response->assertRedirect(route('admin.kelola-profil'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('personel', [
            'id' => $pengurus->id,
            'nama' => 'Nama Baru',
            'jabatan' => 'Ketua Sanggar',
            'status' => 'nonaktif',
            'prestasi' => 'Juara 2 Kabupaten 2024',
        ]);
    }

    public function test_psn_003_admin_mengedit_pelatih_dengan_foto_baru()
    {
        Storage::fake('public');

        $admin = User::factory()->create(['peran' => 'admin']);

        $pelatih = Personel::create([
            'nia' => 'L-201',
            'nama' => 'Rina Pelatih',
            'jabatan' => 'Pelatih',
            'no_handphone' => '081200000102',
            'peran' => 'pelatih',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.update-pelatih', $pelatih->id), [
            'nia' => 'L-201',
            'nama' => 'Rina Pelatih Baru',
            'jabatan' => 'Pelatih Utama',
            'no_hp' => '081200000102',
            'status' => 'aktif',
            'foto' => [UploadedFile::fake()->image('foto-baru.jpg', 600, 800)],
        ]);

        $response->assertRedirect(route('admin.kelola-profil'));

        $this->assertDatabaseHas('personel', [
            'id' => $pelatih->id,
            'nama' => 'Rina Pelatih Baru',
            'jabatan' => 'Pelatih Utama',
        ]);

        $this->assertDatabaseHas('galeri', [
            'sumber_id' => $pelatih->id,
            'kategori' => 'profil',
        ]);
    }

    public function test_psn_003_edit_validasi_nama_wajib_diisi()
    {
        $admin = User::factory()->create(['peran' => 'admin']);

        $pengurus = Personel::create([
            'nia' => 'P-202',
            'nama' => 'Nama Lama',
            'jabatan' => 'Anggota',
            'no_handphone' => '081200000103',
            'peran' => 'pengurus',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.update-pengurus', $pengurus->id), [
            'nia' => 'P-202',
            'nama' => '',
            'jabatan' => 'Anggota',
            'status' => 'aktif',
        ]);

        $response->assertSessionHasErrors('nama');
    }
}
