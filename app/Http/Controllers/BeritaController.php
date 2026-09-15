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

    public function generateAiBerita(Request $request)
    {
        try {
            // 1. Validasi input dari front-end
            $request->validate([
                'judul'        => 'nullable|string|max:255',
                'jenis_berita' => 'nullable|string',
                'prompt'       => 'nullable|string',
            ]);

            $judul     = $request->input('judul', 'berita sanggar jeb');
            $jenis     = $request->input('jenis_berita', 'Umum');
            $promptUser = $request->input('prompt', 'Buatkan berita yang menarik dan informatif.');

            // 2. Ambil API Key dari config/services.php
            $apiKey = config('services.gemini.key');
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'GEMINI_API_KEY belum dikonfigurasi di file .env',
                ], 500);
            }

            // 3. Bangun instruksi tambahan jika user memberi prompt
            $instruksiTambahan = '';
            if (! empty($promptUser)) {
                $instruksiTambahan = "\n\nCatatan Perbaikan/Permintaan Tambahan dari Pengurus:\n\"{$promptUser}\"\nHarap sesuaikan narasi berita dengan memperhatikan permintaan tambahan di atas.";
            }

            // 4. Susun prompt final (mengikuti struktur dari Python)
            $promptFinal = <<<PROMPT
Kamu adalah seorang jurnalis senior untuk Sanggar Tari JEB. Buatkan draf artikel berita singkat yang menarik berdasarkan konteks berikut.

Konteks/Kategori Kegiatan: {$jenis}
Gunakan judul berikut sebagai judul utama: "{$judul}". Tuliskan judul ini dalam komentar HTML <!-- Judul Utama: ... -->.
{$instruksiTambahan}

Panduan Penulisan sesuai Kategori:
- Jika 'Latihan Rutin': fokus pada semangat penari, detail gerakan, dan proses latihan di sanggar.
- Jika 'Event / Pentas': fokus pada kemeriahan acara, kostum/pakaian adat, dan antusiasme penonton.
- Jika 'Prestasi': fokus pada rasa bangga, penghargaan, dan kerja keras tim.
- Jika 'Pengumuman': fokus pada informasi yang jelas, waktu, tempat, dan ajakan.

ATURAN FORMAT OUTPUT (SANGAT PENTING):
1. SELURUH penanda bagian (Paragraf Pembuka, Isi Berita, Penutup) HARUS DITULIS DALAM BENTUK KOMENTAR HTML (<!-- ... -->).
2. Teks paragraf berita ditulis menggunakan tag <p> HTML yang siap dimasukkan ke WYSIWYG Editor (TinyMCE).
3. JANGAN gunakan tag header <h3> untuk penanda bagian atau judul.

CONTOH STRUKTUR OUTPUT YANG DIHARAPKAN:
<!-- Paragraf Pembuka -->
<p>Isi paragraf pembuka di sini (5W+1H dan makna budaya)...</p>

<!-- Isi Berita -->
<p>Paragraf pertama isi berita...</p>
<p>Paragraf kedua isi berita...</p>

<!-- Penutup -->
<p>Paragraf penutup di sini...</p>
PROMPT;

            // 5. Siapkan payload untuk Gemini REST API
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $promptFinal],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature'     => 0.9,
                    'maxOutputTokens' => 2048,
                ],
            ];

            // 6. Daftar model yang akan dicoba (fallback jika model utama gagal)
            $modelList = [
                'gemini-3.7-flash',
                'gemini-3.6-flash',
                'gemini-3.5-flash',
                'gemini-2.5-flash',
            ];

            $hasilGenerasi = null;
            $errorTerakhir = null;

            foreach ($modelList as $model) {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

                $response = \Illuminate\Support\Facades\Http::timeout(60)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $hasilGenerasi = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                    if (! empty($hasilGenerasi)) {
                        break; // Berhasil, keluar dari loop
                    }
                } else {
                    $errorTerakhir = "Model {$model} error: " . $response->body();
                    \Illuminate\Support\Facades\Log::warning("Gemini {$model} gagal: " . $response->body());
                }
            }

            if (empty($hasilGenerasi)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal generate dari semua model Gemini. ' . ($errorTerakhir ?? ''),
                ], 500);
            }

            // 7. Bersihkan output dari ```html jika ada
            $cleanText = trim(str_replace(['```html', '```'], '', $hasilGenerasi));

            return response()->json([
                'success' => true,
                'content' => $cleanText,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Input tidak valid: ' . $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gemini Generate Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error Server: ' . $e->getMessage(),
            ], 500);
        }
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
