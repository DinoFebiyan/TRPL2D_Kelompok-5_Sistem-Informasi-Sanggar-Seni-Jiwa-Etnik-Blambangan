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
    // Cukup satu fungsi index saja yang lengkap
    public function index()
    {
        $date = now();
        $daysInMonth = $date->daysInMonth;
        $blankDays = $date->startOfMonth()->dayOfWeek; 
        
        $data = [
            'profil' => ProfilSanggar::first(),
            'katalogs' => Katalog::latest()->take(3)->get(),
            'beritaUtama' => Berita::with('galeri')->latest()->first(),
            'beritaTerbaru' => Berita::with('galeri')->latest()->skip(1)->take(3)->get(),
            'events' => Event::all(),
            'galeri' => Galeri::latest()->take(6)->get(),
            
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
    // Mengambil semua berita, urutkan dari yang terbaru
    $beritas = Berita::latest()->get(); 
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
        $events = Event::all();
        return view('umum.event', compact('events'));
    }

    public function eventDetail(string $id)
    {
        $event = Event::findOrFail($id);
        return view('umum.event_detail', compact('event'));
    }

    public function katalog()
    {
        $katalogs = Katalog::latest()->get();
        return view('umum.katalog.index', compact('katalogs'));
    }

        public function katalogDetail(string $id)
    {
        // Mengambil data item yang dipilih
        $katalog = Katalog::findOrFail($id);
        
        // Mengambil 4 katalog lain secara acak untuk bagian "Katalog Lain"
        $katalogLain = Katalog::where('id', '!=', $id)
                            ->inRandomOrder()
                            ->take(4)
                            ->get();

        return view('umum.katalog.detail', compact('katalog', 'katalogLain'));
    }

    public function galeri()
    {
        $galeris = Galeri::latest()->paginate(9);
        return view('umum.galeri', compact('galeris'));
    }

    public function galeriDetail(string $id)
    {
        $galeri = Galeri::findOrFail($id);
        return view('umum.galeri_detail', compact('galeri'));
    }
}