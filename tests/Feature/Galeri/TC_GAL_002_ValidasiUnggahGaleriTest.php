<?php

namespace Tests\Feature\Galeri;

use App\Models\Galeri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TC_GAL_002_ValidasiUnggahGaleriTest extends TestCase
{
    use RefreshDatabase;

    public function test_gal_002_format_bukan_jpg_png_ditolak()
    {
        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $response = $this->actingAs($superadmin)->put(route('superadmin.update-profil'), [
            'deskripsi' => 'Deskripsi',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'sejarah' => 'Sejarah',
            'sambutan_pembina' => 'Sambutan',
            'header_files' => [UploadedFile::fake()->create('foto.pdf', 100, 'application/pdf')],
        ]);

        $response->assertSessionHasErrors('header_files.0');
        $this->assertDatabaseCount('galeri', 0);
    }

    public function test_gal_002_ukuran_foto_melebihi_batas_ditolak()
    {
        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $response = $this->actingAs($superadmin)->put(route('superadmin.update-profil'), [
            'deskripsi' => 'Deskripsi',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'sejarah' => 'Sejarah',
            'sambutan_pembina' => 'Sambutan',
            'header_files' => [UploadedFile::fake()->image('gede.jpg', 2000, 2000)->size(6000)],
        ]);

        $response->assertSessionHasErrors('header_files.0');
        $this->assertDatabaseCount('galeri', 0);
    }

    public function test_gal_002_foto_valid_lolos_validasi()
    {
        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $response = $this->actingAs($superadmin)->put(route('superadmin.update-profil'), [
            'deskripsi' => 'Deskripsi',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'sejarah' => 'Sejarah',
            'sambutan_pembina' => 'Sambutan',
            'header_files' => [UploadedFile::fake()->image('valid.jpg', 800, 600)],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('galeri', 1);
    }
}
