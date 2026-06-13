<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Event;
use App\Models\Katalog;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class DashboardSuperadminController extends Controller
{
    public function index()
    {
        // Set lokal bahasa Indonesia untuk tanggal jika diperlukan
        Carbon::setLocale('id');

        // 1. Query untuk 4 Kartu Metrik Utama di Bagian Atas
        $eventMendatangCount = Event::where('status', 'belum selesai')->count();
        $beritaTayangCount = Berita::where('status', 'tayang')->count();
        $totalKatalogCount = Katalog::count();
        $totalLogAktivitas = LogAktivitas::count();

        // 2. Query untuk Tabel Kiri: 5 Event Terdekat yang Belum Selesai
        $upcomingEvents = Event::where('status', 'belum selesai')
            ->orderBy('tanggal', 'asc')
            ->take(5)
            ->get();

        // 3. Query untuk Tabel Kanan: 5 Log Aktivitas Terbaru
        $recentActivities = LogAktivitas::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 4. Melempar semua data ke view dashboard superadmin
        return view('kelola.dashboardSuperadmin', compact(
            'eventMendatangCount',
            'beritaTayangCount',
            'totalKatalogCount',
            'totalLogAktivitas',
            'upcomingEvents',
            'recentActivities'
        ));
    }
}
