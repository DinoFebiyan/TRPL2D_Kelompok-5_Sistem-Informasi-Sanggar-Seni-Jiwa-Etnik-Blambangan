<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TC_ADM_002_ValidasiTambahAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_adm_002_email_admin_duplikat_ditolak()
    {
        // Membuat akun superadmin
        $superadmin = User::factory()->create([
            'name'  => 'Super Admin testing',
            'email' => 'superadmin@gmail.com',
            'peran' => 'superadmin',
        ]);

        // Membuat admin dengan email yang sudah terdaftar
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'peran' => 'admin',
        ]);

        $this->actingAs($superadmin); // Menggunakan actingAs untuk mensimulasikan login atau berperan sebagai superadmin

        // Mengirim data admin baru dengan email duplikat
        $response = $this->post('/superadmin/kelola-admin/store', [
            'name' => 'Admin Baru',
            'email' => 'admin@gmail.com',
            'password' => 'passworod123',
            'password_confirmation' => 'password123',
            'status' => 'aktif',
        ]);

        // Memastikan email ditolak dari variabel response dan memeriksa error pada session
        $response->assertSessionHasErrors('email');
    }
}
