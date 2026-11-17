<?php

namespace Tests\Feature\Profil;

use App\Models\Galeri;
use App\Models\ProfilSanggar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TC_PRF_004_LogoPembinaTanpaWatermarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_prf_004_logo_dan_foto_pembina_tersimpan_tanpa_watermark()
    {
        Storage::fake('public');

        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $response = $this->actingAs($superadmin)->put(route('superadmin.update-profil'), [
            'deskripsi' => 'Deskripsi',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'sejarah' => 'Sejarah',
            'sambutan_pembina' => 'Sambutan',
            'logo' => UploadedFile::fake()->image('logo-sanggar.jpg', 300, 300),
            'foto_pembina' => UploadedFile::fake()->image('pembina.jpg', 600, 800),
        ]);

        $response->assertRedirect(route('superadmin.kelola-profil'));

        $profil = ProfilSanggar::first();
        $this->assertNotNull($profil->logo_id);
        $this->assertNotNull($profil->foto_pembina_id);

        $logo = Galeri::find($profil->logo_id);
        $pembina = Galeri::find($profil->foto_pembina_id);

        $this->assertSame('logo', $logo->kategori);
        $this->assertFalse((bool) $logo->is_watermark);
        $this->assertSame('pembina', $pembina->kategori);
        $this->assertFalse((bool) $pembina->is_watermark);

        Storage::disk('public')->assertExists($logo->path);
        Storage::disk('public')->assertExists($pembina->path);
    }
}
