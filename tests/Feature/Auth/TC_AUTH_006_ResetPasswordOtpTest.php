<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TC-AUTH-006 : Reset password ditolak pada kondisi tidak sah —
 * form password baru tidak bisa dibuka tanpa verifikasi OTP.
 */
class TC_AUTH_006_ResetPasswordOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_006_form_password_baru_tidak_bisa_dibuka_tanpa_verifikasi_otp(): void
    {
        // ACT: langsung membuka halaman password baru tanpa lewat verifikasi OTP.
        $response = $this->get(route('otp.reset.form'));

        // ASSERT: dikembalikan ke halaman awal "Lupa Password".
        $response->assertRedirect(route('otp.forgot.form'));
    }
}
