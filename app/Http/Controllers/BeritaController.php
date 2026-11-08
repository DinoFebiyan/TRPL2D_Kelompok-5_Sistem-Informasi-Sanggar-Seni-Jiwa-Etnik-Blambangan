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

        Storage::disk('public')->makeDirectory('berita');


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
        $beritas->withPath(url('berita'));
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
    set_time_limit(180);
    try {
        // 1. Validasi input
        $request->validate([
            'judul'        => 'nullable|string|max:255',
            'jenis_berita' => 'nullable|string',
            'prompt'       => 'nullable|string',
            'foto.*'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $judul      = trim($request->input('judul', ''));
        $jenis      = $request->input('jenis_berita') ?: 'Event / Pentas';
        $promptUser = trim($request->input('prompt', ''));
        $beritaTerdahulu = Berita::where('status', 'tayang')
            ->latest()
            ->take(5)
            ->get(['judul', 'isi_berita']);

        $referensiKonteks = "";
        if ($beritaTerdahulu->isNotEmpty()) {
            $referensiKonteks .= "\n--- REFERENSI BERITA & GAYA PENULISAN UTUH DARI WEBSITE SAYA ---\n";
            foreach ($beritaTerdahulu as $index => $b) {
                // Ambil isi berita utuh (hanya dibersihkan dari tag HTML agar format prompt rapi)
                $isiUtuh = strip_tags($b->isi_berita);
                
                $referensiKonteks .= "Contoh " . ($index + 1) . ":\n";
                $referensiKonteks .= "Judul: {$b->judul}\n";
                $referensiKonteks .= "Isi Lengkap:\n{$isiUtuh}\n\n";
            }
            $referensiKonteks .= "Pelajari gaya penulisan, alur cerita, pemilihan kata, dan struktur khas dari contoh-contoh berita utuh di atas agar berita baru ini terpersonalisasi sesuai karakter Sanggar Tari.\n";
        }

        // 2. Ambil API Key
        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'GEMINI_API_KEY belum dikonfigurasi di file .env',
            ], 500);
        }

        // 3. Susun Logika Judul & Prompt Tambahan
        if (!empty($judul)) {
            $instruksiJudul = "Gunakan judul berikut sebagai judul utama: '{$judul}'. Tuliskan judul ini dalam komentar HTML <!-- Judul Utama: {$judul} -->.";
        } else {
            $instruksiJudul = "Berikan 3 rekomendasi judul berita yang menarik. Tuliskan ketiga rekomendasi judul ini DALAM KOMENTAR HTML <!-- Rekomendasi Judul:\n1. ...\n2. ...\n3. ...\n-->.";
        }

        $instruksiTambahan = "";
        if (!empty($promptUser)) {
            $instruksiTambahan = "\nCatatan Perbaikan/Permintaan Tambahan dari Pengurus:\n\"{$promptUser}\"\nHarap sesuaikan narasi berita dengan memperhatikan permintaan tambahan di atas.";
        }

        // 4. Susun Prompt Final (Diperbarui untuk Tipografi TinyMCE & Output Judul)
$promptFinal = <<<PROMPT
Kamu adalah seorang jurnalis senior untuk Sanggar Tari JEB. Analisis gambar yang diberikan (jika ada), lalu buatkan draf artikel berita yang menarik dan kaya akan tipografi HTML.

Konteks/Kategori Kegiatan: {$jenis}
{$instruksiJudul}
{$instruksiTambahan}
{$referensiKonteks}

Panduan Penulisan sesuai Kategori:
- Jika 'Latihan Rutin': fokus pada semangat penari, detail gerakan, dan proses latihan di sanggar.
- Jika 'Event / Pentas' atau 'Event': fokus pada kemeriahan acara, kostum/pakaian adat, dan antusiasme penonton.
- Jika 'Prestasi': fokus pada rasa bangga, penghargaan, dan kerja keras tim.
- Jika 'Pengumuman': fokus pada informasi yang jelas, waktu, tempat, dan ajakan.

