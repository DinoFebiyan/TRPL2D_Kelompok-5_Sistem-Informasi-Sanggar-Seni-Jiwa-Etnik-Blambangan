<?php

namespace Tests\Feature\Galeri;

use App\Models\Galeri;
use App\Models\ProfilSanggar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TC_GAL_001_UnggahFotoGaleriTest extends TestCase
{
    use RefreshDatabase;

    public function test_gal_001_foto_galeri_terunggah_dengan_metadata_lengkap()
    {
        Storage::fake('public');

        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $response = $this->actingAs($superadmin)->put(route('superadmin.update-profil'), [
            'deskripsi' => 'Deskripsi',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'sejarah' => 'Sejarah',
            'sambutan_pembina' => 'Sambutan',
            'header_files' => [UploadedFile::fake()->image('dokumentasi-pentas.jpg', 1200, 600)],
        ]);

        $response->assertRedirect(route('superadmin.kelola-profil'));

        $profil = ProfilSanggar::first();

        $galeri = Galeri::where('kategori', 'header')->where('sumber_id', $profil->id)->first();
        $this->assertNotNull($galeri);

        $this->assertStringStartsWith('header/', $galeri->path);
        $this->assertStringContainsString('dokumentasi-pentas.jpg', $galeri->nama_file);
        $this->assertTrue((bool) $galeri->is_watermark);

        Storage::disk('public')->assertExists($galeri->path);
    }
}
