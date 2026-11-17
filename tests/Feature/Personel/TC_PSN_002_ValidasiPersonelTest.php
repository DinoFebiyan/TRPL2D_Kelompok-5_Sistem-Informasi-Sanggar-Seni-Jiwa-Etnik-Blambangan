<?php

namespace Tests\Feature\Personel;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TC_PSN_002_ValidasiPersonelTest extends TestCase
{
    use RefreshDatabase;

    public function test_psn_002_nama_kosong_ditolak()
    {
        $admin = User::factory()->create(['peran' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.store-pengurus'), [
            'nia' => 'P-102',
            'nama' => '',
            'jabatan' => 'Ketua Sanggar',
            'status' => 'aktif',
            'foto' => [UploadedFile::fake()->image('pengurus.jpg')],
        ]);

        $response->assertSessionHasErrors('nama');
    }

    public function test_psn_002_foto_tanpa_file_ditolak()
    {
        $admin = User::factory()->create(['peran' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.store-pengurus'), [
            'nia' => 'P-103',
            'nama' => 'Tanpa Foto',
            'jabatan' => 'Bendahara',
            'status' => 'aktif',
        ]);

        $response->assertSessionHasErrors('foto');
    }

    public function test_psn_002_foto_format_tidak_valid_ditolak()
    {
        $admin = User::factory()->create(['peran' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.store-pengurus'), [
            'nia' => 'P-104',
            'nama' => 'Format Salah',
            'jabatan' => 'Sekretaris',
            'no_hp' => '081234567891',
            'status' => 'aktif',
            'foto' => [UploadedFile::fake()->create('foto.pdf', 100, 'application/pdf')],
        ]);

        $response->assertSessionHasErrors('foto.0');
    }

    public function test_psn_002_foto_ukuran_melebihi_batas_ditolak()
    {
        $admin = User::factory()->create(['peran' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.store-pengurus'), [
            'nia' => 'P-105',
            'nama' => 'Ukuran Besar',
            'jabatan' => 'Sekretaris',
            'no_hp' => '081234567892',
            'status' => 'aktif',
            'foto' => [UploadedFile::fake()->image('gede.jpg', 2000, 2000)->size(6000)],
        ]);

        $response->assertSessionHasErrors('foto.0');
    }
}
