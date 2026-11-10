<?php

namespace Tests\Feature;

use App\Models\Galeri;
use App\Models\Personel;
use App\Models\ProfilSanggar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\DataUji;
use Tests\TestCase;


class ProfilPersonelTest extends TestCase
{
    use DataUji;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function dataProfilValid(array $override = []): array
    {
        return array_merge([
            'deskripsi' => 'Deskripsi sanggar.',
            'visi' => 'Visi sanggar.',
            'misi' => 'Misi sanggar.',
            'sejarah' => 'Sejarah sanggar.',
            'sambutan_pembina' => 'Sambutan pembina.',
        ], $override);
    }

    private function dataPersonelValid(array $override = []): array
    {
        return array_merge([
            'nama' => 'Sari Dewi',
            'jabatan' => 'Ketua',
            'instagram' => '@saridewi',
            'no_hp' => '081234567890',
            'status' => 'aktif',
            'foto' => [UploadedFile::fake()->image('personel.jpg', 600, 600)],
        ], $override);
    }

    public function test_prf_001_superadmin_memperbarui_profil_sanggar(): void
    {
        $response = $this->actingAs($this->buatSuperadmin())
            ->put(route('superadmin.update-profil'), $this->dataProfilValid(['visi' => 'Visi terbaru']));

        $response->assertRedirect(route('superadmin.kelola-profil'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas(ProfilSanggar::class, ['visi' => 'Visi terbaru']);
    }

    public function test_prf_004_logo_dan_foto_pembina_tanpa_watermark(): void
    {
        $data = $this->dataProfilValid([
            'logo' => UploadedFile::fake()->image('logo.png', 400, 400),
            'foto_pembina' => UploadedFile::fake()->image('pembina.jpg', 600, 800),
        ]);

        $this->actingAs($this->buatSuperadmin())
            ->put(route('superadmin.update-profil'), $data)
            ->assertRedirect(route('superadmin.kelola-profil'));

        $this->assertDatabaseHas(Galeri::class, ['kategori' => 'logo', 'is_watermark' => false]);
        $this->assertDatabaseHas(Galeri::class, ['kategori' => 'pembina', 'is_watermark' => false]);

        $logo = Galeri::where('kategori', 'logo')->first();
        Storage::disk('public')->assertExists($logo->path);
    }

    public function test_prf_005_foto_header_berwatermark(): void
    {
        $data = $this->dataProfilValid([
            'header_files' => [UploadedFile::fake()->image('header.jpg', 1200, 500)],
        ]);

        $this->actingAs($this->buatSuperadmin())
            ->put(route('superadmin.update-profil'), $data)
            ->assertRedirect(route('superadmin.kelola-profil'));

        $this->assertDatabaseHas(Galeri::class, ['kategori' => 'header', 'is_watermark' => true]);
    }

    public function test_psn_001_superadmin_menambah_pengurus(): void
    {
        $response = $this->actingAs($this->buatSuperadmin())
            ->post(route('superadmin.store-pengurus'), $this->dataPersonelValid());

        $response->assertRedirect(route('superadmin.kelola-profil'));

        $this->assertDatabaseHas(Personel::class, [
            'nama' => 'Sari Dewi',
            'jabatan' => 'Ketua',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
            'peran' => 'pengurus',
        ]);
        $this->assertDatabaseHas(Galeri::class, ['kategori' => 'profil', 'is_watermark' => false]);
    }

    public function test_psn_003_superadmin_mengedit_pengurus(): void
    {
        $pengurus = Personel::create([
            'nama' => 'Sari Dewi', 'jabatan' => 'Ketua', 'instagram' => '@sari',
            'no_handphone' => '0811', 'status' => 'aktif', 'peran' => 'pengurus',
        ]);

        $data = $this->dataPersonelValid(['nama' => 'Sari Dewi Revisi', 'jabatan' => 'Wakil']);
        unset($data['foto']); 

        $this->actingAs($this->buatSuperadmin())
            ->put(route('superadmin.update-pengurus', $pengurus->id), $data)
            ->assertRedirect(route('superadmin.kelola-profil'));

        $this->assertDatabaseHas(Personel::class, [
            'id' => $pengurus->id, 'nama' => 'Sari Dewi Revisi', 'jabatan' => 'Wakil',
        ]);
        $this->assertDatabaseMissing(Personel::class, ['id' => $pengurus->id, 'nama' => 'Sari Dewi']);
    }

    public function test_psn_004_superadmin_menghapus_pengurus(): void
    {
        $pengurus = Personel::create([
            'nama' => 'Sari Dewi', 'jabatan' => 'Ketua', 'instagram' => '@sari',
            'no_handphone' => '0811', 'status' => 'aktif', 'peran' => 'pengurus',
        ]);
        Storage::disk('public')->put('personel/foto.jpg', 'dummy');
        $galeri = Galeri::create([
            'sumber_id' => $pengurus->id, 'path' => 'personel/foto.jpg',
            'nama_file' => 'foto.jpg', 'kategori' => 'profil', 'is_watermark' => false,
        ]);

        $this->actingAs($this->buatSuperadmin())
            ->delete(route('superadmin.hapus-pengurus', $pengurus->id))
            ->assertRedirect(route('superadmin.kelola-profil'));

        $this->assertDatabaseMissing(Personel::class, ['id' => $pengurus->id]);
        $this->assertDatabaseMissing(Galeri::class, ['id' => $galeri->id]);
        Storage::disk('public')->assertMissing('personel/foto.jpg');
    }


    public function test_prf_003_profil_publik_hanya_menampilkan_personel_aktif(): void
    {
        ProfilSanggar::create($this->dataProfilValid(['sambutan_pembina' => 'Sambutan']));
        Personel::create([
            'nama' => 'Pengurus Aktif', 'jabatan' => 'Ketua', 'instagram' => '@a',
            'no_handphone' => '0811', 'status' => 'aktif', 'peran' => 'pengurus',
        ]);
        Personel::create([
            'nama' => 'Pengurus Nonaktif', 'jabatan' => 'Sekretaris', 'instagram' => '@b',
            'no_handphone' => '0822', 'status' => 'nonaktif', 'peran' => 'pengurus',
        ]);

        $response = $this->get(route('umum.profil'));

        $response->assertOk();
        $response->assertSee('Pengurus Aktif');
        $response->assertDontSee('Pengurus Nonaktif');
    }
}