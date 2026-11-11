<?php

// Namespace = "alamat" file ini. Untuk folder tests/Feature selalu Tests\Feature.
namespace Tests\Feature;

use App\Models\User;                                   // model tabel users
use Illuminate\Foundation\Testing\RefreshDatabase;     // database dikosongkan ulang tiap test
use Illuminate\Support\Facades\Http;                   // untuk memalsukan panggilan ke Google reCAPTCHA
use Tests\TestCase;                                    // kelas dasar test Laravel

/**
 * TC-AUTH-002 : Login gagal
 *   - password salah
 *   - email tidak terdaftar
 *   - field kosong
 *   (tambahan) akun nonaktif tidak boleh login
 *
 * Jenis: Feature test (menguji lewat HTTP, seperti pengguna menekan tombol Login).
 */
class AuthLoginGagalTest extends TestCase
{
    // Setiap test mulai dengan database kosong, jadi data test satu tidak mengganggu test lain.
    use RefreshDatabase;

    // ---------- Fungsi bantu (supaya tidak menulis ulang berkali-kali) ----------

    // Login memanggil server Google (reCAPTCHA). Di test kita ganti dengan jawaban palsu "sukses",
    // supaya test tidak butuh internet dan hasilnya selalu sama.
    private function pakaiRecaptchaPalsu(): void
    {
        Http::fake(['https://www.google.com/*' => Http::response(['success' => true], 200)]);
    }

    // Mengirim form login: sama seperti mengisi email + password lalu klik tombol Login.
    private function kirimLogin(string $email, string $password)
    {
        return $this->post('/login', [
            'email' => $email,
            'password' => $password,
            'g-recaptcha-response' => 'token-palsu',   // anggap kotak "I'm not a robot" sudah dicentang
        ]);
    }

    // ---------- TEST 1: password salah ----------
    public function test_login_gagal_jika_password_salah(): void
    {
        // ARRANGE (siapkan): reCAPTCHA palsu + satu user aktif. Password asli user = "password".
        $this->pakaiRecaptchaPalsu();
        $user = User::factory()->create(['peran' => 'admin', 'status' => 'aktif']);

        // ACT (jalankan): login dengan password yang SALAH.
        $response = $this->kirimLogin($user->email, 'password-yang-salah');

        // ASSERT (periksa):
        $this->assertGuest();                          // pengguna tetap "tamu" = belum login
        $response->assertSessionHasErrors('email');    // muncul pesan error pada field email
    }

    // ---------- TEST 2: email tidak terdaftar ----------
    public function test_login_gagal_jika_email_tidak_terdaftar(): void
    {
        // ARRANGE: tidak ada user sama sekali di database.
        $this->pakaiRecaptchaPalsu();

        // ACT: login memakai email yang tidak pernah didaftarkan.
        $response = $this->kirimLogin('tidak.ada@jeb.test', 'apa-saja-123');

        // ASSERT: tetap tamu dan ada error.
        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    // ---------- TEST 3: field kosong ----------
    public function test_login_gagal_jika_email_dan_password_kosong(): void
    {
        // ARRANGE
        $this->pakaiRecaptchaPalsu();

        // ACT: kedua kolom dikirim kosong.
        $response = $this->kirimLogin('', '');

        // ASSERT: tetap tamu; KEDUA field harus memunculkan error (aturan "required").
        $this->assertGuest();
        $response->assertSessionHasErrors(['email', 'password']);
    }

    // ---------- TEST 4 (tambahan): akun nonaktif ----------
    public function test_akun_nonaktif_tidak_bisa_login(): void
    {
        // ARRANGE: user dengan status "nonaktif" (misalnya sudah dinonaktifkan Superadmin).
        $this->pakaiRecaptchaPalsu();
        $user = User::factory()->create(['peran' => 'admin', 'status' => 'nonaktif']);

        // ACT: password BENAR, tetapi akunnya nonaktif.
        $response = $this->kirimLogin($user->email, 'password');

        // ASSERT: ditolak dengan pesan khusus dari controller.
        $this->assertGuest();
        $response->assertSessionHasErrors([
            'email' => 'Akun Anda telah dinonaktifkan. Hubungi Super Admin.',
        ]);
    }
}