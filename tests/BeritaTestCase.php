<?php

namespace Tests;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Base class untuk test modul Berita & AI Generator (Gemini).
 *
 * - Storage diisolasi ke folder sementara, sehingga foto hasil test
 *   TIDAK menyentuh storage/app/public milik project asli.
 * - Menyediakan helper: user, berita, foto JPEG (ukuran byte presisi), dll.
 */
abstract class BeritaTestCase extends TestCase
{
    use RefreshDatabase;

    protected string $storageSementara = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->storageSementara = sys_get_temp_dir().DIRECTORY_SEPARATOR.'jeb_test_'.uniqid();
        mkdir($this->storageSementara.'/app/public/berita', 0777, true);

        // BeritaController memakai storage_path(...) langsung dan disk 'public'
        $this->app->useStoragePath($this->storageSementara);
        config(['filesystems.disks.public.root' => $this->storageSementara.'/app/public']);
        Storage::forgetDisk('public');
    }

    protected function tearDown(): void
    {
        if ($this->storageSementara !== '') {
            File::deleteDirectory($this->storageSementara);
        }

        parent::tearDown();
    }

    // ------------------------------------------------------------------
    // User
    // ------------------------------------------------------------------

    protected function buatAdmin(array $atribut = []): User
    {
        return User::factory()->create(array_merge(['peran' => 'admin', 'status' => 'aktif'], $atribut));
    }

    protected function buatSuperadmin(array $atribut = []): User
    {
        return User::factory()->create(array_merge(['peran' => 'superadmin', 'status' => 'aktif'], $atribut));
    }

    /** Simulasi pengunjung publik (tanpa login). */
    protected function keluarSebagaiPengunjung(): void
    {
        Auth::guard('web')->logout();
    }

    // ------------------------------------------------------------------
    // Data berita & foto
    // ------------------------------------------------------------------

    /**
     * Buat berita langsung lewat model. $jumlahFoto > 0 membuat berkas fisik
     * di disk 'public' + record galeri (kategori 'berita').
     */
    protected function buatBerita(array $atribut = [], int $jumlahFoto = 0): Berita
    {
        $berita = Berita::create(array_merge([
            'judul' => 'Berita Uji',
            'isi_berita' => 'Isi berita uji.',
            'jenis_berita' => 'Pengumuman',
            'status' => 'tayang',
            'user_id' => $this->buatAdmin()->id,
        ], $atribut));

        for ($i = 1; $i <= $jumlahFoto; $i++) {
            $path = 'berita/uji_'.$berita->id.'_'.$i.'.jpg';
            Storage::disk('public')->put($path, $this->jpegBytes());

            Galeri::create([
                'sumber_id' => $berita->id,
                'path' => $path,
                'nama_file' => 'uji_'.$berita->id.'_'.$i.'.jpg',
                'kategori' => 'berita',
                'is_watermark' => true,
            ]);
        }

        return $berita;
    }

    /** Byte JPEG valid. Jika $ukuran > ukuran asli, dipadding agar PERSIS $ukuran byte. */
    protected function jpegBytes(int $ukuran = 0): string
    {
        $im = imagecreatetruecolor(320, 240);
        imagefill($im, 0, 0, imagecolorallocate($im, 200, 80, 60));
        ob_start();
        imagejpeg($im);
        $data = (string) ob_get_clean();
        imagedestroy($im);

        return $ukuran > strlen($data) ? $data.str_repeat("\0", $ukuran - strlen($data)) : $data;
    }

    /** Berkas upload palsu berisi JPEG asli, ukuran byte bisa ditentukan presisi. */
    protected function foto(string $nama = 'foto_berita.jpg', int $ukuranByte = 0): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($nama, $this->jpegBytes($ukuranByte));
    }

    protected function dataBerita(array $override = []): array
    {
        return array_merge([
            'judul' => 'Pentas Gandrung di Festival Banyuwangi',
            'isi' => 'Sanggar JEB tampil pada festival budaya.',
            'jenis_berita' => 'Pengumuman',
            'status' => 'tayang',
        ], $override);
    }

    /** Request mentah untuk memanggil method controller secara langsung (unit test). */
    protected function requestDengan(array $data, array $files = []): Request
    {
        return Request::create('/uji', 'POST', $data, [], $files);
    }

    /** Jalankan aksi; kembalikan array error validasi (kosong jika lolos). */
    protected function errorValidasi(callable $aksi): array
    {
        try {
            $aksi();
        } catch (ValidationException $e) {
            return $e->errors();
        }

        return [];
    }

    /** Body respons Gemini yang valid. */
    protected function bodyGemini(string $teks): array
    {
        return ['candidates' => [['content' => ['parts' => [['text' => $teks]]]]]];
    }
}
