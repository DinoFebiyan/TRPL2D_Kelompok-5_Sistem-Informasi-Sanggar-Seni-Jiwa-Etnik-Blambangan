<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Galeri;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return view('kelola.pengaturan.indexSuperadmin');
        } elseif ($role === 'admin') {
            return view('kelola.pengaturan.indexAdmin');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * Update data profil user yang sedang login (termasuk foto profil)
     */
    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        // Validasi input mengikuti struktur standar AdminController
        $request->validate([
            'foto_profil'  => 'nullable|image|mimes:jpeg,png,jpg|max:4096', // Validasi foto baru
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'no_handphone' => 'nullable|string|max:20',
        ]);

        // Mengambil input teks utama
        $data = $request->only(['name', 'email', 'no_handphone']);
        
        // Update data utama user pada tabel users
        $user->update($data);

        // Logika pengolahan file foto (Identik dengan AdminController)
        if ($request->hasFile('foto_profil')) {
            // Simpan file fisik foto baru ke disk public di dalam folder 'admin'
            $path = $request->file('foto_profil')->store('admin', 'public');
            $namaFileBaru = uniqid() . '_' . $request->file('foto_profil')->getClientOriginalName();

            // Cek apakah user sudah memiliki relasi foto di tabel galeri
            if ($user->galeri) {
                // Hapus berkas foto lama jika filenya ada di storage
                if (Storage::disk('public')->exists($user->galeri->path)) {
                    Storage::disk('public')->delete($user->galeri->path);
                }

                // Perbarui record path data lama di database
                $user->galeri->update([
                    'path'      => $path,
                    'nama_file' => $namaFileBaru,
                ]);
            } else {
                // Jika belum punya foto sama sekali, buat baris record baru di tabel galeri
                Galeri::create([
                    'sumber_id'    => $user->id,
                    'path'         => $path,
                    'nama_file'    => $namaFileBaru,
                    'kategori'     => 'admin', // Dikunci sebagai admin sesuai sistem galeri Anda
                    'is_watermark' => false,
                ]);
            }
        }

        // Mencatat log riwayat aktivitas sistem
        LogAktivitas::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Memperbarui profil akun sendiri: ' . $user->name,
            'waktu'     => now(),
        ]);

        return redirect()->route(auth()->user()->peran === 'superadmin' ? 'superadmin.pengaturan' : 'admin.pengaturan')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password kata sandi user yang sedang login
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Password saat ini salah.');
                    }
                }
            ],
            'password' => 'required|min:6|confirmed', // Minimal 6 karakter sesuai instruksi blade Anda
        ]);

        // Mengenkripsi password baru menggunakan Hash driver
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Mencatat log riwayat aktivitas sistem
        LogAktivitas::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Mengubah kata sandi akun sendiri: ' . $user->name,
            'waktu'     => now(),
        ]);

        return redirect()->route(auth()->user()->peran === 'superadmin' ? 'superadmin.pengaturan' : 'admin.pengaturan')->with('success', 'Password berhasil diubah.');
    }

    /**
     * Method kosong lainnya di bawah ini bawaan resource scaffolding
     */
    public function create() { }
    public function store(Request $request) { }
    public function show(string $id) { }
    public function edit(string $id) { }
    public function destroy(string $id) { }
}