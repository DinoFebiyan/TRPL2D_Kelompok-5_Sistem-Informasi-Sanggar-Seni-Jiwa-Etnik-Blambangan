<?php

namespace Tests\Feature\Profil;

use App\Models\Galeri;
use App\Models\ProfilSanggar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TC_PRF_005_HeaderSlideshowWatermarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_prf_005_foto_header_slideshow_tersimpan_berwatermark()
    {
        Storage::fake('public');

        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $response = $this->actingAs($superadmin)->put(route('superadmin.update-profil'), [
            'deskripsi' => 'Deskripsi',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'sejarah' => 'Sejarah',
            'sambutan_pembina' => 'Sambutan',
            'header_files' => [
                UploadedFile::fake()->image('header-1.jpg', 1200, 600),
                UploadedFile::fake()->image('header-2.jpg', 1200, 600),
            ],
        ]);

        $response->assertRedirect(route('superadmin.kelola-profil'));

        $profil = ProfilSanggar::first();
        $headers = Galeri::where('kategori', 'header')->where('sumber_id', $profil->id)->get();

        $this->assertCount(2, $headers);

        foreach ($headers as $header) {
            $this->assertTrue((bool) $header->is_watermark);
            $this->assertStringStartsWith('header/', $header->path);
            Storage::disk('public')->assertExists($header->path);
        }
    }
}
