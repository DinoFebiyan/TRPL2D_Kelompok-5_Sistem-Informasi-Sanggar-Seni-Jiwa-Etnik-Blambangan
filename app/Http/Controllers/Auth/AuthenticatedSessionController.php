<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Validasi reCAPTCHA
        $request->validate([
            'g-recaptcha-response' => ['required'],
        ], [
            'g-recaptcha-response.required' => 'Silakan centang reCAPTCHA terlebih dahulu.',
        ]);

        // 2. Verifikasi reCAPTCHA ke server Google
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
            ]
        );

        $hasilCaptcha = $response->json();

        if (! ($hasilCaptcha['success'] ?? false)) {
            return back()
                ->withErrors([
                    'g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal.',
                ])
                ->withInput();
        }

        // 3. Ambil kredensial email & password
        $credentials = $request->only('email', 'password');

        // 4. Coba Autentikasi Manual dengan Pesan Kustom Bahasa Indonesia
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password Anda salah.',
            ])->onlyInput('email');
        }

        // 5. Cek Status Akun setelah Autentikasi Berhasil
        $user = Auth::user();

        if ($user->status !== 'aktif') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi Super Admin.',
            ])->onlyInput('email');
        }

        // 6. Regenerasi Session
        $request->session()->regenerate();

        // 7. Pengalihan berdasarkan Peran (Role)
        if ($user->peran === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->peran === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Jika peran tidak dikenali, logout paksa
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('error', 'Peran anda tidak diketahui, mohon hubungi pihak terkait.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
