<?php

namespace Tests\Feature\Profil;

use App\Models\ProfilSanggar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TC_PRF_001_MemperbaruiProfilSanggarTest extends TestCase
{
    use RefreshDatabase;

    public function test_prf_001_admin_memperbarui_profil_sanggar()
    {
        $admin = User::factory()->create(['peran' => 'admin']);

        ProfilSanggar::create([
            'deskripsi' => 'Deskripsi lama',
            'visi' => 'Visi lama',
            'misi' => 'Misi lama',
            'sejarah' => 'Sejarah lama',
            'sambutan_pembina' => 'Sambutan lama',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.update-profil'), [
            'deskripsi' => 'Sanggar Jiwa Etnik Blambangan melestarikan seni Gandrung.',
            'visi' => 'Menjadi sanggar seni rujukan Banyuwangi.',
            'misi' => 'Melatih generasi muda seni tradisional.',
            'sejarah' => 'Didirikan pada tahun 1970-an di Banyuwangi.',
            'sambutan_pembina' => 'Selamat datang di sanggar kami.',
        ]);

        $response->assertRedirect(route('admin.kelola-profil'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('profil_sanggar', [
            'deskripsi' => 'Sanggar Jiwa Etnik Blambangan melestarikan seni Gandrung.',
            'visi' => 'Menjadi sanggar seni rujukan Banyuwangi.',
            'misi' => 'Melatih generasi muda seni tradisional.',
            'sejarah' => 'Didirikan pada tahun 1970-an di Banyuwangi.',
            'sambutan_pembina' => 'Selamat datang di sanggar kami.',
        ]);

        $this->assertDatabaseCount('profil_sanggar', 1);

        $this->assertDatabaseHas('log_aktivitas', [
            'user_id' => $admin->id,
            'aktivitas' => 'Memperbarui profil sanggar',
        ]);
    }

    public function test_prf_001_superadmin_memperbarui_profil_sanggar()
    {
        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $response = $this->actingAs($superadmin)->put(route('superadmin.update-profil'), [
            'deskripsi' => 'Deskripsi baru',
            'visi' => 'Visi baru',
            'misi' => 'Misi baru',
            'sejarah' => 'Sejarah baru',
            'sambutan_pembina' => 'Sambutan baru',
        ]);

        $response->assertRedirect(route('superadmin.kelola-profil'));

        $this->assertDatabaseHas('profil_sanggar', [
            'deskripsi' => 'Deskripsi baru',
            'visi' => 'Visi baru',
        ]);
    }
}
