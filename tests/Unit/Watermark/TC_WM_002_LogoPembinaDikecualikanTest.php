<?php

namespace Tests\Unit\Watermark;

use App\Models\Galeri;
use App\Models\ProfilSanggar;
use App\Models\User;
use App\Helpers\UploadHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TC_WM_002_LogoPembinaDikecualikanTest extends TestCase
{
    use RefreshDatabase;

    private string $berkasSementara = '';

    protected function tearDown(): void
    {
        if ($this->berkasSementara !== '' && is_file($this->berkasSementara)) {
            unlink($this->berkasSementara);
        }
        parent::tearDown();
    }

    public function test_wm_002_logo_sanggar_tidak_diberi_watermark()
    {
        Storage::fake('public');

        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $this->actingAs($superadmin)->put(route('superadmin.update-profil'), [
            'deskripsi' => 'Deskripsi',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'sejarah' => 'Sejarah',
            'sambutan_pembina' => 'Sambutan',
            'logo' => UploadedFile::fake()->image('logo.jpg', 400, 400),
        ]);

        $profil = ProfilSanggar::first();
        $logo = Galeri::find($profil->logo_id);

        $this->assertNotNull($logo);
        $this->assertSame('logo', $logo->kategori);
        $this->assertFalse((bool) $logo->is_watermark, 'Logo sanggar harus dikecualikan dari watermark.');
    }

    public function test_wm_002_foto_pembina_tidak_diberi_watermark()
    {
        Storage::fake('public');

        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $this->actingAs($superadmin)->put(route('superadmin.update-profil'), [
            'deskripsi' => 'Deskripsi',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'sejarah' => 'Sejarah',
            'sambutan_pembina' => 'Sambutan',
            'foto_pembina' => UploadedFile::fake()->image('pembina.jpg', 600, 800),
        ]);

        $profil = ProfilSanggar::first();
        $pembina = Galeri::find($profil->foto_pembina_id);

        $this->assertNotNull($pembina);
        $this->assertSame('pembina', $pembina->kategori);
        $this->assertFalse((bool) $pembina->is_watermark, 'Foto pembina harus dikecualikan dari watermark.');
    }

    public function test_wm_002_fungsi_watermark_sendiri_tetap_berfungsi()
    {
        $asal = tempnam(sys_get_temp_dir(), 'wm_').'.png';
        $gambar = imagecreatetruecolor(300, 200);
        imagefill($gambar, 0, 0, imagecolorallocate($gambar, 255, 255, 255));
        imagepng($gambar, $asal);
        imagedestroy($gambar);

        $this->berkasSementara = $asal;
        $md5Awal = md5_file($asal);

        $this->assertTrue(UploadHelper::tambahWatermark($asal));
        $this->assertNotSame($md5Awal, md5_file($asal), 'Fungsi watermark harusnya mengubah gambar.');
    }
}
