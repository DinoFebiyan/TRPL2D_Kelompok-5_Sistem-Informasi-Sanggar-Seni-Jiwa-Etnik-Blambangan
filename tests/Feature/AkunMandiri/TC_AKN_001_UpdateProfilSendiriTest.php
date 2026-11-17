<?php

namespace Tests\Feature\AkunMandiri;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TC-AKN-001 : Pengguna dapat memperbarui data profil akun miliknya sendiri
 * (nama, email, no HP, foto profil) melalui menu Pengaturan Akun.
 */
class TC_AKN_001_UpdateProfilSendiriTest extends TestCase
{
    use RefreshDatabase;

    public function test_akn_001_halaman_profil_tampil(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile/edit');

        $response->assertOk();
    }

    public function test_akn_001_profil_sendiri_dapat_diperbarui(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile/edit', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'no_handphone' => '08123456789',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile/edit');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
    }

    public function test_akn_001_status_verifikasi_email_tetap_saat_email_tidak_berubah(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile/edit', [
                'name' => 'Test User',
                'email' => $user->email,
                'no_handphone' => $user->no_handphone,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile/edit');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_hapus_akun_sendiri_berhasil(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile/edit', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_hapus_akun_ditolak_jika_password_salah(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile/edit')
            ->delete('/profile/edit', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile/edit');

        $this->assertNotNull($user->fresh());
    }
}
