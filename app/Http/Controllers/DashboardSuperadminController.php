<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Katalog;
use App\Models\Event;
use App\Models\Berita;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardSuperadminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mendapatkan bulan dan tahun saat ini untuk kalkulasi data bulanan
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. DATA TOTAL STATISTIK
        $totalAdmin = User::where('peran', 'admin')->count();
        $totalKatalog = Katalog::count();
        $activeEvents = Event::where('status', 'belum selesai')->count();
        $totalBerita = Berita::count();

        // 2. LOGIKA BARU: HITUNG DATA BARU YANG MASUK BULAN INI
        $newAdminThisMonth = User::where('peran', 'admin')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $newKatalogThisMonth = Katalog::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $newEventsThisMonth = Event::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $newBeritaThisMonth = Berita::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // 3. DAFTAR ADMIN TERBARU (LIMIT 5)
        $latestAdmins = User::where('peran', 'admin')
            ->latest()
            ->take(5)
            ->get();

        // 4. LOG AKTIVITAS TERBARU (LIMIT 5)
        $recentActivities = LogAktivitas::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Melempar seluruh variabel ke dalam View Blade
        return view('superadmin.dashboard', compact(
            'totalAdmin',
            'totalKatalog',
            'activeEvents',
            'totalBerita',
            'newAdminThisMonth',
            'newKatalogThisMonth',
            'newEventsThisMonth',
            'newBeritaThisMonth',
            'latestAdmins',
            'recentActivities'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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