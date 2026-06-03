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
    /**
     * Halaman Beranda / Landing Page Publik
     */
    public function index()
    {
        $date = now();
        $daysInMonth = $date->daysInMonth;
        $blankDays = $date->startOfMonth()->dayOfWeek; 
        
        $data = [
            'profil' => ProfilSanggar::first(),
            'katalogs' => Katalog::with('galeri')->latest()->take(3)->get(),
            'beritaUtama' => Berita::with('galeri')->where('status', 'tayang')->latest()->first(),
            'beritaTerbaru' => Berita::with('galeri')->where('status', 'tayang')->latest()->skip(1)->take(3)->get(),
            'events' => Event::take(3)->get(),
            'galeri' => Galeri::whereNotIn('kategori', ['logo', 'pembina']) 
                ->latest()
                ->take(6)
                ->get(),
            
            // Variabel untuk Kalender
            'daysInMonth' => $daysInMonth,
            'blankDays' => $blankDays,
            'currentMonthName' => $date->format('F'),
            'eventDays' => Event::pluck('tanggal')->map(fn($tgl) => (int) Carbon::parse($tgl)->format('d'))->toArray(),
        ];
        
        return view('umum.landing_page', $data);
    }

    /**
     * Halaman Daftar Berita Publik (Dengan Fitur Searching & Multi-Filter Kategori)
     */
    public function berita(Request $request)
    {
        $search = $request->input('search');
        $categories = $request->input('kategori', []); // Menangkap array checkbox kategori dari form blade

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
        $beritas = $query->latest()->paginate(9)->withQueryString();

        return view('umum.berita.index', compact('beritas'));
    }

    /**
     * Halaman Detail Konten Berita Publik
     */
    public function beritaDetail($id)
    {
        $berita = Berita::with(['galeri', 'user'])->where('status', 'tayang')->findOrFail($id);

        $beritaLainnya = Berita::with(['galeri', 'user'])
            ->where('status', 'tayang')
            ->where('id', '!=', $id)
            ->latest()
            ->take(4)
            ->get();

        return view('umum.berita.detail', compact('berita', 'beritaLainnya'));
    }

    /**
     * Halaman Daftar Event Publik
     */
    public function event(Request $request)
    {
        $date = now();
        $daysInMonth = $date->daysInMonth;
        $blankDays = $date->startOfMonth()->dayOfWeek; 
        
        $search = $request->input('search');
        $statuses = $request->input('status', []); // Array status dari checkbox

        $query = \App\Models\Event::query();

        // 1. Filter Pencarian Teks
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_event', 'like', '%' . $search . '%')
                ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }

        // 2. Filter Status Kegiatan
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        // Mengubah dari ->get() menjadi ->paginate() agar links data halaman bekerja sempurna
        $events = $query->orderBy('tanggal', 'asc')->paginate(3)->withQueryString();

        return view('umum.event.index', [
            'events' => $events,
            'daysInMonth' => $daysInMonth,
            'blankDays' => $blankDays,
            'currentMonthName' => $date->format('F'),
            'currentYear' => $date->format('Y'),
            'currentMonth' => $date->format('m'),
            'eventDays' => \App\Models\Event::pluck('tanggal')->map(fn($tgl) => (int) \Carbon\Carbon::parse($tgl)->format('d'))->toArray(),
        ]);
    }

    /**
     * Halaman Detail Event Publik
     */
    public function eventDetail($id)
    {
        $event = Event::with('galeri')->findOrFail($id);

        $eventLainnya = Event::where('id', '!=', $id)
            ->orderBy('tanggal', 'desc')
            ->take(8)
            ->get();

        return view('umum.event.detail', compact('event', 'eventLainnya'));
    }

    /**
     * Halaman Daftar Katalog Publik
     */
    public function katalog(Request $request)
    {
        $search = $request->input('search');
        $categories = $request->input('kategori', []); // Menampung array checkbox kategori kostum

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

        // Membatasi isi grid 8 data per halaman agar simetris (layout grid 4 kolom = 2 baris seimbang)
        $katalogs = $query->latest()->paginate(9)->withQueryString();

        return view('umum.katalog.index', compact('katalogs'));
    }

    /**
     * Halaman Detail Katalog Publik
     */
    public function katalogDetail(string $id)
    {
        $katalog = Katalog::with('galeri')->findOrFail($id);
        
        $katalogLain = Katalog::with('galeri')
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('umum.katalog.detail', compact('katalog', 'katalogLain'));
    }

    /**
     * Halaman Galeri Foto Publik
     */
    public function galeri(Request $request)
    {
        // Mengamankan data dari logo dan foto profil pembina
        $query = Galeri::whereNotIn('kategori', ['logo', 'pembina']);

        $search = $request->input('search');
        $categories = $request->input('kategori', []); // Array checkbox kategori galeri

        // 1. Logika Global Searching Data File Galeri
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama_file', 'like', '%' . $search . '%')
                ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }

        // 2. Logika Multi-Filter Checkbox Kategori
        if (!empty($categories)) {
            $query->whereIn('kategori', $categories);
        }

        // Mengubah ->get() menjadi ->paginate(12) agar layout grid (4 kolom x 3 baris) terbagi presisi dan rapi
        $galeriData = $query->latest()->paginate(12)->withQueryString();

        return view('umum.galeri.index', compact('galeriData'));
    }

    /**
     * Halaman Detail Item Galeri Publik
     */
    public function galeriDetail($id)
    {
        $galeriUtama = Galeri::findOrFail($id);

        $galeriLainnya = Galeri::where('id', '!=', $id)
            ->where('kategori', $galeriUtama->kategori) 
            ->latest()
            ->take(4)
            ->get();

        return view('umum.galeri.detail', compact('galeriUtama', 'galeriLainnya'));
    }

    /**
     * Halaman Profil Struktur Organisasi Sanggar Publik
     */
    public function profil()
    {
        $profil = ProfilSanggar::with(['logo', 'fotoPembina'])->first();
        $pengurus = Personel::where('peran', 'pengurus')->where('status', 'aktif')->get();
        $pelatih = Personel::where('peran', 'pelatih')->where('status', 'aktif')->get();
        $prestasi = Berita::where('jenis_berita', 'Prestasi')->where('status', 'tayang')->latest()->take(4)->get();

        return view('umum.profil.index', compact('profil', 'pengurus', 'pelatih', 'prestasi'));
    }
}