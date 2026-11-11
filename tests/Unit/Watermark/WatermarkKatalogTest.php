<?php

namespace Tests\Unit;

use App\Http\Controllers\KatalogController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use ReflectionMethod;
use Tests\Concerns\DataUji;
use Tests\TestCase;

class WatermarkKatalogTest extends TestCase
{
    use DataUji;

    protected function setUp(): void
    {
        parent::setUp();
        $this->siapkanFolderKatalog();
    }

    protected function tearDown(): void
    {
        $this->bersihkanFolderKatalog();
        parent::tearDown();
    }

    private function jalankanWatermark(UploadedFile $file): string
    {
        $controller = new KatalogController;
        $method = new ReflectionMethod($controller, 'simpanDenganWatermark');

        return $method->invoke($controller, $file);
    }

    public function test_wm_001_file_logo_watermark_tersedia(): void
    {
        $this->assertFileExists(public_path('img/watermark/logo-jeb.png'));
    }

    public function test_wm_001_foto_otomatis_berwatermark(): void
    {
        $file = UploadedFile::fake()->image('foto.jpg', 800, 600);
        $md5Asli = md5_file($file->getRealPath());

        $path = $this->jalankanWatermark($file);
        $hasil = storage_path('app/public/'.$path);

        $this->assertStringStartsWith('katalog/', $path);
        $this->assertFileExists($hasil);

        $this->assertNotSame($md5Asli, md5_file($hasil));

        [$lebar, $tinggi] = getimagesize($hasil);
        $this->assertSame(800, $lebar);
        $this->assertSame(600, $tinggi);
    }

    public function test_wm_001_nama_file_hasil_unik(): void
    {
        $path1 = $this->jalankanWatermark(UploadedFile::fake()->image('a.jpg', 800, 600));
        $path2 = $this->jalankanWatermark(UploadedFile::fake()->image('a.jpg', 800, 600));

        $this->assertNotSame($path1, $path2);
        $this->assertTrue(File::exists(storage_path('app/public/'.$path1)));
        $this->assertTrue(File::exists(storage_path('app/public/'.$path2)));
    }
}