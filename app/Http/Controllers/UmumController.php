<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Event;
use App\Models\Katalog;
use App\Models\Galeri;
use App\Models\ProfilSanggar;
use App\Models\Personel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UmumController extends Controller
{
    public function index()
    {
        // 1. Ambil data utama sanggar
        $profil = \App\Models\ProfilSanggar::first(); // Sesuaikan nama namespace model kamu
        
        // 2. Ambil data katalog secara acak
        $katalogs = \App\Models\Katalog::inRandomOrder()->take(3)->get();
        
        // 3. Logika pengambilan Berita
        $beritaUtama = \App\Models\Berita::where('status', 'tayang')->latest()->first();
        $beritaTerbaru = \App\Models\Berita::where('status', 'tayang')
                            ->where('id', '!=', $beritaUtama?->id)
                            ->latest()
                            ->take(3)
                            ->get();
        
        // 4. Ambil data Galeri secara acak
        $kategoriPengecualian = ['pembina', 'admin', 'logo', 'header', 'profil'];

$galeri = \App\Models\Galeri::whereNotIn(\DB::raw('LOWER(kategori)'), $kategoriPengecualian)
            ->where(function($query) use ($kategoriPengecualian) {
                foreach ($kategoriPengecualian as $keyword) {
                    $query->where(\DB::raw('LOWER(path)'), 'not like', '%' . $keyword . '%');
                }
            })
            ->inRandomOrder()
            ->take(6)
            ->get();
            
        // 5. Ambil data Event secara acak
        $events = \App\Models\Event::inRandomOrder()->take(3)->get();
        

        // Data pembantu untuk widget kalender bawaan (jika diperlukan)
        $blankDays = 5; 
        $daysInMonth = 31;
        $eventDays = \App\Models\Event::pluck('tanggal')->map(function($tgl) {
            return (int) \Carbon\Carbon::parse($tgl)->format('d');
        })->toArray();

        // Kirim SEMUA variabel yang dibutuhkan view di dalam compact()
        return view('umum.landing_page', compact(
            'profil', 
            'katalogs', 
            'beritaUtama', 
            'beritaTerbaru', 
            'galeri', 
            'events', 
            'blankDays',
            'daysInMonth',
            'eventDays'
        ));
    }

    /**
     * Halaman Daftar Berita Publik (Dengan Fitur Searching & Multi-Filter Kategori)
     */
    public function berita(Request $request)
    {
        $search = $request->input('search');
        $categories = $request->input('kategori', []); // Menangkap array checkbox kategori dari form blade

        // Ambil data profil beserta tumpukan gambar header untuk banner atas
        $profil = ProfilSanggar::with('headers')->first();

        // Base query: Hanya menampilkan berita yang sudah disetujui untuk 'tayang'
        $query = Berita::where('status', 'tayang')->with(['user', 'galeri']);

        // 1. Penerapan Fitur Global Searching (Judul atau Isi Teks Berita)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('isi_berita', 'like', '%' . $search . '%');
            });
        }

        // 2. Penerapan Fitur Multi-Filtering Kategori Konten Berita
        if (!empty($categories)) {
            $query->whereIn('jenis_berita', $categories);
        }

        // Ambil data terbaru dengan sistem pagination (9 data per halaman agar grid 3x3 simetris)
        $beritas = $query->latest()->paginate(12)->withQueryString();

        return view('umum.berita.index', compact('beritas', 'profil'));
    }

    /**
     * Halaman Detail Konten Berita Publik
     */
    public function beritaDetail($id)
    {
        $profil = ProfilSanggar::with('headers')->first();
        $berita = Berita::with(['galeri', 'user'])->where('status', 'tayang')->findOrFail($id);

        // Urutan diacak (Random)
        $beritaLainnya = Berita::with(['galeri', 'user'])
            ->where('status', 'tayang')
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('umum.berita.detail', compact('berita', 'beritaLainnya', 'profil'));
    }

    /**
     * Halaman Daftar Event Publik
     */
    public function event(Request $request)
    {
        $search = $request->input('search');
        $statuses = $request->input('status', []);

        $profil = ProfilSanggar::with('headers')->first();
        $query = Event::query();

        // Filter Pencarian Teks
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }

        // Filter Status Kegiatan
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // Ambil SEMUA data tanpa paginasi khusus untuk suplai KALENDER penuh
        $allEvents = $query->clone()->orderBy('tanggal', 'asc')->get();

        // Tetap menggunakan paginasi 3 data untuk list kartu awal di sebelah kanan
        $events = $query->orderBy('tanggal', 'asc')->paginate(3)->withQueryString();

        return view('umum.event.index', [
            'events' => $events,
            'allEvents' => $allEvents, 
            'profil' => $profil,
        ]);
    }

    /**
     * Halaman Detail Event Publik
     */
    public function eventDetail($id)
    {
        $profil = ProfilSanggar::with('headers')->first();
        $event = Event::with('galeri')->findOrFail($id);

        // Mengubah pengurutan agar acak (Random)
        $eventLainnya = Event::where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            // Pas dengan layout grid baru kita
            ->get();

        return view('umum.event.detail', compact('event', 'eventLainnya', 'profil'));
    }

    /**
     * Halaman Daftar Katalog Publik
     */
    public function katalog(Request $request)
    {
        $search = $request->input('search');
        $categories = $request->input('kategori', []); // Menampung array checkbox kategori kostum

        // Ambil data profil beserta tumpukan gambar header untuk banner atas
        $profil = ProfilSanggar::with('headers')->first();

        $query = Katalog::with('galeri');

        // 1. Logika Global Searching Kostum (Nama Tari atau Deskripsi)
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_tari', 'like', '%' . $search . '%')
                ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        // 2. Logika Multi-Filter Checkbox Kategori Kostum
        if (!empty($categories)) {
            $query->whereIn('kategori', $categories);
        }

        // Membatasi isi grid 12 data per halaman agar simetris
        $katalogs = $query->latest()->paginate(12)->withQueryString();

        return view('umum.katalog.index', compact('katalogs', 'profil'));
    }

    /**
     * Halaman Detail Katalog Publik
     */
    public function katalogDetail(string $id)
    {
        $profil = ProfilSanggar::with('headers')->first();
        $katalog = Katalog::with('galeri')->findOrFail($id);
        
        $katalogLain = Katalog::with('galeri')
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('umum.katalog.detail', compact('katalog', 'katalogLain', 'profil'));
    }

    /**
     * Halaman Galeri Foto Publik (Menampilkan Tepat 12 Card)
     */
    public function galeri(Request $request)
    {
        // Ambil data profil beserta tumpukan gambar header untuk banner atas
        $profil = ProfilSanggar::with('headers')->first();

        // 1. BLOKIR FOTO INTERNAL: Menghalang kategori internal, file dummy, dan path personel langsung dari query database
        $query = Galeri::whereNotIn(\DB::raw('LOWER(kategori)'), ['logo', 'pembina', 'profil', 'header', 'admin'])
            ->where(function($q) {
                $q->where('path', 'not like', '%personel%')
                  ->where('path', 'not like', '%pembina%')
                  ->where('nama_file', 'not like', '%pengurus%')
                  ->where('nama_file', 'not like', '%pelatih%')
                  ->where('nama_file', 'not like', '%pembina%')
                  ->where('nama_file', 'not like', '%dummy%');
            });

        $search = $request->input('search');
        $categories = $request->input('kategori', []); // Array checkbox kategori galeri

        // 2. Logika Global Searching Data File Galeri
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_file', 'like', '%' . $search . '%')
                ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }

        // 3. Logika Multi-Filter Checkbox Kategori
        if (!empty($categories)) {
            $query->whereIn('kategori', $categories);
        }

        // Ambil data terbaru dengan batasan tepat 12 data per halaman
        $galeriData = $query->latest()->paginate(12)->withQueryString();

        return view('umum.galeri.index', compact('galeriData', 'profil'));
    }

    /**
     * Halaman Detail Item Galeri Publik
     */
    public function galeriDetail($id)
    {
        $profil = ProfilSanggar::with('headers')->first();
        $galeriUtama = Galeri::with(['event', 'katalog', 'berita'])->findOrFail($id);

        // Menggunakan inRandomOrder() untuk mengacak rekomendasi dokumentasi
        $galeriLainnya = Galeri::where('id', '!=', $id)
            ->where('kategori', $galeriUtama->kategori) 
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('umum.galeri.detail', compact('galeriUtama', 'galeriLainnya', 'profil'));
    }

    /**
     * Halaman Profil Struktur Organisasi Sanggar Publik
     */
    public function profil()
    {
        $profil = ProfilSanggar::with(['logo', 'fotoPembina', 'headers'])->first();
        
        // PERBAIKAN: Kunci relasi galeri hanya untuk kategori 'profil' (foto personel resmi)
        $pengurus = Personel::with(['galeri' => function($query) {
            $query->where('kategori', 'profil');
        }])->where('peran', 'pengurus')->where('status', 'aktif')->get();

        $pelatih = Personel::with(['galeri' => function($query) {
            $query->where('kategori', 'profil');
        }])->where('peran', 'pelatih')->where('status', 'aktif')->get();

        return view('umum.profil.index', compact('profil', 'pengurus', 'pelatih'));
    }
}