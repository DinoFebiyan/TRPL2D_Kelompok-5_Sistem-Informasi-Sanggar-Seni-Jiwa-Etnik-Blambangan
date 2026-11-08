<?php

namespace App\Http\Controllers;

use App\Helpers\UploadHelper;
use App\Models\Galeri;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Menampilkan daftar admin
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statuses = $request->input('status', []); // Array status dari form view

        // Base Query khusus untuk peran admin
        $query = User::where('peran', 'admin');

        // 1. Logika Global Search
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_handphone', 'like', "%{$search}%");
            });
        }

        // 2. Logika Multi-Filter Checkbox Status
        if (! empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // Eksekusi pagination dengan mempertahankan parameter URL query string
        $admins = $query->with('galeri')->latest()->paginate(10)->withQueryString();
        $admins->withPath(url('admin'));
        // Statistik Card (Murni total data tanpa pengaruh filter pencarian)
        $totalAdmin = User::where('peran', 'admin')->count();
        $activeAdmins = User::where('peran', 'admin')->where('status', 'aktif')->count();
        $inactiveAdmins = User::where('peran', 'admin')->where('status', 'nonaktif')->count();

        return view('kelola.admin.indexSuperadmin', compact('admins', 'totalAdmin', 'activeAdmins', 'inactiveAdmins'));
    }

    /**
     * Menampilkan form untuk menambah admin baru
     */
    public function create()
    {
        return view('kelola.admin.create');
    }

    /**
     * Menyimpan data admin baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'no_handphone' => 'nullable|string|max:12',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($request->hasFile('foto') && ! UploadHelper::isImageMimeValid($request->file('foto'))) {
            return back()->withErrors(['foto' => 'File bukan gambar yang valid.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'peran' => 'admin',
            'no_handphone' => $request->no_handphone,
            'status' => $request->status,
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('admin', 'public');

            Galeri::create([
                'sumber_id' => $user->id,
                'path' => $path,
                'nama_file' => uniqid().'_'.$request->file('foto')->getClientOriginalName(),
                'kategori' => 'admin',
                'is_watermark' => false,
            ]);
        }

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menambahkan admin baru: '.$request->name,
            'waktu' => now(),
        ]);

        return redirect()->route('superadmin.kelola-admin')->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data admin
     */
    public function edit(string $id)
    {
        $admin = User::where('peran', 'admin')->findOrFail($id);

        return view('kelola.admin.update', compact('admin'));
    }

    /**
     * Menyimpan pembaruan data admin ke database
     */
    public function update(Request $request, string $id)
    {
        $admin = User::where('peran', 'admin')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'no_handphone' => 'required|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
            'password' => 'nullable|min:8|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($request->hasFile('foto') && ! UploadHelper::isImageMimeValid($request->file('foto'))) {
            return back()->withErrors(['foto' => 'File bukan gambar yang valid.']);
        }

        $data = $request->only(['name', 'email', 'no_handphone', 'status']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('admin', 'public');
            $namaFileBaru = basename($path); // REMEDIASI: Menggunakan nama acak yang aman dari path Laravel

            if ($admin->galeri) {
                if (Storage::disk('public')->exists($admin->galeri->path)) {
                    Storage::disk('public')->delete($admin->galeri->path);
                }
                $admin->galeri->update([
                    'path' => $path,
                    'nama_file' => $namaFileBaru,
                ]);
            } else {
                Galeri::create([
                    'sumber_id' => $admin->id,
                    'path' => $path,
                    'nama_file' => $namaFileBaru,
                    'kategori' => 'admin',
                    'is_watermark' => false,
                ]);
            }
        }

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Memperbarui data admin: '.$admin->name,
            'waktu' => now(),
        ]);

        return redirect()->route('superadmin.kelola-admin')->with('success', 'Admin berhasil diperbarui.');
    }

    /**
     * Menghapus data admin dari database
     */
    public function destroy(string $id)
    {
        $admin = User::where('peran', 'admin')->findOrFail($id);

        if ($admin->galeri) {
            if (Storage::disk('public')->exists($admin->galeri->path)) {
                Storage::disk('public')->delete($admin->galeri->path);
            }
            $admin->galeri->delete();
        }

        $admin->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menghapus admin: '.$admin->name,
            'waktu' => now(),
        ]);

        return redirect()->route('superadmin.kelola-admin')->with('success', 'Admin berhasil dihapus.');
    }
}