ATURAN TIPOGRAFI & FORMAT HTML (TINYMCE):
1. TAMPILKAN JUDUL REKOMENDASI SECARA VISIEL (bukan di dalam komentar HTML). Gunakan tag <h3> untuk judul utama / rekomendasi judul agar langsung terbaca di editor.
2. Gunakan tag tipografi HTML berikut secara proporsional agar artikel terlihat estetik:
   - <strong>kata/frasa penting</strong> untuk penekanan point utama.
   - <em>frasa atau istilah asing/daerah</em> (misal nama tarian, istilah gerakan).
   - <blockquote>...</blockquote> untuk kutipan langsung dari pimpinan sanggar atau tokoh.
   - <ul><li>...</li></ul> atau <ol><li>...</li></ol> jika ada poin-poin informasi (misal detail acara, jadwal, atau daftar pengisi acara).
   - Tabel sederhana <table> jika ada data berbentuk rincian jadwal atau susunan acara.
3. Seluruh paragraf wajib dibungkus dengan tag <p>.
4. Buat narasi artikel secara ringkas dan padat (maksimal 300-400 kata). Pastikan seluruh kalimat dan paragraf ditulis sampai selesai hingga tag penutup </p> tanpa terpotong.

CONTOH STRUKTUR OUTPUT YANG DIHARAPKAN:
<h3>Rekomendasi Judul:</h3>
<ul>
  <li><strong>Judul 1:</strong> Kemeriahan Pentas Seni Sanggar JEB...</li>
  <li><strong>Judul 2:</strong> Pesona Tari Tradisional di Acara...</li>
</ul>

<hr>

<p>Paragraf pembuka berisi informasi <strong>5W+1H</strong> dan konteks budaya <em>tari tradisional</em>...</p>

<blockquote>"Kutipan menarik dari pimpinan sanggar atau penari utama mengenai acara ini..."</blockquote>

<p>Paragraf isi yang menjelaskan jalannya kegiatan secara detail...</p>

<p>Berikut adalah beberapa poin penting kegiatan:</p>
<ul>
  <li><strong>Waktu:</strong> 19.00 WIB - Selesai</li>
  <li><strong>Lokasi:</strong> Panggung Utama Sanggar JEB</li>
</ul>

<p>Paragraf penutup yang mengajak pembaca untuk terus mendukung pelestarian budaya...</p>
PROMPT;

// \Illuminate\Support\Facades\Log::info("=== PROMPT GEMINI LENGKAP ===");
// \Illuminate\Support\Facades\Log::info($promptFinal);
// \Illuminate\Support\Facades\Log::info("=============================");

        // 5. Susun Array 'parts' (Teks + Multiple Gambar)
        $parts = [
            ['text' => $promptFinal]
        ];

        // Process gambar yang diunggah
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $mimeType = $file->getMimeType();
                    $base64Data = base64_encode(file_get_contents($file->getRealPath()));
                    
                    $parts[] = [
                        'inline_data' => [
                            'mime_type' => $mimeType,
                            'data'      => $base64Data
                        ]
                    ];
                }
            }
        }

        $payload = [
            'contents' => [
                ['parts' => $parts]
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 16384,
            ],
        ];

        // 6. Daftar Model Fallback (Gunakan model yang resmi rilis)
        $modelList = [
        'gemini-2.5-flash',
        'gemini-2.5-pro',
        'gemini-2.5-flash-lite',
        'gemini-3.5-flash',
        'gemini-3.5-flash-lite',
        'gemini-3.6-flash',
        ];

        $hasilGenerasi = null;
        $errorTerakhir = null;

        foreach ($modelList as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            // TAMBAHKAN TRY-CATCH DI DALAM LOOP
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(120)
                    ->connectTimeout(10)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $hasilGenerasi = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                    if (!empty($hasilGenerasi)) {
                        break; // Jika sukses, keluar dari loop
                    }
                } else {
                    $errorTerakhir = "Model {$model} error: " . $response->body();
                    \Illuminate\Support\Facades\Log::warning("Gemini {$model} gagal: " . $response->body());
                }
            } catch (\Exception $e) {
                // Tangkap error cURL / Timeout di sini agar loop bisa lanjut ke model berikutnya
                $errorTerakhir = "Gagal terhubung ke {$model}: " . $e->getMessage();
                \Illuminate\Support\Facades\Log::error($errorTerakhir);
            }
        }

        if (empty($hasilGenerasi)) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate dari semua model Gemini. ' . ($errorTerakhir ?? ''),
            ], 500);
        }

        // 7. Clean Output
        $cleanText = trim($hasilGenerasi);
        $cleanText = str_replace(['```html', '```'], '', $cleanText);

        return response()->json([
            'success' => true,
            'content' => trim($cleanText),
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
