@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header_title', 'Dashboard Admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* =========================================================================
       SYSTEM INTEGRATION DASHBOARD STYLES (ADMIN ROLE MODE - RESTRICTED ACCESS)
       ========================================================================= */
    .dashboard-wrapper {
        width: 100%;
        font-family: 'Poppins', sans-serif;
        color: #333;
    }
    
    /* 1. STATS GRID CARD DISPLAY (2 COLUMNS FOR EVENT & NEWS ONLY) */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .stat-info p {
        margin: 0;
        font-size: 0.75rem;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .stat-info h2 {
        margin: 5px 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #333;
    }
    .stat-change {
        font-size: 0.75rem;
        font-weight: 500;
        color: #666;
    }
    .stat-change.up {
        color: #16a34a;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    .stat-icon.merah { background: #fef2f2; color: #ef4444; }
    .stat-icon.hijau { background: #f0fdf4; color: #16a34a; }

    /* 2. QUICK ACCESS LINKS COMPONENT (3 COLUMNS FOR EVENT, NEWS & PROFILE) */
    .quick-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 24px;
    }
    .quick-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        text-decoration: none;
        color: #333;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
    }
    .quick-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(91, 26, 26, 0.05);
        border-color: #d4aeae;
    }
    .quick-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
                align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 12px;
        background: #fdf6f6;
    }
    .quick-card p {
        margin: 0;
        font-weight: 600;
        font-size: 0.9rem;
        color: #2D1A1A;
    }
    .quick-card span {
        font-size: 0.75rem;
        color: #888;
        margin-top: 4px;
    }

    /* 3. SPLIT COLUMN LAYOUT (2 COLUMNS) */
    .grid-2 {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 20px;
    }
    .card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        padding: 20px 0;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px 15px 20px;
        border-bottom: 1px solid #f3f4f6;
        margin-bottom: 15px;
    }
    .card-header h3 {
        margin: 0;
        font-size: 1.05rem;
        color: #333;
        font-weight: 600;
    }
    .table-wrap {
        width: 100%;
        overflow-x: auto;
    }
    
    .content-table {
        width: 100%;
        border-collapse: collapse;
    }
    .content-table thead {
        background-color: #F5EBEB;
    }
    .content-table th {
        text-align: left;
        padding: 12px 20px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    .content-table td {
        padding: 14px 20px;
        border-bottom: 1px solid #E0E0E0;
        vertical-align: middle;
        font-size: 0.85rem;
    }
    .content-table tr:last-child td {
        border-bottom: none;
    }
    
    .badge-tayang {
        background: #dcfce7;
        color: #16a34a;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-draft {
        background: #fee2e2;
        color: #ef4444;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* TIMELINE SYSTEM FOR LATEST EVENTS */
    .event-timeline {
        padding: 0 20px;
        display: flex;
        flex-direction: column;
    }
    .timeline-item {
        display: flex;
        gap: 15px;
        position: relative;
    }
    .timeline-dot-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .timeline-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #eab308;
        z-index: 2;
        margin-top: 5px;
        box-shadow: 0 0 0 4px #FEFCE8;
    }
    .timeline-line {
        flex: 1;
        width: 2px;
        background: #E5E7EB;
        margin: 4px 0;
    }
    .timeline-item:last-child .timeline-line {
        background: transparent;
    }
    .timeline-content {
        padding-bottom: 20px;
        flex: 1;
    }
    .timeline-content p {
        margin: 0;
        font-size: 0.85rem;
        color: #4B5563;
        line-height: 1.4;
    }
    .timeline-content p strong {
        color: #111827;
    }
    .timeline-content span {
        display: block;
        font-size: 0.75rem;
        color: #9CA3AF;
        margin-top: 4px;
    }
</style>

<div class="dashboard-wrapper">
    {{-- STATS GRID (2 COLUMNS) --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <p>Event Aktif</p>
                <h2>{{ $activeEvents ?? 0 }}</h2>
                <div class="stat-change up"><i class="fas fa-caret-up"></i> {{ $newEventsThisMonth ?? 0 }} bulan ini</div>
            </div>
            <div class="stat-icon hijau">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p>Total Berita</p>
                <h2>{{ $totalBerita ?? 0 }}</h2>
                <div class="stat-change up"><i class="fas fa-caret-up"></i> {{ $newBeritaThisMonth ?? 0 }} bulan ini</div>
            </div>
            <div class="stat-icon merah">
                <i class="fas fa-newspaper"></i>
            </div>
        </div>
    </div>

    {{-- QUICK ACCESS --}}
    <div class="quick-grid">
        <a class="quick-card" href="{{ url('/admin/kelola-event') }}">
            <div class="quick-icon"><i class="fas fa-calendar-plus" style="color: #16a34a;"></i></div>
            <p>Buat Event</p>
            <span>Jadwalkan kegiatan baru</span>
        </a>

        <a class="quick-card" href="{{ url('/admin/kelola-berita') }}">
            <div class="quick-icon"><i class="fas fa-newspaper" style="color: #ef4444;"></i></div>
            <p>Kelola Berita</p>
            <span>Buat berita & artikel baru</span>
        </a>

        <a class="quick-card" href="{{ url('/admin/kelola-profil') }}">
            <div class="quick-icon"><i class="fas fa-id-card" style="color: #eab308;"></i></div>
            <p>Kelola Profil</p>
            <span>Perbarui informasi sanggar</span>
        </a>
    </div>

    <div class="grid-2">
        {{-- TABEL BERITA TERBARU --}}
        <div class="card">
            <div class="card-header">
                <h3>Berita Terbaru</h3>
                <a href="{{ url('/admin/kelola-berita') }}" style="text-decoration: none; color: #5B1A1A; font-weight: 700; font-size: 0.85rem;">Kelola Berita <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i></a>
            </div>
            <div class="card-body">
                <div class="table-wrap">
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>Judul Berita</th>
                                <th>Penulis</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestBeritas ?? [] as $berita)
                            <tr>
                                <td><strong>{{ \Illuminate\Support\Str::limit($berita->judul, 45) }}</strong></td>
                                <td style="color: #666;">{{ $berita->user->name ?? 'Admin' }}</td>
                                <td>
                                    @if($berita->status == 'tayang')
                                        <span class="badge-tayang">Tayang</span>
                                    @else
                                        <span class="badge-draft">Draft</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" style="text-align: center; color: #999;">Belum ada konten berita terbaru.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- AGENDA EVENT TERBARU / TERDEKAT --}}
        <div class="card">
            <div class="card-header">
                <h3>Agenda Event Terdekat</h3>
                <a href="{{ url('/admin/kelola-event') }}" style="text-decoration: none; color: #5B1A1A; font-weight: 700; font-size: 0.85rem;">Lihat Semua <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i></a>
            </div>
            <div class="card-body">
                <div class="event-timeline">
                    @forelse($latestEvents ?? [] as $event)
                    <div class="timeline-item">
                        <div class="timeline-dot-wrap">
                            <div class="timeline-dot"></div>
                            <div class="timeline-line"></div>
                        </div>
                        <div class="timeline-content">
                            <p><strong>{{ $event->nama }}</strong> - Berlokasi di {{ $event->lokasi }}</p>
                            <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; color: #999; font-size: 0.85rem; padding: 20px 0;">Belum ada agenda event yang dijadwalkan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection