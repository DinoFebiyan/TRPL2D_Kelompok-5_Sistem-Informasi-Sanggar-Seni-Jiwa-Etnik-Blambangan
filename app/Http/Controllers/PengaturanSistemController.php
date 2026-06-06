<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengaturanSistem;
use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;

class PengaturanSistemController extends Controller
{
    public function index()
    {
        $sistem = PengaturanSistem::with('headers')->first();
        if (!$sistem) {
            $sistem = PengaturanSistem::create([
                'link_maps' => '', 'link_wa' => '', 'link_email' => '', 'link_instagram' => '', 'link_tiktok' => ''
            ]);
        }
        return view('kelola.pengaturan_sistem.index', compact('sistem'));
    }

    public function edit()
    {
        $sistem = PengaturanSistem::with('headers')->first();
        if (!$sistem) {
            $sistem = PengaturanSistem::create([
                'link_maps' => '', 'link_wa' => '', 'link_email' => '', 'link_instagram' => '', 'link_tiktok' => ''
            ]);
        }
        return view('kelola.pengaturan_sistem.edit', compact('sistem'));
    }

    public function update(Request $request)
    {
        $sistem = PengaturanSistem::first();
        if (!$sistem) {
            $sistem = PengaturanSistem::create([
                'link_maps' => '', 'link_wa' => '', 'link_email' => '', 'link_instagram' => '', 'link_tiktok' => ''
            ]);
        }

        $request->validate([
            'link_maps'        => 'nullable|string',
            'link_wa'          => 'nullable|string|max:50',
            'link_email'       => 'nullable|email|max:255',
            'link_instagram'   => 'nullable|url|max:255',
            'link_tiktok'      => 'nullable|url|max:255',
            'header_files'     => 'nullable|array',
            'header_files.*'   => 'image|mimes:jpeg,png,jpg|max:16384',
            'hapus_header'     => 'nullable|array',
            'hapus_header.*'   => 'exists:galeri,id',
        ]);

        // Hapus Header lama yang ditandai
        if ($request->filled('hapus_header')) {
            foreach ($request->hapus_header as $fotoId) {
                $fotoLama = Galeri::where('sumber_id', $sistem->id)->where('kategori', 'header')->find($fotoId);
                if ($fotoLama) {
                    if (Storage::disk('public')->exists($fotoLama->path)) {
                        Storage::disk('public')->delete($fotoLama->path);
                    }
                    $fotoLama->delete();
                }
            }
        }

        // Upload Header Baru
        if ($request->hasFile('header_files')) {
            foreach ($request->file('header_files') as $file) {
                $path = $file->store('header', 'public');
                Galeri::create([
                    'sumber_id'    => $sistem->id,
                    'path'         => $path,
                    'nama_file'    => uniqid() . '_' . $file->getClientOriginalName(),
                    'kategori'     => 'header',
                    'is_watermark' => false,
                ]);
            }
        }

        $sistem->update([
            'link_maps'      => $request->link_maps,
            'link_wa'        => $request->link_wa,
            'link_email'     => $request->link_email,
            'link_instagram' => $request->link_instagram,
            'link_tiktok'    => $request->link_tiktok,
        ]);

        if (class_exists('App\Models\LogAktivitas')) {
            \App\Models\LogAktivitas::create([
                'user_id'   => auth()->id(),
                'aktivitas' => 'Memperbarui konfigurasi media sosial, kontak, dan slider header sistem.',
                'waktu'     => now(),
            ]);
        }

        return redirect()->route('superadmin.pengaturan-sistem.index')->with('success', 'Konfigurasi integrasi sistem berhasil disimpan.');
    }
}