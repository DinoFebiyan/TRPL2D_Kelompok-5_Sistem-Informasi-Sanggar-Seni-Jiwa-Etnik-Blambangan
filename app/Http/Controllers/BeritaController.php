<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\LogAktivitas;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Berita::with('user', 'galeri');
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        $beritas = $query->latest()->paginate(10);
        $totalBerita = Berita::count();
        $ditayangkan = Berita::where('status', 'tayang')->count();
        $tidakDitayangkan = Berita::where('status', 'tidak ditayangkan')->count();
        
        return view('superadmin.berita.index', compact('beritas', 'totalBerita', 'ditayangkan', 'tidakDitayangkan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.berita.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. SINKRONISASI VALIDASI: Menggunakan 'foto' dan 'foto.*' sesuai file Blade Anda
        $request->validate([
            'judul'        => 'required|string|max:255',
            'isi'          => 'required|string',
            'jenis_berita' => 'required|in:Pengumuman,Prestasi,Kolaborasi,Event,Lainnya',
            'status'       => 'required|in:tayang,tidak ditayangkan',
            'foto'         => 'nullable|array', // Memastikan input berbentuk kumpulan data (array)
            'foto.*'       => 'image|mimes:jpeg,png,jpg|max:4096', // Validasi per file maks 4MB
        ]);

        // 2. Simpan data berita utama ke database
        $berita = Berita::create([
            'judul'        => $request->judul,
            'isi_berita'   => $request->isi,
            'jenis_berita' => $request->jenis_berita,
            'status'       => $request->status,
            'user_id'      => auth()->id(),
        ]);

        // 3. PROSES MULTIPLE UPLOAD: Memproses array file 'foto' satu per satu dengan foreach
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                
                // Simpan file fisik secara otomatis ke folder public/storage/berita
                $path = $file->store('berita', 'public');
                
                // Masukkan data record gambar ke tabel galeri satu per satu
                Galeri::create([
                    'sumber_id'    => $berita->id, 
                    'path'         => $path,       
                    'nama_file'    => uniqid() . '_' . $file->getClientOriginalName(), 
                    'kategori'     => 'berita',
                    'is_watermark' => false,
                ]);
            }
        }

        // 4. Catat Aktivitas ke Log Audit Trail
        LogAktivitas::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Menambahkan berita baru: ' . $request->judul,
            'waktu'     => now(),
        ]);

        return redirect()->route('superadmin.kelola-berita')->with('success', 'Berita berhasil ditambahkan dengan multi-foto.');
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
    public function edit(string $id)
    {
        // Ambil data berita beserta seluruh foto galeri terkait menggunakan Eager Loading
        $berita = Berita::with('galeri')->findOrFail($id);
        
        // Mengembalikan tampilan form edit berita
        return view('superadmin.berita.update', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul'        => 'required|string|max:255',
            'isi'          => 'required|string',
            'jenis_berita' => 'required|in:Pengumuman,Prestasi,Kolaborasi,Event,Lainnya',
            'status'       => 'required|in:tayang,tidak ditayangkan',
            'foto'         => 'nullable|array',
            'foto.*'       => 'image|mimes:jpeg,png,jpg|max:4096',
            'hapus_foto'   => 'nullable|array', // Validasi pelacak foto dihapus
            'hapus_foto.*' => 'exists:galeri,id',
        ]);

        // 1. Perbarui teks isi berita utama
        $berita->update([
            'judul'        => $request->judul,
            'isi_berita'   => $request->isi,
            'jenis_berita' => $request->jenis_berita,
            'status'       => $request->status,
        ]);

        // LOGIKA BARU: Proses penghapusan foto lama yang ditandai user
        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $fotoId) {
                // Cari foto di dalam tabel galeri yang terikat dengan berita ini
                $fotoLama = Galeri::where('sumber_id', $berita->id)
                                  ->where('kategori', 'berita')
                                  ->find($fotoId);

                if ($fotoLama) {
                    // Hapus file biner asli yang ada di folder storage/berita/
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($fotoLama->path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($fotoLama->path);
                    }
                    // Hapus record di database tabel galeri
                    $fotoLama->delete();
                }
            }
        }

        // 3. Jika user mengunggah beberapa foto tambahan baru, lakukan penyimpanan loop
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('berita', 'public');
                
                Galeri::create([
                    'sumber_id'    => $berita->id,
                    'path'         => $path,
                    'nama_file'    => uniqid() . '_' . $file->getClientOriginalName(),
                    'kategori'     => 'berita',
                    'is_watermark' => false,
                ]);
            }
        }

        // 4. Catat aktivitas pembaharuan data ke Log sistem
        LogAktivitas::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Memperbarui data berita: ' . $berita->judul,
            'waktu'     => now(),
        ]);

        return redirect()->route('superadmin.kelola-berita')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $berita = Berita::with('galeri')->findOrFail($id);
        $judulBerita = $berita->judul;

        // 1. Hapus semua file fisik gambar berita yang ada di storage/berita/
        foreach ($berita->galeri as $foto) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($foto->path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($foto->path);
            }
            // Hapus baris record data gambar di tabel galeri
            $foto->delete();
        }

        // 2. Hapus data utama berita dari database
        $berita->delete();

        // 3. Catat aktivitas penghapusan ke Log
        LogAktivitas::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Menghapus berita: ' . $judulBerita,
            'waktu'     => now(),
        ]);

        return redirect()->route('superadmin.kelola-berita')->with('success', 'Berita beserta seluruh gambarnya berhasil dihapus.');
    }
}
