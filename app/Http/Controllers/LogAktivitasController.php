<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $types = $request->input('tipe', []); // Array penampung filter checkbox popup ("menambahkan", "memperbarui", "menghapus")

        $query = LogAktivitas::with('user');

        // 1. Global Search (Mencari teks aktivitas dan nama user aktor log)
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('aktivitas', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        // 2. Multi-Filter Jenis Operasi Data (Looping string keyword)
        if (! empty($types)) {
            $query->where(function ($q) use ($types) {
                foreach ($types as $type) {
                    $q->orWhere('aktivitas', 'like', '%'.$type.'%');
                }
            });
        }

        // Jalankan pagination
        $logs = $query->latest()->paginate(15)->withQueryString();
        $logs->withPath(url('log-aktivitas'));
        // FIX SINKRONISASI VARIABEL: Menggunakan nama variabel persis sesuai yang diminta code view Blade Anda
        $totalaktivitas = LogAktivitas::count();
        $tambahaktivitas = LogAktivitas::where('aktivitas', 'like', '%Menambahkan%')->count();
        $editaktivitas = LogAktivitas::where('aktivitas', 'like', '%Memperbarui%')->count();
        $hapusaktivitas = LogAktivitas::where('aktivitas', 'like', '%Menghapus%')->count();

        return view('kelola.log_aktivitas.indexSuperadmin', compact(
            'logs',
            'totalaktivitas',
            'tambahaktivitas',
            'editaktivitas',
            'hapusaktivitas'
        ));
    }
}
