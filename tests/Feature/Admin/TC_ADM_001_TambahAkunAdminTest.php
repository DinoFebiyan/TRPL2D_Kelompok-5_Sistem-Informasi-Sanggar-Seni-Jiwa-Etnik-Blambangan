<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TC_ADM_001_TambahAkunAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_adm_001_superadmin_dapat_menambahkan_admin()
    {
        // 1. Membuat akun Super Admin
        $superadmin = User::factory()->create([
            'peran' => 'superadmin',
        ]);

        // 2. Mensimulasikan Super Admin sudah login
        $this->actingAs($superadmin);

        // 3. Mengirim data Admin baru
        $response = $this->post('/superadmin/kelola-admin/store', [
            'name' => 'Admin Baru',
            'email' => 'adminbaru@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
        ]);

        // 4. Memastikan sistem mengarahkan kembali ke halaman Admin
        $response->assertRedirect(route('superadmin.kelola-admin'));

        // 5. Memastikan data Admin tersimpan di database
        $this->assertDatabaseHas('users', [
            'email' => 'adminbaru@gmail.com',
            'peran' => 'admin',
        ]);
    }
}
