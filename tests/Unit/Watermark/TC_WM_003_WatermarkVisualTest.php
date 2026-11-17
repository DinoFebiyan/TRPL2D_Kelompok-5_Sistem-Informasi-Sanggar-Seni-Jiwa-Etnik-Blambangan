<?php

namespace Tests\Unit\Watermark;

use App\Helpers\UploadHelper;
use PHPUnit\Framework\TestCase;

class TC_WM_003_WatermarkVisualTest extends TestCase
{
    private string $asli = '';
    private string $berwatermark = '';

    protected function tearDown(): void
    {
        foreach ([$this->asli, $this->berwatermark] as $berkas) {
            if ($berkas !== '' && is_file($berkas)) {
                unlink($berkas);
            }
        }
        parent::tearDown();
    }

    private function siapkanGambar(int $lebar = 400, int $tinggi = 300): void
    {
        $gambar = imagecreatetruecolor($lebar, $tinggi);
        imagefill($gambar, 0, 0, imagecolorallocate($gambar, 255, 255, 255));

        $this->asli = tempnam(sys_get_temp_dir(), 'asli_').'.png';
        $this->berwatermark = tempnam(sys_get_temp_dir(), 'wm_').'.png';

        imagepng($gambar, $this->asli);
        imagedestroy($gambar);
        copy($this->asli, $this->berwatermark);

        $this->assertTrue(UploadHelper::tambahWatermark($this->berwatermark));
    }

    public function test_wm_003_area_sudut_kanan_bawah_berubah(): void
    {
        $this->siapkanGambar();

        $asli = imagecreatefrompng($this->asli);
        $hasil = imagecreatefrompng($this->berwatermark);

        [$lebar, $tinggi] = getimagesize($this->asli);

        $berbeda = 0;
        for ($x = $lebar - 180; $x < $lebar; $x += 5) {
            for ($y = $tinggi - 30; $y < $tinggi; $y += 5) {
                if (imagecolorat($asli, $x, $y) !== imagecolorat($hasil, $x, $y)) {
                    $berbeda++;
                }
            }
        }

        imagedestroy($asli);
        imagedestroy($hasil);

        $this->assertGreaterThan(
            0,
            $berbeda,
            'Watermark teks harus terlihat (mengubah piksel) di area kanan bawah foto.'
        );
    }

    public function test_wm_003_area_tengah_kiri_tetap_utuh(): void
    {
        $this->siapkanGambar();

        $asli = imagecreatefrompng($this->asli);
        $hasil = imagecreatefrompng($this->berwatermark);

        $sama = 0;
        for ($x = 0; $x < 150; $x += 5) {
            for ($y = 0; $y < 150; $y += 5) {
                if (imagecolorat($asli, $x, $y) === imagecolorat($hasil, $x, $y)) {
                    $sama++;
                }
            }
        }

        imagedestroy($asli);
        imagedestroy($hasil);

        $this->assertSame(900, $sama, 'Area di luar watermark tidak boleh berubah (PNG lossless).');
    }

    public function test_wm_003_dimensi_gambar_tidak_berubah(): void
    {
        $this->siapkanGambar(640, 480);

        [$lebar, $tinggi] = getimagesize($this->berwatermark);

        $this->assertSame(640, $lebar);
        $this->assertSame(480, $tinggi);
    }
}
