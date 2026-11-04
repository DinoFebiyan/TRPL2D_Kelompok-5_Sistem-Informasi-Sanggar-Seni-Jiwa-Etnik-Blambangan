<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthValidasiEmailAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_admin_harus_unik()
    {
        // Membuat akun superadmin
        $superadmin = User::factory()->create([
            'name'  => 'Super Admin testing',
            'email' => 'superadmin@gmail.com',
            'peran' => 'superadmin',
        ]);
        dump($superadmin->toArray()); // Debugging: Menampilkan data superadmin yang dibuat

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
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'status' => 'aktif',
        ]);

        // Memastikan email ditolak dari variabel response dan memeriksa error pada session
        $response->assertSessionHasErrors('email');
        dump($response->getSession()->get('errors')); // Debugging: Menampilkan error email dari session
    }
}