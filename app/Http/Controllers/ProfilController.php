<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilSanggar;
use App\Models\Personel;
use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relasi 'headers' baru untuk mencegah N+1 Query Problem
        $profil = ProfilSanggar::with(['logo', 'fotoPembina', 'headers'])->first();

        if (!$profil) {
            $profil = new ProfilSanggar();
        }

        $pengurus = Personel::with('galeri')->where('peran', 'pengurus')->paginate(10);
        $pelatih = Personel::with('galeri')->where('peran', 'pelatih')->paginate(10);

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return view('kelola.profil.indexSuperadmin', compact('profil', 'pengurus', 'pelatih'));
        } elseif ($role === 'admin') {
            return view('kelola.profil.indexAdmin', compact('profil', 'pengurus', 'pelatih'));
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        // Memuat halaman edit beserta data multi-foto header terpilih
        $profil = ProfilSanggar::with(['logo', 'fotoPembina', 'headers'])->first();

        if (!$profil) {
            $profil = new ProfilSanggar();
        }

        return view('kelola.profil.edit', compact('profil'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $profil = ProfilSanggar::first();

        if (!$profil) {
            $profil = ProfilSanggar::create([
                'deskripsi' => '', 'visi' => '', 'misi' => '', 'sejarah' => '', 'sambutan_pembina' => ''
            ]);
        }

        // Jalankan Validasi Input
        $request->validate([
            'deskripsi'        => 'required|string',
            'visi'             => 'required|string',
            'misi'             => 'required|string',
            'sejarah'          => 'required|string',
            'sambutan_pembina' => 'required|string',
            'logo'             => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'foto_pembina'     => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'header_files'     => 'nullable|array',
            'header_files.*'   => 'image|mimes:jpeg,png,jpg|max:16384',
            'hapus_header'     => 'nullable|array',
            'hapus_header.*'   => 'exists:galeri,id',
        ]);

        // LOGIKA A: Bersihkan Foto Header Lama yang Ditandai Admin
        if ($request->filled('hapus_header')) {
            foreach ($request->hapus_header as $fotoId) {
                $fotoLama = Galeri::where('sumber_id', $profil->id)
                                  ->where('kategori', 'header')
                                  ->find($fotoId);

                if ($fotoLama) {
                    if (Storage::disk('public')->exists($fotoLama->path)) {
                        Storage::disk('public')->delete($fotoLama->path);
                    }
                    $fotoLama->delete();
                }
            }
        }

        // LOGIKA B: Upload Kumpulan Foto Header Baru ke Folder 'header'
        if ($request->hasFile('header_files')) {
            foreach ($request->file('header_files') as $file) {
                $path = $file->store('header', 'public');

                Galeri::create([
                    'sumber_id'    => $profil->id,
                    'path'         => $path,
                    'nama_file'    => uniqid() . '_' . $file->getClientOriginalName(),
                    'kategori'     => 'header',
                    'is_watermark' => false,
                ]);
            }
        }

        // Proses Simpan Gambar Single (Logo) -> Kategori 'logo'
        if ($request->hasFile('logo')) {
            $fileLogo = $request->file('logo');
            $pathLogo = $fileLogo->store('galeri', 'public');

            if ($profil->logo_id) {
                $galeriLogoLama = Galeri::find($profil->logo_id);
                if ($galeriLogoLama) {
                    if ($galeriLogoLama->path) { Storage::disk('public')->delete($galeriLogoLama->path); }
                    $galeriLogoLama->update([
                        'path' => $pathLogo, 
                        'nama_file' => $fileLogo->getClientOriginalName(),
                        'kategori' => 'logo'
                    ]);
                }
            } else {
                $galeriLogoBaru = Galeri::create([
                    'sumber_id' => $profil->id, 
                    'path' => $pathLogo, 
                    'nama_file' => $fileLogo->getClientOriginalName(), 
                    'kategori' => 'logo', 
                    'is_watermark' => false,
                ]);
                $profil->logo_id = $galeriLogoBaru->id;
            }
        }

        // Proses Simpan Gambar Single (Foto Pembina) -> Kategori 'pembina'
        if ($request->hasFile('foto_pembina')) {
            $filePembina = $request->file('foto_pembina');
            $pathPembina = $filePembina->store('galeri', 'public');

            if ($profil->foto_pembina_id) {
                $galeriPembinaLama = Galeri::find($profil->foto_pembina_id);
                if ($galeriPembinaLama) {
                    if ($galeriPembinaLama->path) { Storage::disk('public')->delete($galeriPembinaLama->path); }
                    $galeriPembinaLama->update([
                        'path' => $pathPembina, 
                        'nama_file' => $filePembina->getClientOriginalName(),
                        'kategori' => 'pembina'
                    ]);
                }
            } else {
                $galeriPembinaBaru = Galeri::create([
                    'sumber_id' => $profil->id, 
                    'path' => $pathPembina, 
                    'nama_file' => $filePembina->getClientOriginalName(), 
                    'kategori' => 'pembina', 
                    'is_watermark' => false,
                ]);
                $profil->foto_pembina_id = $galeriPembinaBaru->id;
            }
        }

        // Simpan Data Teks Umum Ke Database
        $profil->update([
            'deskripsi'        => $request->deskripsi,
            'visi'             => $request->visi,
            'misi'             => $request->misi,
            'sejarah'          => $request->sejarah,
            'sambutan_pembina' => $request->sambutan_pembina,
            'logo_id'          => $profil->logo_id,
            'foto_pembina_id'  => $profil->foto_pembina_id,
        ]);

        if (class_exists('App\Models\LogAktivitas')) {
            \App\Models\LogAktivitas::create([
                'user_id'   => auth()->id(),
                'aktivitas' => 'Memperbarui profil sanggar beserta tumpukan gambar header slideshow',
                'waktu'     => now(),
            ]);
        }

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
           return redirect()->route('superadmin.kelola-profil')->with('success', 'Profil sanggar dan data header baru berhasil diperbarui.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-profil')->with('success', 'Profil sanggar dan data header baru berhasil diperbarui.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Show form for adding new pengurus.
     */
    public function tambahPengurus()
    {
        return view('kelola.profil.tambah-pengurus');
    }

    /**
     * Show form for adding new pelatih.
     */
    public function tambahPelatih()
    {
        return view('kelola.profil.tambah-pelatih');
    }

    /**
     * Store a newly created pengurus in storage.
     */
    public function storePengurus(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'jabatan'   => 'required|string',
            'instagram' => 'nullable|string|max:255',
            'no_hp'     => 'required|string|max:20',
            'status'    => 'required|in:aktif,nonaktif',
            'foto'      => 'nullable|array',
            'foto.*'    => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $personel = Personel::create([
            'nama'         => $request->nama,
            'jabatan'      => $request->jabatan,
            'instagram'    => $request->instagram,
            'no_handphone' => $request->no_hp,
            'status'       => $request->status,
            'peran'        => 'pengurus',
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('personel', 'public');
                
                Galeri::create([
                    'sumber_id'    => $personel->id,
                    'path'         => $path,
                    'nama_file'    => uniqid() . '_' . $file->getClientOriginalName(),
                    'kategori'     => 'profil',
                    'is_watermark' => false,
                ]);
            }
        }

        if (class_exists('App\Models\LogAktivitas')) {
            \App\Models\LogAktivitas::create([
                'user_id'   => auth()->id(),
                'aktivitas' => 'Menambahkan pengurus baru: ' . $request->nama,
                'waktu'     => now(),
            ]);
        }

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.kelola-profil')->with('success', 'Data pengurus baru beserta galeri foto berhasil disimpan.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-profil')->with('success', 'Data pengurus baru beserta galeri foto berhasil disimpan.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * Show the form for editing pengurus.
     */
    public function editPengurus(string $id)
    {
        $pengurus = Personel::with('galeri')->findOrFail($id);
        return view('kelola.profil.edit_pengurus', compact('pengurus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePengurus(Request $request, string $id)
    {
        $pengurus = Personel::findOrFail($id);

        $request->validate([
            'nama'         => 'required|string|max:255',
            'jabatan'      => 'required|string',
            'instagram'    => 'nullable|string|max:255',
            'no_hp'        => 'required|string|max:20',
            'status'       => 'required|in:aktif,nonaktif',
            'foto'         => 'nullable|array',
            'foto.*'       => 'image|mimes:jpeg,png,jpg|max:4096',
            'hapus_foto'   => 'nullable|array',
            'hapus_foto.*' => 'exists:galeri,id',
        ]);

        $pengurus->update([
            'nama'         => $request->nama,
            'jabatan'      => $request->jabatan,
            'instagram'    => $request->instagram,
            'no_handphone' => $request->no_hp,
            'status'       => $request->status,
        ]);

        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $fotoId) {
                $fotoLama = Galeri::where('sumber_id', $pengurus->id)
                                  ->where('kategori', 'profil')
                                  ->find($fotoId);

                if ($fotoLama) {
                    if (Storage::disk('public')->exists($fotoLama->path)) {
                        Storage::disk('public')->delete($fotoLama->path);
                    }
                    $fotoLama->delete();
                }
            }
        }

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('personel', 'public');

                Galeri::create([
                    'sumber_id'    => $pengurus->id,
                    'path'         => $path,
                    'nama_file'    => uniqid() . '_' . $file->getClientOriginalName(),
                    'kategori'     => 'profil',
                    'is_watermark' => false,
                ]);
            }
        }

        if (class_exists('App\Models\LogAktivitas')) {
            \App\Models\LogAktivitas::create([
                'user_id'   => auth()->id(),
                'aktivitas' => 'Memperbarui data pengurus: ' . $pengurus->nama,
                'waktu'     => now(),
            ]);
        }

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.kelola-profil')->with('success', 'Data profil pengurus berhasil diperbarui.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-profil')->with('success', 'Data profil pengurus berhasil diperbarui.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * TAMBAHAN BARU: Menghapus data Pengurus beserta relasi file fotonya
     */
    public function hapusPengurus(string $id)
    {
        $pengurus = Personel::where('peran', 'pengurus')->findOrFail($id);

        // Ambil dan hapus semua file foto fisik di folder storage
        $fotoPersonel = Galeri::where('sumber_id', $pengurus->id)->where('kategori', 'profil')->get();
        foreach ($fotoPersonel as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
            $foto->delete();
        }

        $namaPengurus = $pengurus->nama;
        $pengurus->delete();

        if (class_exists('App\Models\LogAktivitas')) {
            \App\Models\LogAktivitas::create([
                'user_id'   => auth()->id(),
                'aktivitas' => 'Menghapus data pengurus: ' . $namaPengurus,
                'waktu'     => now(),
            ]);
        }

        $role = auth()->user()->peran;
        $route = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';

        return redirect()->route($route)->with('success', 'Data pengurus dan file foto terkait berhasil dihapus.');
    }

    /**
     * Store a newly created pelatih in storage.
     */
    public function storePelatih(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'jabatan'   => 'required|string',
            'instagram' => 'nullable|string|max:255',
            'no_hp'     => 'required|string|max:20',
            'status'    => 'required|in:aktif,nonaktif',
            'foto'      => 'nullable|array',
            'foto.*'    => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $personel = Personel::create([
            'nama'         => $request->nama,
            'jabatan'      => $request->jabatan,
            'instagram'    => $request->instagram,
            'no_handphone' => $request->no_hp,
            'status'       => $request->status,
            'peran'        => 'pelatih',
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('personel', 'public');
                
                Galeri::create([
                    'sumber_id'    => $personel->id,
                    'path'         => $path,
                    'nama_file'    => uniqid() . '_' . $file->getClientOriginalName(),
                    'kategori'     => 'profil',
                    'is_watermark' => false,
                ]);
            }
        }

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.kelola-profil')->with('success', 'Data pelatih baru berhasil disimpan.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-profil')->with('success', 'Data pelatih baru berhasil disimpan.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * Show the form for editing pelatih.
     */
    public function editPelatih(string $id)
    {
        $pelatih = Personel::with('galeri')->findOrFail($id);
        return view('kelola.profil.edit-pelatih', compact('pelatih'));
    }

    /**
     * Update pelatih in storage.
     */
    public function updatePelatih(Request $request, string $id)
    {
        $pelatih = Personel::findOrFail($id);

        $request->validate([
            'nama'         => 'required|string|max:255',
            'jabatan'      => 'required|string',
            'instagram'    => 'nullable|string|max:255',
            'no_hp'        => 'required|string|max:20',
            'status'       => 'required|in:aktif,nonaktif',
            'foto'         => 'nullable|array',
            'foto.*'       => 'image|mimes:jpeg,png,jpg|max:4096',
            'hapus_foto'   => 'nullable|array',
            'hapus_foto.*' => 'exists:galeri,id',
        ]);

        $pelatih->update([
            'nama'         => $request->nama,
            'jabatan'      => $request->jabatan,
            'instagram'    => $request->instagram,
            'no_handphone' => $request->no_hp,
            'status'       => $request->status,
        ]);

        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $fotoId) {
                $fotoLama = Galeri::where('sumber_id', $pelatih->id)
                                  ->where('kategori', 'profil')
                                  ->find($fotoId);
                                  
                if ($fotoLama) {
                    if (Storage::disk('public')->exists($fotoLama->path)) {
                        Storage::disk('public')->delete($fotoLama->path);
                    }
                    $fotoLama->delete();
                }
            }
        }

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('personel', 'public');

                Galeri::create([
                    'sumber_id'    => $pelatih->id,
                    'path'         => $path,
                    'nama_file'    => uniqid() . '_' . $file->getClientOriginalName(),
                    'kategori'     => 'profil',
                    'is_watermark' => false,
                ]);
            }
        }

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.kelola-profil')->with('success', 'Data profil pelatih berhasil diperbarui.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-profil')->with('success', 'Data profil pelatih berhasil diperbarui.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * TAMBAHAN BARU: Menghapus data Pelatih beserta relasi file fotonya
     */
    public function hapusPelatih(string $id)
    {
        $pelatih = Personel::where('peran', 'pelatih')->findOrFail($id);

        // Ambil dan hapus semua file foto fisik di folder storage
        $fotoPersonel = Galeri::where('sumber_id', $pelatih->id)->where('kategori', 'profil')->get();
        foreach ($fotoPersonel as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
            $foto->delete();
        }

        $namaPelatih = $pelatih->nama;
        $pelatih->delete();

        if (class_exists('App\Models\LogAktivitas')) {
            \App\Models\LogAktivitas::create([
                'user_id'   => auth()->id(),
                'aktivitas' => 'Menghapus data pelatih: ' . $namaPelatih,
                'waktu'     => now(),
            ]);
        }

        $role = auth()->user()->peran;
        $route = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';

        return redirect()->route($route)->with('success', 'Data pelatih dan file foto terkait berhasil dihapus.');
    }
}