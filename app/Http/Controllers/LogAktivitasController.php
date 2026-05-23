<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $query = LogAktivitas::with('user');

        if ($request->filled('search')) {
            $query->where('aktivitas', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        $logs = $query->latest()->paginate(15);

        // Statistik
        $totalAktivitas = LogAktivitas::count();
        $aktivitasHariIni = LogAktivitas::whereDate('created_at', today())->count();
        $aktivitasBulanIni = LogAktivitas::whereMonth('created_at', now()->month)->count();

        return view('superadmin.log_aktivitas.index', compact('logs', 'totalAktivitas', 'aktivitasHariIni', 'aktivitasBulanIni'));
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
