<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Helpers\UploadHelper;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BeritaController extends Controller
{
    // Fungsi untuk menyimpan gambar dengan watermark
    private function simpanDenganWatermark($file)
    {
        $manager = new ImageManager(new Driver);

        $filename =
            time().'_'.
            uniqid().'.'.
            $file->getClientOriginalExtension();

        // Baca gambar asli
        $image = $manager->read(
            $file->getRealPath()
        );

        // Baca watermark
        $watermark = $manager->read(
            public_path('img/watermark/logo-jeb.png')
        );

        // Resize watermark
        $watermark->scale(
            width: 300
        );

        // Tempel watermark di tengah
        $image->place(
            $watermark,
            'center'
        );

        Storage::disk('public')->makeDirectory('berita/watermark');


        // Simpan hasil watermark
        $image->save(
            storage_path(
                'app/public/berita/'.
                $filename
            )
        );

        return 'berita/'.$filename;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statuses = $request->input('status', []); // Menangkap array status dari filter

        $query = Berita::with(['user', 'galeri']);

        // 1. Global Search (Judul, Isi Berita, Nama Penulis)
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%'.$search.'%')
                    ->orWhere('isi_berita', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        // 2. Multi-Filter Status Checkbox
        if (! empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        $beritas = $query->latest()->paginate(10)->withQueryString();

        // Hitung total statistik riil data murni database
        $totalBerita = Berita::count();
        $ditayangkan = Berita::where('status', 'tayang')->count();
        $tidakDitayangkan = Berita::where('status', 'tidak ditayangkan')->count();

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return view('kelola.berita.indexSuperadmin', compact('beritas', 'totalBerita', 'ditayangkan', 'tidakDitayangkan'));
        } elseif ($role === 'admin') {
            return view('kelola.berita.indexAdmin', compact('beritas', 'totalBerita', 'ditayangkan', 'tidakDitayangkan'));
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kelola.berita.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'jenis_berita' => 'required|in:Pengumuman,Prestasi,Kolaborasi,Event,Lainnya',
            'status' => 'required|in:tayang,tidak ditayangkan',
            'foto' => 'required|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $request->merge(['isi' => SanitizeHelper::sanitize($request->isi)]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if (! UploadHelper::isImageMimeValid($file)) {
                    return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
                }
            }
        }

        $berita = Berita::create([
            'judul' => $request->judul,
            'isi_berita' => $request->isi,
            'jenis_berita' => $request->jenis_berita,
            'status' => $request->status,
            'user_id' => auth()->id(),
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $this->simpanDenganWatermark($file);

                Galeri::create([
                    'sumber_id' => $berita->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'berita',
                    'is_watermark' => true,
                ]);
            }
        }

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menambahkan berita baru: '.$request->judul,
            'waktu' => now(),
        ]);

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.kelola-berita')->with('success', 'Berita berhasil ditambahkan dengan multi-foto.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-berita')->with('success', 'Berita berhasil ditambahkan dengan multi-foto.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('superadmin.kelola-berita');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $berita = Berita::with('galeri')->findOrFail($id);

        return view('kelola.berita.update', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'jenis_berita' => 'required|in:Pengumuman,Prestasi,Kolaborasi,Event,Lainnya',
            'status' => 'required|in:tayang,tidak ditayangkan',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
            'hapus_foto' => 'nullable|array',
            'hapus_foto.*' => 'exists:galeri,id',
        ]);

        $request->merge(['isi' => SanitizeHelper::sanitize($request->isi)]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if (! UploadHelper::isImageMimeValid($file)) {
                    return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
                }
            }
        }

        $berita->update([
            'judul' => $request->judul,
            'isi_berita' => $request->isi,
            'jenis_berita' => $request->jenis_berita,
            'status' => $request->status,
        ]);

        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $fotoId) {
                $fotoLama = Galeri::where('sumber_id', $berita->id)
                    ->where('kategori', 'berita')
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
                $path = $this->simpanDenganWatermark($file);

                Galeri::create([
                    'sumber_id' => $berita->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'berita',
                    'is_watermark' => true,
                ]);
            }
        }

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Memperbarui data berita: '.$berita->judul,
            'waktu' => now(),
        ]);

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.kelola-berita')->with('success', 'Berita berhasil diperbarui.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-berita')->with('success', 'Berita berhasil diperbarui.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * Endpoint upload gambar dari TinyMCE editor.
     * Mengembalikan JSON { location: url } sesuai format TinyMCE.
     */
    public function uploadGambar(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $file = $request->file('file');

        // REMEDIASI: Menambahkan proteksi MIME riil tambahan sebelum memproses berkas TinyMCE
        if (! UploadHelper::isImageMimeValid($file)) {
            return response()->json(['error' => 'Berkas tidak valid.'], 400);
        }

        $path = $this->simpanDenganWatermark($file);

        return response()->json([
            'location' => Storage::disk('public')->url($path),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $berita = Berita::with('galeri')->findOrFail($id);
        $judulBerita = $berita->judul;

        foreach ($berita->galeri as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
            $foto->delete();
        }

        $berita->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menghapus berita: '.$judulBerita,
            'waktu' => now(),
        ]);

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.kelola-berita')->with('success', 'Berita beserta seluruh gambarnya berhasil dihapus.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-berita')->with('success', 'Berita beserta seluruh gambarnya berhasil dihapus.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }
}
