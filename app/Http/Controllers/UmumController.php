<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Event;
use App\Models\Katalog;
use App\Models\Galeri;
use App\Models\ProfilSanggar;
use Illuminate\Http\Request;

class UmumController extends Controller
{
    
    public function index()
    {
        $date = now();
        $daysInMonth = $date->daysInMonth;
        $blankDays = $date->startOfMonth()->dayOfWeek; 
        
        $data = [
            'profil' => ProfilSanggar::first(),
            'katalogs' => Katalog::with('galeri')->latest()->take(3)->get(),
            'beritaUtama' => Berita::with('galeri')->latest()->first(),
            'beritaTerbaru' => Berita::with('galeri')->latest()->skip(1)->take(3)->get(),
            'events' => Event::all(),
            'galeri' => Galeri::whereNotIn('kategori', ['logo', 'pembina']) 
                  ->latest()
                  ->take(6)
                  ->get(),
            
            // Variabel untuk Kalender
            'daysInMonth' => $daysInMonth,
            'blankDays' => $blankDays,
            'currentMonthName' => $date->format('F'),
            'eventDays' => Event::pluck('tanggal')->map(fn($tgl) => (int) \Carbon\Carbon::parse($tgl)->format('d'))->toArray(),
        ];
        
        return view('umum.landing_page', $data);
    }

   
    public function berita()
    {
        // PERBAIKAN: Tambahkan with('galeri') agar data gambar ikut ditarik dengan benar
        $beritas = Berita::with('galeri')->latest()->get(); 
        return view('umum.berita.index', compact('beritas'));
    }

    public function beritaDetail($id)
    {
        $berita = Berita::with(['galeri', 'user'])->findOrFail($id);

        $beritaLainnya = Berita::with(['galeri', 'user'])
                            ->where('id', '!=', $id)
                            ->latest('tgl_terbit')
                            ->take(4)
                            ->get();

        return view('umum.berita.detail', compact('berita', 'beritaLainnya'));
    }

    // Fungsi untuk Event
    public function event()
{
    $date = now();
    $daysInMonth = $date->daysInMonth;
    $blankDays = $date->startOfMonth()->dayOfWeek; 
    
    $events = Event::all();

    return view('umum.event.index', [
        'events' => $events,
        'daysInMonth' => $daysInMonth,
        'blankDays' => $blankDays,
        'currentMonthName' => $date->format('F'),
        'currentYear' => $date->format('Y'),
        'currentMonth' => $date->format('m'),
        'eventDays' => Event::pluck('tanggal')->map(fn($tgl) => (int) \Carbon\Carbon::parse($tgl)->format('d'))->toArray(),
    ]);
}

    public function eventDetail($id)
{
    
    $event = Event::with('galeri')->findOrFail($id);

    
    $eventLainnya = Event::where('id', '!=', $id)
                          ->orderBy('tanggal', 'desc')
                          ->take(8)
                          ->get();

    return view('umum.event.detail', compact('event', 'eventLainnya'));
}

    public function katalog()
    {
        // PERBAIKAN: Tambahkan with('galeri') agar gambar katalog ikut terpanggil
        $katalogs = Katalog::with('galeri')->latest()->get();
        return view('umum.katalog.index', compact('katalogs'));
    }

    public function katalogDetail(string $id)
    {
        // PERBAIKAN: Tambahkan eager loading galeri pada item utama
        $katalog = Katalog::with('galeri')->findOrFail($id);
        
        // PERBAIKAN: Tambahkan eager loading galeri pada bagian rekomendasi "Katalog Lain"
        $katalogLain = Katalog::with('galeri')
                            ->where('id', '!=', $id)
                            ->inRandomOrder()
                            ->take(4)
                            ->get();

        return view('umum.katalog.detail', compact('katalog', 'katalogLain'));
    }

   public function galeri(Request $request)
{
    
    $query = Galeri::whereNotIn('kategori', ['logo', 'pembina']);


    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('nama_file', 'like', '%' . $request->search . '%')
              ->orWhere('kategori', 'like', '%' . $request->search . '%');
        });
    }

    // Ambil data terbaru
    $galeriData = $query->latest()->get();

    return view('umum.galeri.index', compact('galeriData'));
}

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
}