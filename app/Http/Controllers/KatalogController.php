<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Helpers\UploadHelper;
use App\Models\Galeri;
use App\Models\Katalog;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class KatalogController extends Controller
{
    private function simpanDenganWatermark($file)
    {
        $manager = new ImageManager(
            new Driver
        );

        $filename =
            time().'_'.
            uniqid().'.'.
            $file->getClientOriginalExtension();

        $image = $manager->read(
            $file->getRealPath()
        );

        $watermark = $manager->read(
            public_path(
                'img/watermark/logo-jeb.png'
            )
        );

        // Lebih besar untuk katalog
        $watermark->scale(
            width: 500
        );

        $image->place(
            $watermark,
            'center'
        );

        Storage::disk('public')->makeDirectory('katalog/watermark');

        $image->save(
            Storage::disk('public')->path('katalog/' . $filename)
        );

        return 'katalog/'.$filename;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Ambil data query pencarian & filter dari URL/Request
        $search = $request->input('search');
        $statuses = $request->input('status', []); // Array status
        $categories = $request->input('kategori', []); // Array kategori

        // 2. Buat Base Query dengan Eager Loading
        $query = Katalog::with(['galeri', 'user']);

        // 3. Tambahkan Logika Global Search (Mencari di Nama, Kategori, Deskripsi, dan Stok)
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_katalog', 'like', '%'.$search.'%')
                    ->orWhere('kategori', 'like', '%'.$search.'%')
                    ->orWhere('deskripsi', 'like', '%'.$search.'%')
                    ->orWhere('stok', 'like', '%'.$search.'%');
            });
        }

        // 4. Tambahkan Multi-Filter Status (Checkbox)
        if (! empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // 5. Tambahkan Multi-Filter Kategori (Checkbox)
        if (! empty($categories)) {
            $query->whereIn('kategori', $categories);
        }

        // 6. Eksekusi Pagination dengan mempertahankan Query Parameters di URL Links
        $katalogs = $query->latest()->paginate(10)->withQueryString();
        $katalogs->withPath(route('umum.katalog'));
        // 7. Hitung statistik card (Selalu ambil data total murni tanpa filter pencarian)
        $totalKatalog = Katalog::count();
        $tersedia = Katalog::where('status', 'tersedia')->count();
        $tidakTersedia = Katalog::where('status', 'tidak tersedia')->count();

        return view('kelola.katalog.indexSuperadmin', compact('katalogs', 'totalKatalog', 'tersedia', 'tidakTersedia'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kelola.katalog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. VALIDASI MULTI-UPLOAD: Memvalidasi 'foto' sebagai tumpukan array berkas
        $request->validate([
            'nama_katalog' => 'required|string|max:255',
            'kategori' => 'required|string',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'required|string',
            'status' => 'required|in:tersedia,tidak tersedia',
            'foto' => 'required|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

       $request->merge([
    'deskripsi' => SanitizeHelper::sanitize($request->deskripsi)
]);


        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if (! UploadHelper::isImageMimeValid($file)) {
                    return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
                }
            }
        }

        // 2. Simpan informasi teks produk utama ke database
        $katalog = Katalog::create([
            'nama_katalog' => $request->nama_katalog,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        // 3. LOGIKA MULTI-UPLOAD: Menyimpan tumpukan foto fisik produk ke dalam folder storage
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {

                // File biner asli disimpan ke: public/storage/katalog/
                $path = $this->simpanDenganWatermark($file);

                Galeri::create([
                    'sumber_id' => $katalog->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'katalog',
                    'is_watermark' => true,
                ]);
            }
        }

        // 4. Catat riwayat penambahan barang ke Log Aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menambahkan katalog produk baru: '.$request->nama_katalog,
            'waktu' => now(),
        ]);

        return redirect()->route('superadmin.kelola-katalog')->with('success', 'Katalog berhasil ditambahkan dengan multi-foto.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('superadmin.kelola-katalog')->with('error', 'Halaman detail tidak tersedia.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $katalog = Katalog::with('galeri')->findOrFail($id);

        return view('kelola.katalog.update', compact('katalog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $katalog = Katalog::findOrFail($id);

        $request->validate([
            'nama_katalog' => 'required|string|max:255',
            'kategori' => 'required|string',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'required|string',
            'status' => 'required|in:tersedia,tidak tersedia',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
            'hapus_foto' => 'nullable|array',
            'hapus_foto.*' => 'exists:galeri,id',
        ]);

        $request->merge(['deskripsi' => SanitizeHelper::sanitize($request->deskripsi)]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if (! UploadHelper::isImageMimeValid($file)) {
                    return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
                }
            }
        }
        // 1. Perbarui teks data utama katalog
        $katalog->update([
            'nama_katalog' => $request->nama_katalog,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        // 2. LOGIKA SELEKTIF HAPUS FOTO: Memproses pembuangan gambar lama yang ditandai pengguna
        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $fotoId) {
                $fotoLama = Galeri::where('sumber_id', $katalog->id)
                    ->where('kategori', 'katalog')
                    ->find($fotoId);

                if ($fotoLama) {
                    // Singkirkan berkas dari folder public/storage/katalog/
                    if (Storage::disk('public')->exists($fotoLama->path)) {
                        Storage::disk('public')->delete($fotoLama->path);
                    }
                    $fotoLama->delete();
                }
            }
        }

        // 3. Tambahkan beberapa foto produk baru jika ada berkas yang masuk
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $this->simpanDenganWatermark($file);

                Galeri::create([
                    'sumber_id' => $katalog->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'katalog',
                    'is_watermark' => true,
                ]);
            }
        }

        // 4. Catat riwayat perubahan ke tabel Log Audit Trail
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Memperbarui data katalog produk: '.$katalog->nama_katalog,
            'waktu' => now(),
        ]);

        return redirect()->route('superadmin.kelola-katalog')->with('success', 'Katalog berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $katalog = Katalog::with('galeri')->findOrFail($id);
        $namaKatalog = $katalog->nama_katalog;

        // 1. Sapu bersih seluruh berkas fisik gambar di folder lokal komputer sebelum baris record dihapus
        foreach ($katalog->galeri as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
            $foto->delete();
        }

        // 2. Singkirkan data record dari tabel katalog utama
        $katalog->delete();

        // 3. Rekam jejak penghapusan barang ke Log Aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus data katalog: '.$namaKatalog,
            'waktu' => now(),
        ]);

        return redirect()->route('superadmin.kelola-katalog')->with('success', 'Katalog beserta seluruh gambarnya berhasil dihapus.');
    }
}
