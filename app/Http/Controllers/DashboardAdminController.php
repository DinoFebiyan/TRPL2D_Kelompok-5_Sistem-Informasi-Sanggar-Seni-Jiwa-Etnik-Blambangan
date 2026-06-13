<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Event;
use App\Models\Katalog;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mendapatkan bulan dan tahun saat ini untuk kalkulasi data bulanan
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. DATA TOTAL STATISTIK (Tanpa Data Admin)
        $totalKatalog = Katalog::count();
        $activeEvents = Event::where('status', 'belum selesai')->count();
        $totalBerita = Berita::count();

        // 2. HITUNG DATA BARU YANG MASUK BULAN INI (Tanpa Data Admin)
        $newKatalogThisMonth = Katalog::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $newEventsThisMonth = Event::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $newBeritaThisMonth = Berita::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // 3. AMBIL DATA EVENT TERBARU UNTUK DITAMPILKAN DI GRID BAWAH (Substitusi Log Aktivitas)
        $latestEvents = Event::latest()
            ->take(5)
            ->get();

        // 4. AMBIL DATA BERITA TERBARU (Substitusi Tabel Admin)
        $latestBeritas = Berita::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Melempar variabel yang sudah disaring ke dalam View
        return view('kelola.dashboardAdmin', compact(
            'totalKatalog',
            'activeEvents',
            'totalBerita',
            'newKatalogThisMonth',
            'newEventsThisMonth',
            'newBeritaThisMonth',
            'latestEvents',
            'latestBeritas'
        ));
    }
}
