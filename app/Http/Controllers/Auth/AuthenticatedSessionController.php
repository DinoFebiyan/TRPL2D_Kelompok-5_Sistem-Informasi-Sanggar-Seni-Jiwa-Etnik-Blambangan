<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Validasi wajib centang captcha
        $request->validate([
            'g-recaptcha-response' => ['required'],
        ], [
            'g-recaptcha-response.required' => 'Silakan centang reCAPTCHA terlebih dahulu.',
        ]);

        // Verifikasi ke server Google
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
            ]
        );

        $hasilCaptcha = $response->json();

        if (!($hasilCaptcha['success'] ?? false)) {
            return back()
                ->withErrors([
                    'g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal.'
                ])
                ->withInput();
        }

        $request->authenticate();

        $request->session()->regenerate();

        // Ambil data user yang login
        $user = Auth::user();

        if ($user->status !== 'aktif') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi Super Admin.'
            ]);
        }

        // SINKRONISASI COATING ROLE (Menggunakan route() murni untuk mematikan intended bypass)
        if ($user->peran === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->peran === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Jika peran tidak dikenali, logout paksa agar tidak menggantung di sistem
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