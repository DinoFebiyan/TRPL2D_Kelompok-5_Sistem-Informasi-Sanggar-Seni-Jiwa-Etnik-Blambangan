<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Galeri;

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
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi_berita' => 'required|string',
            'jenis_berita' => 'required|in:pengumuman,prestasi,kolaborasi,event,lainnya',
            'foto_header.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $berita = Berita::create([
            'judul' => $request->judul,
            'konten' => $request->isi_berita,
            'status' => $request->jenis_berita,
            'user_id' => auth()->id(),
        ]);

        if ($request->hasFile('foto_header')) {
            foreach ($request->file('foto_header') as $file) {
                $path = $file->store('public/berita');
                Galeri::create([
                    'berita_id' => $berita->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('superadmin.kelola-berita')->with('success', 'Berita berhasil ditambahkan.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
