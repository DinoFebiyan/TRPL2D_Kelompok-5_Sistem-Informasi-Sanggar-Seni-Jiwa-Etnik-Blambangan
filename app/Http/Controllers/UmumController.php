<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Event;
use App\Models\Galeri;
use App\Models\Katalog;
use App\Models\Personel;
use App\Models\ProfilSanggar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UmumController extends Controller
{
    public function index()
    {
        $profil = ProfilSanggar::first();
        $katalogs = Katalog::inRandomOrder()->take(3)->get();

        $beritaUtama = Berita::where('status', 'tayang')->latest()->first();
        $beritaTerbaru = Berita::where('status', 'tayang')
            ->where('id', '!=', $beritaUtama?->id)
            ->latest()
            ->take(3)
            ->get();

        $kategoriPengecualian = ['pembina', 'admin', 'logo', 'header', 'profil'];

        $galeri = Galeri::whereNotIn(\DB::raw('LOWER(kategori)'), $kategoriPengecualian)
            ->where(function ($query) use ($kategoriPengecualian) {
                foreach ($kategoriPengecualian as $keyword) {
                    $query->where(\DB::raw('LOWER(path)'), 'not like', '%'.$keyword.'%');
                }
            })
            ->inRandomOrder()
            ->take(6)
            ->get();

        $events = Event::inRandomOrder()->take(3)->get();

        $blankDays = 5;
        $daysInMonth = 31;
        $eventDays = Event::pluck('tanggal')->map(function ($tgl) {
            return (int) Carbon::parse($tgl)->format('d');
        })->toArray();

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

    public function berita(Request $request)
    {
        $search = $request->input('search');
        $categories = $request->input('kategori', []);

        $profil = ProfilSanggar::with('headers')->first();
        $query = Berita::where('status', 'tayang')->with(['user', 'galeri']);

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%'.$search.'%')
                    ->orWhere('isi_berita', 'like', '%'.$search.'%');
            });
        }

        if (! empty($categories)) {
            $query->whereIn('jenis_berita', $categories);
        }

        $beritas = $query->latest()->paginate(12)->withQueryString();
        $beritas->withPath(route('umum.berita'));

        return view('umum.berita.index', compact('beritas', 'profil'));
    }

    public function beritaDetail($id)
    {
        $profil = ProfilSanggar::with('headers')->first();
        $berita = Berita::with(['galeri', 'user'])->where('status', 'tayang')->findOrFail($id);

        $beritaLainnya = Berita::with(['galeri', 'user'])
            ->where('status', 'tayang')
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('umum.berita.detail', compact('berita', 'beritaLainnya', 'profil'));
    }

    public function event(Request $request)
    {
        $search = $request->input('search');
        $statuses = $request->input('status', []);

        $profil = ProfilSanggar::with('headers')->first();
        $query = Event::query();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('lokasi', 'like', '%'.$search.'%');
            });
        }

        if (! empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        $allEvents = $query->clone()->orderBy('tanggal', 'desc')->get();
        $events = $query->orderBy('tanggal', 'desc')->paginate(3)->withQueryString();
        $events->withPath(route('umum.event'));

        return view('umum.event.index', [
            'events' => $events,
            'allEvents' => $allEvents,
            'profil' => $profil,
        ]);
    }

    public function eventDetail($id)
    {
        $profil = ProfilSanggar::with('headers')->first();
        $event = Event::with('galeri')->findOrFail($id);

        $eventLainnya = Event::where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('umum.event.detail', compact('event', 'eventLainnya', 'profil'));
    }

    public function katalog(Request $request)
    {
        $search = $request->input('search');
        $categories = $request->input('kategori', []);

        $profil = ProfilSanggar::with('headers')->first();
        $query = Katalog::with('galeri');

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_katalog', 'like', '%'.$search.'%')
                    ->orWhere('deskripsi', 'like', '%'.$search.'%');
            });
        }

        if (! empty($categories)) {
            $query->whereIn('kategori', $categories);
        }

        $katalogs = $query->latest()->paginate(12)->withQueryString();
        $katalogs->withPath(route('umum.katalog'));

        return view('umum.katalog.index', compact('katalogs', 'profil'));
    }

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

    public function galeri(Request $request)
    {
        $profil = ProfilSanggar::with('headers')->first();

        $query = Galeri::whereNotIn(\DB::raw('LOWER(kategori)'), ['logo', 'pembina', 'profil', 'header', 'admin'])
            ->where(function ($q) {
                $q->where('path', 'not like', '%personel%')
                    ->where('path', 'not like', '%pembina%')
                    ->where('nama_file', 'not like', '%pengurus%')
                    ->where('nama_file', 'not like', '%pelatih%')
                    ->where('nama_file', 'not like', '%pembina%')
                    ->where('nama_file', 'not like', '%dummy%');
            });

        $search = $request->input('search');
        $categories = $request->input('kategori', []);

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_file', 'like', '%'.$search.'%')
                    ->orWhere('kategori', 'like', '%'.$search.'%');
            });
        }

        if (! empty($categories)) {
            $query->whereIn('kategori', $categories);
        }

        $galeriData = $query->latest()->paginate(12)->withQueryString();
        $galeriData->withPath(route('umum.galeri'));

        return view('umum.galeri.index', compact('galeriData', 'profil'));
    }

    public function galeriDetail($id)
    {
        $profil = ProfilSanggar::with('headers')->first();
        $galeriUtama = Galeri::with(['event', 'katalog', 'berita'])->findOrFail($id);

        $galeriLainnya = Galeri::where('id', '!=', $id)
            ->where('kategori', $galeriUtama->kategori)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('umum.galeri.detail', compact('galeriUtama', 'galeriLainnya', 'profil'));
    }

    public function profil(Request $request)
    {
        $profil = ProfilSanggar::with(['logo', 'fotoPembina', 'headers'])->first();

        if (! $profil) {
            $profil = new ProfilSanggar;
        }

        $search = $request->input('search');
        $peranFilters = $request->input('peran', []); // Array peran ['pengurus', 'pelatih', 'anggota']

        $personelQuery = Personel::with(['galeri' => function ($q) {
            $q->where('kategori', 'profil');
        }]);

        // Filter berdasarkan pencarian teks
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

        $isFiltered = !empty($search) || !empty($peranFilters);

        if ($isFiltered) {
            // Jika sedang melakukan pencarian/filter, tampilkan dalam satu pagination hasil pencarian
            $personels = $personelQuery->latest()->paginate(10)->withQueryString();
            $personels->withPath(route('umum.profil'));
            $pengurus = collect();
            $pelatih = collect();
            $anggota = collect();
        } else {
            // Tampilan normal: pisah per section (hanya yang aktif atau semua sesuai sistem Anda)
            $personels = null;
            $pengurus = Personel::with(['galeri' => function ($q) {
                $q->where('kategori', 'profil');
            }])->where('peran', 'pengurus')->get();

            $pelatih = Personel::with(['galeri' => function ($q) {
                $q->where('kategori', 'profil');
            }])->where('peran', 'pelatih')->get();

            $anggota = Personel::with(['galeri' => function ($q) {
                $q->where('kategori', 'profil');
            }])->where('peran', 'anggota')->get();
        }

        return view('umum.profil.index', compact('profil', 'pengurus', 'pelatih', 'anggota', 'personels', 'isFiltered'));
    }
}