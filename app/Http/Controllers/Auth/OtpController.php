<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpController extends Controller
{
    // ─────────────────────────────────────────────
    // STEP 1: Tampilkan form input email (lupa password)
    // ─────────────────────────────────────────────
    public function showForgotForm()
    {
        return view('auth.forgot_password');
    }

    // ─────────────────────────────────────────────
    // STEP 2: Proses email → buat & kirim OTP
    // ─────────────────────────────────────────────
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'Email tidak ditemukan dalam sistem kami.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Hapus OTP lama milik user ini
        Otp::where('user_id', $user->id)->delete();

        // Buat kode OTP 6 digit
        $kode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan ke database
        Otp::create([
            'user_id'    => $user->id,
            'kode'       => $kode,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Kirim email
        Mail::send('emails.otp', ['kode' => $kode, 'user' => $user], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode Verifikasi Reset Password – Sanggar Tari JEB');
        });

        // Simpan email di session untuk digunakan di step berikutnya
        session(['otp_email' => $user->email]);

        return redirect()->route('otp.verify.form')
                         ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    // ─────────────────────────────────────────────
    // STEP 3: Tampilkan form verifikasi OTP
    // ─────────────────────────────────────────────
    public function showVerifyForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('otp.forgot.form')
                             ->withErrors(['email' => 'Sesi tidak valid, silakan ulangi.']);
        }

        return view('auth.verifikasi_otp');
    }

    // ─────────────────────────────────────────────
    // STEP 4: Verifikasi kode OTP yang dimasukkan
    // ─────────────────────────────────────────────
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('otp.forgot.form')
                             ->withErrors(['otp' => 'Sesi tidak valid, silakan ulangi.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('otp.forgot.form')
                             ->withErrors(['otp' => 'User tidak ditemukan.']);
        }

        $otp = Otp::where('user_id', $user->id)
                  ->where('kode', $request->otp)
                  ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        if (Carbon::now()->greaterThan($otp->expires_at)) {
            $otp->delete();
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.']);
        }

        // OTP valid → tandai di session, hapus OTP dari DB
        session(['otp_verified' => true]);
        $otp->delete();

        return redirect()->route('otp.reset.form');
    }

    // ─────────────────────────────────────────────
    // STEP 5: Tampilkan form reset password baru
    // ─────────────────────────────────────────────
    public function showResetForm()
    {
        if (!session('otp_email') || !session('otp_verified')) {
            return redirect()->route('otp.forgot.form')
                             ->withErrors(['email' => 'Akses tidak sah. Silakan ulangi proses.']);
        }

        return view('auth.reset_password');
    }

    // ─────────────────────────────────────────────
    // STEP 6: Simpan password baru
    // ─────────────────────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]);

        $email = session('otp_email');
        if (!$email || !session('otp_verified')) {
            return redirect()->route('otp.forgot.form')
                             ->withErrors(['email' => 'Sesi tidak valid, silakan ulangi.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('otp.forgot.form')
                             ->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Bersihkan session OTP
        session()->forget(['otp_email', 'otp_verified']);

        return redirect()->route('login')
                         ->with('status', 'Password berhasil diubah! Silakan login.');
    }

    // ─────────────────────────────────────────────
    // BONUS: Kirim ulang OTP
    // ─────────────────────────────────────────────
    public function resendOtp()
    {
        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('otp.forgot.form')
                             ->withErrors(['email' => 'Sesi tidak valid.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('otp.forgot.form');
        }

        // Cek rate limit: jangan kirim ulang kalau OTP lama belum 1 menit
        $existingOtp = Otp::where('user_id', $user->id)->first();
        if ($existingOtp && Carbon::now()->lessThan($existingOtp->expires_at->subMinutes(9))) {
            return back()->withErrors(['otp' => 'Harap tunggu 1 menit sebelum meminta kode baru.']);
        }

        Otp::where('user_id', $user->id)->delete();

        $kode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Otp::create([
            'user_id'    => $user->id,
            'kode'       => $kode,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::send('emails.otp', ['kode' => $kode, 'user' => $user], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode Verifikasi Reset Password – Sanggar Tari JEB');
        });

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
