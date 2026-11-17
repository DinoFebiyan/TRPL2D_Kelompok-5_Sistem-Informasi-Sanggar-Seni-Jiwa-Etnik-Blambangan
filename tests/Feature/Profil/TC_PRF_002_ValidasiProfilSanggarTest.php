<?php

namespace Tests\Feature\Profil;

use App\Models\ProfilSanggar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TC_PRF_002_ValidasiProfilSanggarTest extends TestCase
{
    use RefreshDatabase;

    public function test_prf_002_field_wajib_kosong_ditolak()
    {
        $admin = User::factory()->create(['peran' => 'admin']);

        $response = $this->actingAs($admin)->put(route('admin.update-profil'), [
            'deskripsi' => '',
            'visi' => '',
            'misi' => '',
            'sejarah' => '',
            'sambutan_pembina' => '',
        ]);

        $response->assertSessionHasErrors(['deskripsi', 'visi', 'misi', 'sejarah', 'sambutan_pembina']);
        $this->assertDatabaseCount('profil_sanggar', 1);
        $this->assertDatabaseHas('profil_sanggar', ['deskripsi' => '']);
    }

    public function test_prf_002_format_file_logo_tidak_valid_ditolak()
    {
        $admin = User::factory()->create(['peran' => 'admin']);

        $response = $this->actingAs($admin)->put(route('admin.update-profil'), [
            'deskripsi' => 'Deskripsi',
            'visi' => 'Visi',
            'misi' => 'Misi',
            'sejarah' => 'Sejarah',
            'sambutan_pembina' => 'Sambutan',
            'logo' => UploadedFile::fake()->create('logo.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('logo');
        $this->assertDatabaseCount('profil_sanggar', 1);
        $this->assertDatabaseHas('profil_sanggar', ['deskripsi' => '']);
    }

    public function test_prf_002_profil_belum_ada_dibuat_otomatis()
    {
        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $this->assertDatabaseCount('profil_sanggar', 0);

        $response = $this->actingAs($superadmin)->put(route('superadmin.update-profil'), [
            'deskripsi' => 'Deskripsi pertama',
            'visi' => 'Visi pertama',
            'misi' => 'Misi pertama',
            'sejarah' => 'Sejarah pertama',
            'sambutan_pembina' => 'Sambutan pertama',
        ]);

        $response->assertRedirect(route('superadmin.kelola-profil'));
        $this->assertDatabaseCount('profil_sanggar', 1);
        $this->assertDatabaseHas('profil_sanggar', [
            'deskripsi' => 'Deskripsi pertama',
            'sambutan_pembina' => 'Sambutan pertama',
        ]);
    }
}
