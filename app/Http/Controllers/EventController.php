<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Galeri;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::with('user', 'galeri');
        
        if ($request->filled('search')) {
            // PERBAIKAN: Gunakan nama kolom 'nama' sesuai database migration Anda
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        
        $events = $query->latest()->paginate(10);
        
        $totalEvent = Event::count();
        $selesai = Event::where('status', 'selesai')->count();
        $belumSelesai = Event::where('status', 'belum selesai')->count();

        return view('superadmin.event.index', compact('events', 'totalEvent', 'selesai', 'belumSelesai'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. VALIDASI: Sesuai pola Berita, mendukung tumpukan array 'foto'
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal'    => 'required|date',
            'jam'        => 'required',
            'lokasi'     => 'required|string',
            'kategori'   => 'required|in:Latihan,Pertunjukan,Pelatihan,Lainnya',
            'status'     => 'required|in:selesai,belum selesai',
            'foto'       => 'nullable|array', 
            'foto.*'     => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        // 2. Simpan data teks utama ke tabel event
        $event = Event::create([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi ?? '-', // Memastikan deskripsi terisi jika ada inputnya
            'tanggal'   => $request->tanggal,
            'jam'       => $request->jam,
            'lokasi'    => $request->lokasi,
            'kategori'  => $request->kategori,
            'status'    => $request->status,
            'user_id'   => Auth::id(),
        ]);

        // 3. PROSES MULTIPLE UPLOAD FOTO
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                
                // Simpan file fisik ke folder public/storage/event
                $path = $file->store('event', 'public');
                
                // Catat baris record ke tabel galeri
                Galeri::create([
                    'sumber_id'    => $event->id, 
                    'path'         => $path,       
                    'nama_file'    => uniqid() . '_' . $file->getClientOriginalName(), 
                    'kategori'     => 'event',
                    'is_watermark' => false,
                ]);
            }
        }

        // 4. Catat Jejak ke Log Aktivitas
        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Menambahkan event baru: ' . $request->nama,
            'waktu'     => now(),
        ]);

        return redirect()->route('superadmin.kelola-event')->with('success', 'Event berhasil ditambahkan dengan multi-foto.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('superadmin.kelola-event')->with('error', 'Halaman detail tidak tersedia.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $event = Event::with('galeri')->findOrFail($id);
        return view('superadmin.event.update', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'nama'   => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'jam'          => 'required',
            'lokasi'       => 'required|string',
            'kategori'     => 'required|in:Latihan,Pertunjukan,Pelatihan,Lainnya',
            'status'       => 'required|in:selesai,belum selesai',
            'foto'         => 'nullable|array',
            'foto.*'       => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'hapus_foto'   => 'nullable|array', 
            'hapus_foto.*' => 'exists:galeri,id',
        ]);

        // 1. Perbarui data teks utama
        $event->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi ?? $event->deskripsi,
            'tanggal'   => $request->tanggal,
            'jam'       => $request->jam,
            'lokasi'    => $request->lokasi,
            'kategori'  => $request->kategori,
            'status'    => $request->status,
        ]);

        // 2. LOGIKA HAPUS FOTO SELEKTIF (Pola Sukses Berita)
        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $fotoId) {
                $fotoLama = Galeri::where('sumber_id', $event->id)
                                  ->where('kategori', 'event')
                                  ->find($fotoId);

                if ($fotoLama) {
                    // Hapus file fisik dari folder storage/event/
                    if (Storage::disk('public')->exists($fotoLama->path)) {
                        Storage::disk('public')->delete($fotoLama->path);
                    }
                    // Hapus record dari database
                    $fotoLama->delete();
                }
            }
        }

        // 3. Tambahkan berkas foto baru jika ada upload tambahan
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('event', 'public');
                
                Galeri::create([
                    'sumber_id'    => $event->id,
                    'path'         => $path,
                    'nama_file'    => uniqid() . '_' . $file->getClientOriginalName(),
                    'kategori'     => 'event',
                    'is_watermark' => false,
                ]);
            }
        }

        // 4. Catat Perubahan ke Log Aktivitas
        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Memperbarui data event: ' . $event->nama,
            'waktu'     => now(),
        ]);

        return redirect()->route('superadmin.kelola-event')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::with('galeri')->findOrFail($id);
        $namaEvent = $event->nama;

        // 1. Bersihkan seluruh file fisik gambar di storage/event/ sebelum menghapus baris tabel
        foreach ($event->galeri as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
            $foto->delete();
        }

        // 2. Hapus data utama event
        $event->delete();

        // 3. Catat Aktivitas ke Log
        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aktivitas' => 'Menghapus event: ' . $namaEvent,
            'waktu'     => now(),
        ]);

        return redirect()->route('superadmin.kelola-event')->with('success', 'Event beserta seluruh gambarnya berhasil dihapus.');
    }
}