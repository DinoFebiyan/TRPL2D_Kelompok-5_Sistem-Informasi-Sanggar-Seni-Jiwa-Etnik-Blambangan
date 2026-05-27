<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\LogAktivitas;

class AdminController extends Controller
{
    /**
     * Menampilkan daftar admin
     */
    public function index(Request $request)
    {
        $query = User::where('peran', 'admin');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_handphone', 'like', "%{$search}%");
            });
        }
        
        $admins = $query->with('galeri')->latest()->paginate(10);
        $totalAdmin = User::where('peran', 'admin')->count();
        $activeAdmins = User::where('peran', 'admin')->where('status', 'aktif')->count();
        $inactiveAdmins = User::where('peran', 'admin')->where('status', 'nonaktif')->count();
        
        return view('superadmin.admin.index', compact('admins', 'totalAdmin', 'activeAdmins', 'inactiveAdmins'));
    }

    /**
     * Menampilkan form untuk menambah admin baru
     */
    public function create()
    {
        return view('superadmin.admin.create');
    }

    /**
     * Menyimpan data admin baru ke database
     */
    public function store(Request $request)
{
    $request->validate([
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'no_handphone' => 'nullable|string|max:12',
        'status' => 'required|in:aktif,nonaktif',
    ]);

    // Simpan user
$user = User::create([
    'name'        => $request->name,
    'email'       => $request->email,
    'password'    => Hash::make($request->password),
    'peran'       => 'admin',
    'no_handphone'=> $request->no_handphone,
    'status'      => $request->status,
]);

// Kalau ada foto, simpan ke Galeri dengan user_id
if ($request->hasFile('foto')) {
    $path = $request->file('foto')->store('admin_fotos', 'public');

    Galeri::create([
        'sumber_id'    => $user->id,
        'path'           => $path,
        'nama_file'      => uniqid().'_'.$request->file('foto')->getClientOriginalName(),
        'kategori'       => 'admin',
        'is_watermark'   => false,
    ]);
}

    LogAktivitas::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Menambahkan admin baru: ' . $request->name,
            'waktu'     => now(),
        ]);

    return redirect()->route('superadmin.kelola-admin')->with('success', 'Admin berhasil ditambahkan.');
    }


    /**
     * Menampilkan form untuk mengedit data admin
     */
    public function edit(string $id)
    {
        $admin = User::where('peran', 'admin')->findOrFail($id);
        return view('superadmin.admin.update', compact('admin'));
    }

    /**
     * Menyimpan pembaruan data admin ke database
     */
    public function update(Request $request, string $id)
{
    // Cari data admin berdasarkan ID 
    $admin = User::where('peran', 'admin')->findOrFail($id);

    // Validasi Input 
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'no_handphone' => 'required|string|max:20',
        'status' => 'required|in:aktif,nonaktif',
        'password' => 'nullable|min:8|confirmed', // Diubah ke min:8 agar sesuai petunjuk Blade
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto baru
    ]);

    $data = $request->only(['name', 'email', 'no_handphone', 'status']);
    
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }
    
    // Update data utama pada tabel users
    $admin->update($data);

    if ($request->hasFile('foto')) {
        // Simpan file fisik foto baru ke dalam folder storage/app/public/admin_fotos
        $path = $request->file('foto')->store('admin_fotos', 'public');
        $namaFileBaru = uniqid().'_'.$request->file('foto')->getClientOriginalName();

        // PERBAIKAN: Gunakan optional check agar tidak crash jika galeri bernilai null
        if ($admin->galeri) {
            // Hapus file foto lama dari disk storage
            if (Storage::disk('public')->exists($admin->galeri->path)) {
                Storage::disk('public')->delete($admin->galeri->path);
            }

            // Perbarui data path foto lama
            $admin->galeri->update([
                'path' => $path,
                'nama_file' => $namaFileBaru,
            ]);
        } else {
            // Jika sebelumnya admin tidak memiliki foto, buat data baru di tabel galeri
            Galeri::create([
                'sumber_id'    => $admin->id,
                'path'         => $path,
                'nama_file'    => $namaFileBaru,
                'kategori'     => 'admin', // Memastikan kategori terkunci sebagai admin
                'is_watermark' => false,
            ]);
        }
    }

    LogAktivitas::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Memperbarui data admin: ' . $admin->name,
            'waktu'     => now(),
        ]);

    //Kembali ke halaman utama kelola admin
    return redirect()->route('superadmin.kelola-admin')->with('success', 'Admin berhasil diperbarui.');
}

    /**
     * Menghapus data admin dari database
     */
    public function destroy(string $id)
    {
        $admin = User::where('peran', 'admin')->findOrFail($id);
        $admin->delete();
        
        LogAktivitas::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Menghapus admin: ' . $admin->name,
            'waktu'     => now(),
        ]);
        return redirect()->route('superadmin.kelola-admin')->with('success', 'Admin berhasil dihapus.');
    }
}