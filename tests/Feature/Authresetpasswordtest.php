<?php

namespace Tests\Feature;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * TC-AUTH-005 : Reset password lewat email sampai password baru tersimpan.
 *
 * Alurnya 3 langkah, masing-masing saya uji sebagai test sendiri:
 *   Langkah 1 -> minta OTP        (POST otp.send)
 *   Langkah 2 -> verifikasi OTP   (POST otp.verify)
 *   Langkah 3 -> simpan password  (POST otp.reset)
 */
class AuthResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    // ---------- LANGKAH 1: minta OTP ----------
    public function test_langkah1_minta_otp_membuat_kode_dan_mengirim_email(): void
    {
        // ARRANGE: satu user dengan email tertentu.
        $user = User::factory()->create(['email' => 'febri@jeb.test', 'peran' => 'admin']);

        // ACT: pengguna mengisi email di halaman "Lupa Password".
        $response = $this->post(route('otp.send'), ['email' => 'febri@jeb.test']);

        // ASSERT:
        $response->assertRedirect(route('otp.verify.form'));             // lanjut ke halaman isi OTP
        $this->assertDatabaseHas('otp', ['user_id' => $user->id]);       // OTP tersimpan di tabel "otp"
        // Saat test, email tidak benar-benar dikirim (MAIL_MAILER=array), hanya ditampung di memori.
        // Jadi kita cukup menghitung: harus ada 1 email.
        $this->assertCount(1, Mail::mailer()->getSymfonyTransport()->messages());
    }

    // ---------- LANGKAH 2: verifikasi OTP ----------
    public function test_langkah2_otp_benar_diterima_dan_dihapus(): void
    {
        // ARRANGE: buat user + OTP "123456" yang masih berlaku 5 menit lagi.
        $user = User::factory()->create(['email' => 'febri@jeb.test', 'peran' => 'admin']);
        Otp::create([
            'user_id' => $user->id,
            'kode' => '123456',
            'expires_at' => now()->addMinutes(5),
        ]);

        // ACT: withSession(...) meniru kondisi "email sudah tersimpan di sesi" (terjadi di langkah 1).
        // Lalu kirim kode OTP yang benar.
        $response = $this->withSession(['otp_email' => 'febri@jeb.test'])
            ->post(route('otp.verify'), ['otp' => '123456']);

        // ASSERT:
        $response->assertRedirect(route('otp.reset.form'));      // boleh lanjut ke form password baru
        $response->assertSessionHas('otp_verified', true);       // sesi ditandai "sudah terverifikasi"
        $this->assertDatabaseCount('otp', 0);                    // OTP sekali pakai -> dihapus
    }

    // ---------- LANGKAH 3: simpan password baru ----------
    public function test_langkah3_password_baru_tersimpan_dan_bisa_dipakai_login(): void
    {
        // ARRANGE: user dengan password lama "password" (bawaan factory) + reCAPTCHA palsu untuk login nanti.
        Http::fake(['https://www.google.com/*' => Http::response(['success' => true], 200)]);
        $user = User::factory()->create(['email' => 'febri@jeb.test', 'peran' => 'admin', 'status' => 'aktif']);

        // ACT: sesi sudah terverifikasi (hasil langkah 2), lalu kirim password baru + konfirmasinya.
        $response = $this->withSession(['otp_email' => 'febri@jeb.test', 'otp_verified' => true])
            ->post(route('otp.reset'), [
                'password' => 'PasswordBaru123',
                'password_confirmation' => 'PasswordBaru123',
            ]);

        // ASSERT 1: diarahkan ke halaman login.
        $response->assertRedirect(route('login'));

        // ASSERT 2: password di database berubah. Password disimpan ter-hash (diacak),
        // jadi tidak bisa dibandingkan langsung; kita pakai Hash::check.
        $user->refresh();                                              // ambil data terbaru dari database
        $this->assertTrue(Hash::check('PasswordBaru123', $user->password));   // password baru cocok
        $this->assertFalse(Hash::check('password', $user->password));         // password lama sudah tidak berlaku

        // ASSERT 3 (bukti akhir): login dengan password baru berhasil.
        $this->post('/login', [
            'email' => 'febri@jeb.test',
            'password' => 'PasswordBaru123',
            'g-recaptcha-response' => 'token-palsu',
        ])->assertRedirect('/admin/dashboard');
    }

    // ---------- Tambahan: tidak boleh melompati langkah ----------
    public function test_form_password_baru_tidak_bisa_dibuka_tanpa_verifikasi_otp(): void
    {
        // ACT: langsung membuka halaman password baru tanpa lewat verifikasi OTP.
        $response = $this->get(route('otp.reset.form'));

        // ASSERT: dikembalikan ke halaman awal "Lupa Password".
        $response->assertRedirect(route('otp.forgot.form'));
    }
}