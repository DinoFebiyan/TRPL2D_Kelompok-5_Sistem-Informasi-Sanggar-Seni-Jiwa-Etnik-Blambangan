<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Helpers\UploadHelper;
use App\Models\Galeri;
use App\Models\LogAktivitas;
use App\Models\Personel;
use App\Models\ProfilSanggar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $profil = ProfilSanggar::with(['logo', 'fotoPembina', 'headers'])->first();

        if (! $profil) {
            $profil = new ProfilSanggar;
        }

        $search = $request->input('search');
        $peranFilters = $request->input('peran', []); // Array peran ['pengurus', 'pelatih', 'anggota']

        $isFiltered = !empty($search) || !empty($peranFilters);

        if ($isFiltered) {
            $personelQuery = Personel::with(['galeri' => function ($q) {
                $q->where('kategori', 'profil');
            }]);

            // Filter berdasarkan pencarian nama, NIA, jabatan, dll.
            if (! empty($search)) {
                $personelQuery->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('nia', 'like', '%' . $search . '%')
                      ->orWhere('jabatan', 'like', '%' . $search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $search . '%')
                      ->orWhere('prestasi', 'like', '%' . $search . '%');
                });
            }

            // Filter berdasarkan kategori peran (Checkbox)
            if (! empty($peranFilters)) {
                $personelQuery->whereIn('peran', $peranFilters);
            }

            // Jika sedang melakukan pencarian/filter, gunakan pagination tunggal
            $personels = $personelQuery->latest()->paginate(10)->withQueryString();
            $pengurus = collect();
            $pelatih = collect();
            $anggota = collect();
        } else {
            // Tampilan normal: kembalikan paginate(10) per section seperti semula agar fungsi ->links() berjalan normal
            $personels = null;
            $pengurus = Personel::with(['galeri' => function ($q) {
                $q->where('kategori', 'profil');
            }])->where('peran', 'pengurus')->paginate(10);

            $pelatih = Personel::with(['galeri' => function ($q) {
                $q->where('kategori', 'profil');
            }])->where('peran', 'pelatih')->paginate(10);

            $anggota = Personel::with(['galeri' => function ($q) {
                $q->where('kategori', 'profil');
            }])->where('peran', 'anggota')->paginate(10);
        }

        $role = auth()->user()->peran;
        if ($role === 'superadmin') {
            return view('kelola.profil.indexSuperadmin', compact('profil', 'pengurus', 'pelatih', 'anggota', 'personels', 'isFiltered'));
        } elseif ($role === 'admin') {
            return view('kelola.profil.indexAdmin', compact('profil', 'pengurus', 'pelatih', 'anggota', 'personels', 'isFiltered'));
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    public function edit()
    {
        $profil = ProfilSanggar::with(['logo', 'fotoPembina', 'headers'])->first();

        if (! $profil) {
            $profil = new ProfilSanggar;
        }

        return view('kelola.profil.edit', compact('profil'));
    }

    public function update(Request $request)
    {
        $profil = ProfilSanggar::first();
        if (! $profil) {
            $profil = ProfilSanggar::create([
                'deskripsi' => '', 'visi' => '', 'misi' => '', 'sejarah' => '', 'sambutan_pembina' => '',
            ]);
        }

        $request->validate([
            'deskripsi' => 'required|string',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'sejarah' => 'required|string',
            'sambutan_pembina' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'foto_pembina' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'header_files' => 'nullable|array',
            'header_files.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $request->merge([
            'deskripsi' => SanitizeHelper::sanitize($request->deskripsi),
            'visi' => SanitizeHelper::sanitize($request->visi),
            'misi' => SanitizeHelper::sanitize($request->misi),
            'sejarah' => SanitizeHelper::sanitize($request->sejarah),
            'sambutan_pembina' => SanitizeHelper::sanitize($request->sambutan_pembina),
        ]);

        if ($request->hasFile('logo') && ! UploadHelper::isImageMimeValid($request->file('logo'))) {
            return back()->withErrors(['logo' => 'File logo bukan gambar yang valid.']);
        }
        if ($request->hasFile('foto_pembina') && ! UploadHelper::isImageMimeValid($request->file('foto_pembina'))) {
            return back()->withErrors(['foto_pembina' => 'File foto pembina bukan gambar yang valid.']);
        }
        if ($request->hasFile('header_files')) {
            foreach ($request->file('header_files') as $file) {
                if (! UploadHelper::isImageMimeValid($file)) {
                    return back()->withErrors(['header_files' => 'Salah satu file header bukan gambar yang valid.']);
                }
            }
        }

        if ($request->hasFile('logo')) {
            $fileLogo = $request->file('logo');
            $pathLogo = $fileLogo->store('galeri', 'public');
            if ($profil->logo_id) {
                $galeriLogoLama = Galeri::find($profil->logo_id);
                if ($galeriLogoLama) {
                    if ($galeriLogoLama->path) {
                        Storage::disk('public')->delete($galeriLogoLama->path);
                    }
                    $galeriLogoLama->update([
                        'path' => $pathLogo,
                        'nama_file' => $fileLogo->getClientOriginalName(),
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

        if ($request->hasFile('foto_pembina')) {
            $filePembina = $request->file('foto_pembina');
            $pathPembina = $filePembina->store('galeri', 'public');
            if ($profil->foto_pembina_id) {
                $galeriPembinaLama = Galeri::find($profil->foto_pembina_id);
                if ($galeriPembinaLama) {
                    if ($galeriPembinaLama->path) {
                        Storage::disk('public')->delete($galeriPembinaLama->path);
                    }
                    $galeriPembinaLama->update([
                        'path' => $pathPembina,
                        'nama_file' => $filePembina->getClientOriginalName(),
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

        if ($request->hasFile('header_files')) {
            foreach ($request->file('header_files') as $file) {
                $path = $file->store('header', 'public');
                Galeri::create([
                    'sumber_id' => $profil->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'header',
                    'is_watermark' => false,
                ]);
            }
        }

        $profil->update([
            'deskripsi' => $request->deskripsi,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'sejarah' => $request->sejarah,
            'sambutan_pembina' => $request->sambutan_pembina,
        ]);

        if (class_exists('App\Models\LogAktivitas')) {
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Memperbarui profil sanggar',
                'waktu' => now(),
            ]);
        }

        $role = auth()->user()->peran;
        $routeName = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';

        return redirect()->route($routeName)->with('success', 'Profil sanggar berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────
    //  PENGURUS (CRUD)
    // ─────────────────────────────────────────────────────────
    public function tambahPengurus()
    {
        return view('kelola.profil.tambah-pengurus');
    }

    public function storePengurus(Request $request)
    {
        $request->validate([
            'nia' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string',
            'instagram' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'masa_bakti' => 'nullable|string|max:255',
            'foto' => 'required|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ], [
            'nia.required' => 'Nomor Induk Anggota (NIA) wajib diisi.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'foto.required' => 'Foto pengurus belum dipilih.',
        ]);

        foreach ($request->file('foto', []) as $file) {
            if (! UploadHelper::isImageMimeValid($file)) {
                return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
            }
        }

        $personel = Personel::create([
            'nia' => $request->nia,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'instagram' => $request->instagram,
            'no_handphone' => $request->no_hp,
            'status' => $request->status,
            'peran' => 'pengurus',
            'deskripsi' => $request->deskripsi,
            'prestasi' => $request->prestasi,
            'masa_bakti' => $request->masa_bakti,
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('personel', 'public');
                Galeri::create([
                    'sumber_id' => $personel->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'profil',
                    'is_watermark' => false,
                ]);
            }
        }

        if (class_exists('App\Models\LogAktivitas')) {
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Menambahkan pengurus baru: '.$request->nama,
                'waktu' => now(),
            ]);
        }

        $role = auth()->user()->peran;
        $routeRedirect = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';
        return redirect()->route($routeRedirect)->with('success', 'Data pengurus baru berhasil disimpan.');
    }

    public function editPengurus(string $id)
    {
        $pengurus = Personel::with(['galeri' => function ($q) {
            $q->where('kategori', 'profil');
        }])->findOrFail($id);

        return view('kelola.profil.edit_pengurus', compact('pengurus'));
    }

    public function updatePengurus(Request $request, string $id)
    {
        $pengurus = Personel::findOrFail($id);

        $request->validate([
            'nia' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string',
            'instagram' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'masa_bakti' => 'nullable|string|max:255',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
            'hapus_foto' => 'nullable|array',
            'hapus_foto.*' => 'exists:galeri,id',
        ], [
            'nia.required' => 'Nomor Induk Anggota (NIA) wajib diisi.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
        ]);

        foreach ($request->file('foto', []) as $file) {
            if (! UploadHelper::isImageMimeValid($file)) {
                return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
            }
        }

        $pengurus->update([
            'nia' => $request->nia,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'instagram' => $request->instagram,
            'no_handphone' => $request->no_hp,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'prestasi' => $request->prestasi,
            'masa_bakti' => $request->masa_bakti,
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
                    'sumber_id' => $pengurus->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'profil',
                    'is_watermark' => false,
                ]);
            }
        }

        $role = auth()->user()->peran;
        $routeRedirect = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';
        return redirect()->route($routeRedirect)->with('success', 'Data profil pengurus berhasil diperbarui.');
    }

    public function hapusPengurus(string $id)
    {
        $pengurus = Personel::findOrFail($id);
        foreach ($pengurus->galeri as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
            $foto->delete();
        }
        $pengurus->delete();

        $role = auth()->user()->peran;
        $routeRedirect = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';
        return redirect()->route($routeRedirect)->with('success', 'Data pengurus berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────
    //  PELATIH (CRUD)
    // ─────────────────────────────────────────────────────────
    public function tambahPelatih()
    {
        return view('kelola.profil.tambah-pelatih');
    }

    public function storePelatih(Request $request)
    {
        $request->validate([
            'nia' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string',
            'instagram' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'masa_bakti' => 'nullable|string|max:255',
            'foto' => 'required|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ], [
            'nia.required' => 'Nomor Induk Anggota (NIA) wajib diisi.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'foto.required' => 'Foto pelatih belum dipilih.',
        ]);

        foreach ($request->file('foto', []) as $file) {
            if (! UploadHelper::isImageMimeValid($file)) {
                return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
            }
        }

        $personel = Personel::create([
            'nia' => $request->nia,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'instagram' => $request->instagram,
            'no_handphone' => $request->no_hp,
            'status' => $request->status,
            'peran' => 'pelatih',
            'deskripsi' => $request->deskripsi,
            'prestasi' => $request->prestasi,
            'masa_bakti' => $request->masa_bakti,
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('personel', 'public');
                Galeri::create([
                    'sumber_id' => $personel->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'profil',
                    'is_watermark' => false,
                ]);
            }
        }

        $role = auth()->user()->peran;
        $routeRedirect = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';
        return redirect()->route($routeRedirect)->with('success', 'Data pelatih baru berhasil disimpan.');
    }

    public function editPelatih(string $id)
    {
        $pelatih = Personel::with(['galeri' => function ($q) {
            $q->where('kategori', 'profil');
        }])->findOrFail($id);

        return view('kelola.profil.edit_pelatih', compact('pelatih'));
    }

    public function updatePelatih(Request $request, string $id)
    {
        $pelatih = Personel::findOrFail($id);

        $request->validate([
            'nia' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string',
            'instagram' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'masa_bakti' => 'nullable|string|max:255',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
            'hapus_foto' => 'nullable|array',
            'hapus_foto.*' => 'exists:galeri,id',
        ], [
            'nia.required' => 'Nomor Induk Anggota (NIA) wajib diisi.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
        ]);

        foreach ($request->file('foto', []) as $file) {
            if (! UploadHelper::isImageMimeValid($file)) {
                return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
            }
        }

        $pelatih->update([
            'nia' => $request->nia,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'instagram' => $request->instagram,
            'no_handphone' => $request->no_hp,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'prestasi' => $request->prestasi,
            'masa_bakti' => $request->masa_bakti,
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
                    'sumber_id' => $pelatih->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'profil',
                    'is_watermark' => false,
                ]);
            }
        }

        $role = auth()->user()->peran;
        $routeRedirect = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';
        return redirect()->route($routeRedirect)->with('success', 'Data profil pelatih berhasil diperbarui.');
    }

    public function hapusPelatih(string $id)
    {
        $pelatih = Personel::findOrFail($id);
        foreach ($pelatih->galeri as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
            $foto->delete();
        }
        $pelatih->delete();

        $role = auth()->user()->peran;
        $routeRedirect = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';
        return redirect()->route($routeRedirect)->with('success', 'Data pelatih berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────
    //  ANGGOTA (CRUD)
    // ─────────────────────────────────────────────────────────
    public function tambahAnggota()
    {
        return view('kelola.profil.tambah-anggota');
    }

    public function storeAnggota(Request $request)
    {
        $request->validate([
            'nia' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'masa_bakti' => 'nullable|string|max:255',
            'foto' => 'required|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ], [
            'nia.required' => 'Nomor Induk Anggota (NIA) wajib diisi.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'foto.required' => 'Foto anggota belum dipilih.',
        ]);

        foreach ($request->file('foto', []) as $file) {
            if (! UploadHelper::isImageMimeValid($file)) {
                return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
            }
        }

        $personel = Personel::create([
            'nia' => $request->nia,
            'nama' => $request->nama,
            'jabatan' => 'Anggota',
            'instagram' => $request->instagram,
            'no_handphone' => $request->no_hp,
            'status' => $request->status,
            'peran' => 'anggota',
            'deskripsi' => $request->deskripsi,
            'prestasi' => $request->prestasi,
            'masa_bakti' => $request->masa_bakti,
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('personel', 'public');
                Galeri::create([
                    'sumber_id' => $personel->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'profil',
                    'is_watermark' => false,
                ]);
            }
        }

        $role = auth()->user()->peran;
        $routeRedirect = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';
        return redirect()->route($routeRedirect)->with('success', 'Data anggota baru berhasil disimpan.');
    }

    public function editAnggota(string $id)
    {
        $anggota = Personel::with(['galeri' => function ($q) {
            $q->where('kategori', 'profil');
        }])->findOrFail($id);

        return view('kelola.profil.edit_anggota', compact('anggota'));
    }

    public function updateAnggota(Request $request, string $id)
    {
        $anggota = Personel::findOrFail($id);

        $request->validate([
            'nia' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
            'deskripsi' => 'nullable|string',
            'prestasi' => 'nullable|string',
            'masa_bakti' => 'nullable|string|max:255',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
            'hapus_foto' => 'nullable|array',
            'hapus_foto.*' => 'exists:galeri,id',
        ], [
            'nia.required' => 'Nomor Induk Anggota (NIA) wajib diisi.',
            'nama.required' => 'Nama lengkap wajib diisi.',
        ]);

        foreach ($request->file('foto', []) as $file) {
            if (! UploadHelper::isImageMimeValid($file)) {
                return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
            }
        }

        $anggota->update([
            'nia' => $request->nia,
            'nama' => $request->nama,
            'jabatan' => 'Anggota',
            'instagram' => $request->instagram,
            'no_handphone' => $request->no_hp,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            'prestasi' => $request->prestasi,
            'masa_bakti' => $request->masa_bakti,
        ]);

        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $fotoId) {
                $fotoLama = Galeri::where('sumber_id', $anggota->id)
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
                    'sumber_id' => $anggota->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'profil',
                    'is_watermark' => false,
                ]);
            }
        }

        $role = auth()->user()->peran;
        $routeRedirect = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';
        return redirect()->route($routeRedirect)->with('success', 'Data profil anggota berhasil diperbarui.');
    }

    public function hapusAnggota(string $id)
    {
        $anggota = Personel::findOrFail($id);
        foreach ($anggota->galeri as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
            $foto->delete();
        }
        $anggota->delete();

        $role = auth()->user()->peran;
        $routeRedirect = ($role === 'superadmin') ? 'superadmin.kelola-profil' : 'admin.kelola-profil';
        return redirect()->route($routeRedirect)->with('success', 'Data anggota berhasil dihapus.');
    }
}