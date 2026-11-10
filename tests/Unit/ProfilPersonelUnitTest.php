<?php

namespace Tests\Unit;

use App\Models\Galeri;
use App\Models\Personel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\DataUji;
use Tests\TestCase;


class ProfilPersonelUnitTest extends TestCase
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

    public static function profilTidakValid(): array
    {
        return [
            'deskripsi kosong' => [['deskripsi' => ''], 'deskripsi'],
            'visi kosong' => [['visi' => ''], 'visi'],
            'misi kosong' => [['misi' => ''], 'misi'],
            'sejarah kosong' => [['sejarah' => ''], 'sejarah'],
            'sambutan kosong' => [['sambutan_pembina' => ''], 'sambutan_pembina'],
        ];
    }

    #[DataProvider('profilTidakValid')]
    public function test_prf_002_validasi_profil_menolak_data_tidak_valid(array $override, string $fieldError): void
    {
        $response = $this->actingAs($this->buatSuperadmin())
            ->from(route('superadmin.edit-profil'))
            ->put(route('superadmin.update-profil'), $this->dataProfilValid($override));

        $response->assertSessionHasErrors($fieldError);
    }

    public function test_prf_002_logo_bukan_gambar_ditolak(): void
    {
        $data = $this->dataProfilValid([
            'logo' => UploadedFile::fake()->create('logo.pdf', 10, 'application/pdf'),
        ]);

        $this->actingAs($this->buatSuperadmin())
            ->from(route('superadmin.edit-profil'))
            ->put(route('superadmin.update-profil'), $data)
            ->assertSessionHasErrors('logo');
    }

    public static function personelTidakValid(): array
    {
        return [
            'nama kosong' => [['nama' => ''], 'nama'],
            'jabatan kosong' => [['jabatan' => ''], 'jabatan'],
            'status tidak dikenal' => [['status' => 'cuti'], 'status'],
            'foto tidak ada' => [['foto' => null], 'foto'],
        ];
    }

    #[DataProvider('personelTidakValid')]
    public function test_psn_002_validasi_personel_menolak_data_tidak_valid(array $override, string $fieldError): void
    {
        $data = $this->dataPersonelValid($override);
        if (array_key_exists('foto', $override) && $override['foto'] === null) {
            unset($data['foto']);
        }

        $this->actingAs($this->buatSuperadmin())
            ->from(route('superadmin.tambah-pengurus'))
            ->post(route('superadmin.store-pengurus'), $data)
            ->assertSessionHasErrors($fieldError);

        $this->assertSame(0, Personel::count());
    }


    public function test_wm_002_logo_dan_pembina_tidak_diberi_watermark(): void
    {
        $logo = UploadedFile::fake()->image('logo.png', 400, 400);
        $pembina = UploadedFile::fake()->image('pembina.jpg', 600, 800);
        $md5Logo = md5_file($logo->getRealPath());
        $md5Pembina = md5_file($pembina->getRealPath());

        $this->actingAs($this->buatSuperadmin())
            ->put(route('superadmin.update-profil'), $this->dataProfilValid([
                'logo' => $logo,
                'foto_pembina' => $pembina,
            ]));

        $galeriLogo = Galeri::where('kategori', 'logo')->first();
        $galeriPembina = Galeri::where('kategori', 'pembina')->first();

        $this->assertSame($md5Logo, md5(Storage::disk('public')->get($galeriLogo->path)));
        $this->assertSame($md5Pembina, md5(Storage::disk('public')->get($galeriPembina->path)));
    }
}