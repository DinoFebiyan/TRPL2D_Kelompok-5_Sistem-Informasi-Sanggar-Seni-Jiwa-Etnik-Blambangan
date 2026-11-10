<?php

namespace Tests\Concerns;

use App\Models\Katalog;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

trait DataUji
{
    /** @var string[] */
    private array $berkasKatalogAwal = [];

    protected function siapkanFolderKatalog(): void
    {
        $dir = storage_path('app/public/katalog');
        File::ensureDirectoryExists($dir);
        $this->berkasKatalogAwal = array_map(
            fn ($f) => $f->getPathname(),
            File::files($dir)
        );
    }

    protected function bersihkanFolderKatalog(): void
    {
        $dir = storage_path('app/public/katalog');
        if (! File::isDirectory($dir)) {
            return;
        }
        foreach (File::files($dir) as $f) {
            if (! in_array($f->getPathname(), $this->berkasKatalogAwal, true)) {
                File::delete($f->getPathname());
            }
        }
    }

    protected function buatSuperadmin(): User
    {
        return User::factory()->create(['peran' => 'superadmin']);
    }

    protected function buatAdmin(): User
    {
        return User::factory()->create(['peran' => 'admin']);
    }

    protected function buatKatalog(array $override = []): Katalog
    {
        return Katalog::create(array_merge([
            'nama_katalog' => 'Kostum Gandrung',
            'kategori' => 'Kostum',
            'stok' => 5,
            'deskripsi' => 'Kostum tari Gandrung lengkap.',
            'status' => 'tersedia',
            'user_id' => $this->buatSuperadmin()->id,
        ], $override));
    }

    protected function dataKatalogValid(array $override = []): array
    {
        return array_merge([
            'nama_katalog' => 'Kostum Gandrung',
            'kategori' => 'Kostum',
            'stok' => 5,
            'deskripsi' => 'Kostum tari Gandrung lengkap.',
            'status' => 'tersedia',
            'foto' => [UploadedFile::fake()->image('kostum.jpg', 800, 600)],
        ], $override);
    }

    
    protected function assertDitolak($response): void
    {
        $this->assertContains(
            $response->getStatusCode(),
            [302, 401, 403],
            'Akses seharusnya ditolak, tetapi status = '.$response->getStatusCode()
        );
    }
}