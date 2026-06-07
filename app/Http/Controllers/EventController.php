<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Helpers\UploadHelper;
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
        $search = $request->input('search');
        $statuses = $request->input('status', []); // Array status checkbox

        $query = Event::with(['user', 'galeri']);

        // 1. Logika Global Search Multi Kolom
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('lokasi', 'like', '%'.$search.'%')
                    ->orWhere('kategori', 'like', '%'.$search.'%')
                    ->orWhere('deskripsi', 'like', '%'.$search.'%');
            });
        }

        // 2. Logika Multi-Filter Checkbox Status
        if (! empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        $events = $query->latest()->paginate(10)->withQueryString();

        // Menghitung data statistik real data tanpa filter
        $totalEvent = Event::count();
        $selesai = Event::where('status', 'selesai')->count();
        $belumSelesai = Event::where('status', 'belum selesai')->count();

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return view('kelola.event.indexSuperadmin', compact('events', 'totalEvent', 'selesai', 'belumSelesai'));
        } elseif ($role === 'admin') {
            return view('kelola.event.indexAdmin', compact('events', 'totalEvent', 'selesai', 'belumSelesai'));
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kelola.event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'lokasi' => 'required|string',
            'kategori' => 'required|in:Latihan,Pertunjukan,Pelatihan,Lainnya',
            'status' => 'required|in:selesai,belum selesai',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $request->merge(['deskripsi' => SanitizeHelper::sanitize($request->deskripsi)]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if (! UploadHelper::isImageMimeValid($file)) {
                    return back()->withErrors(['foto' => 'Salah satu file bukan gambar yang valid.']);
                }
            }
        }

        $event = Event::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi ?? '-',
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'lokasi' => $request->lokasi,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('event', 'public');

                Galeri::create([
                    'sumber_id' => $event->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'event',
                    'is_watermark' => false,
                ]);
            }
        }

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menambahkan event baru: '.$request->nama,
            'waktu' => now(),
        ]);

        return redirect()->route('superadmin.kelola-event')->with('success', 'Event berhasil ditambahkan dengan multi-foto.');
        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.kelola-event')->with('success', 'Event berhasil ditambahkan dengan multi-foto.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-event')->with('success', 'Event berhasil ditambahkan dengan multi-foto.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
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

        return view('kelola.event.update', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'lokasi' => 'required|string',
            'kategori' => 'required|in:Latihan,Pertunjukan,Pelatihan,Lainnya',
            'status' => 'required|in:selesai,belum selesai',
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

        $event->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi ?? $event->deskripsi,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'lokasi' => $request->lokasi,
            'kategori' => $request->kategori,
            'status' => $request->status,
        ]);

        if ($request->filled('hapus_foto')) {
            foreach ($request->hapus_foto as $fotoId) {
                $fotoLama = Galeri::where('sumber_id', $event->id)
                    ->where('kategori', 'event')
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
                $path = $file->store('event', 'public');

                Galeri::create([
                    'sumber_id' => $event->id,
                    'path' => $path,
                    'nama_file' => uniqid().'_'.$file->getClientOriginalName(),
                    'kategori' => 'event',
                    'is_watermark' => false,
                ]);
            }
        }

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Memperbarui data event: '.$event->nama,
            'waktu' => now(),
        ]);

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.kelola-event')->with('success', 'Event berhasil diperbarui.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-event')->with('success', 'Event berhasil diperbarui.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::with('galeri')->findOrFail($id);
        $namaEvent = $event->nama;

        foreach ($event->galeri as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
            $foto->delete();
        }

        $event->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus event: '.$namaEvent,
            'waktu' => now(),
        ]);

        $role = auth()->user()->peran;

        if ($role === 'superadmin') {
            return redirect()->route('superadmin.kelola-event')->with('success', 'Event beserta seluruh gambarnya berhasil dihapus.');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.kelola-event')->with('success', 'Event beserta seluruh gambarnya berhasil dihapus.');
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }
    }
}
