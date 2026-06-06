@extends('layouts.superadmin')

@section('title', 'Dashboard Super Admin')
@section('header_title', 'Dashboard Super Admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .dashboard-wrapper {
        width: 100%;
        font-family: 'Poppins', sans-serif;
        color: #333;
    }
    
    /* STATS GRID */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 15px 20px;
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
        font-size: 1.6rem;
        font-weight: 700;
        color: #333;
    }
    .stat-change {
        font-size: 0.7rem;
        font-weight: 500;
        color: #666;
    }
    .stat-change.up { color: #16a34a; }
    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .stat-icon.merah { background: #fef2f2; color: #ef4444; }
    .stat-icon.emas  { background: #fefce8; color: #eab308; }

    /* QUICK ACCESS */
    .quick-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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

    /* DUA KOLOM */
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 18px;
    }
    .card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        padding: 20px 0;
        overflow: hidden;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px 15px 20px;
        border-bottom: 1px solid #f3f4f6;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .card-header h3 {
        margin: 0;
        font-size: 1.05rem;
        color: #333;
        font-weight: 600;
    }
    .card-header a {
        text-decoration: none;
        color: #5B1A1A;
        font-weight: 700;
        font-size: 0.85rem;
        white-space: nowrap;
    }
    .table-wrap {
        width: 100%;
        overflow-x: auto;
    }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }
    .admin-table thead {
        background-color: #F5EBEB;
    }
    .admin-table th {
        text-align: left;
        padding: 12px 20px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    .admin-table td {
        padding: 14px 20px;
        border-bottom: 1px solid #E0E0E0;
        vertical-align: middle;
        font-size: 0.85rem;
    }
    .admin-table tr:last-child td { border-bottom: none; }
    .badge-aktif {
        background: #dcfce7;
        color: #16a34a;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-nonaktif {
        background: #fee2e2;
        color: #ef4444;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .action-btn {
        border: none;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        margin-right: 4px;
        cursor: pointer;
    }
    .action-btn:first-child { background: #eff6ff; color: #3b82f6; }
    .action-btn:last-child { background: #fef2f2; color: #ef4444; }

    /* ========== AKTIVITAS LIST - TIDAK KELUAR & LEGA ========== */
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 0;
        /* Penting: beri padding kiri agar titik tidak menempel ke tepi card */
        padding-left: 20px;
        padding-right: 20px;
    }

    .activity-item {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        margin-bottom: 24px;  /* jarak antar item lega */
    }

    .activity-item:last-child {
        margin-bottom: 0;
    }

    .act-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #EF4444;
        flex-shrink: 0;
        margin-top: 5px;  /* sejajar dengan teks baris pertama */
    }

    .act-content {
        flex: 1;
    }

    .act-content p {
        margin: 0 0 4px 0;
        font-size: 0.85rem;
        line-height: 1.5;
        color: #4B5563;
        word-break: break-word;
    }

    .act-content p strong {
        color: #111827;
    }

    .act-content span {
        display: block;
        font-size: 0.75rem;
        color: #9CA3AF;
        margin-top: 4px;
    }

    /* RESPONSIF */
    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .quick-grid {
            grid-template-columns: 1fr;
        }
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .admin-table th, .admin-table td {
            padding: 10px 12px;
        }
        .stat-info h2 {
            font-size: 1.4rem;
        }
        .activity-list {
            padding-left: 16px;
            padding-right: 16px;
        }
    }

    @media (max-width: 480px) {
        .stat-card {
            flex-direction: column;
            align-items: flex-start;
        }
        .stat-icon {
            align-self: flex-end;
        }
    }
</style>

<div class="dashboard-wrapper">
    {{-- STATS GRID --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <p>Total Event</p>
                <h2>{{ $eventMendatangCount ?? 0 }}</h2>
            </div>
            <div class="stat-icon emas"><i class="fas fa-calendar-alt"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <p>Total Katalog</p>
                <h2>{{ $totalKatalogCount ?? 0 }}</h2>
            </div>
            <div class="stat-icon merah"><i class="fas fa-book"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <p>Total Berita</p>
                <h2>{{ $beritaTayangCount ?? 0 }}</h2>
            </div>
            <div class="stat-icon emas"><i class="fas fa-newspaper"></i></div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <p>Log Aktivitas</p>
                <h2>{{ $totalLogAktivitas ?? 0 }}</h2>
            </div>
            <div class="stat-icon merah"><i class="fas fa-history"></i></div>
        </div>
    </div>

    {{-- DUA KOLOM DATA UTAMA --}}
    <div class="grid-2">
        {{-- TABEL EVENT TERDEKAT --}}
        <div class="card">
            <div class="card-header">
                <h3>Event Mendatang</h3>
                <a href="{{ url('/superadmin/kelola-event') }}">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nama Event</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingEvents ?? [] as $event)
                        <tr>
                            <td><strong>{{ $event->nama }}</strong></td>
                            
                            <td>{{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</td>
                            <td><span class="badge-aktif">{{ $event->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align: center;">Tidak ada event mendatang.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- AKTIVITAS TERBARU --}}
        <div class="card">
            <div class="card-header">
                <h3>Aktivitas Terbaru</h3>
                <a href="{{ url('/superadmin/log-aktivitas') }}">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="activity-list">
                @forelse($recentActivities ?? [] as $activity)
                <div class="activity-item">
                    <div class="act-dot"></div>
                    <div class="act-content">
                        <p><strong>{{ $activity->user->name ?? 'Sistem' }}</strong> {{ $activity->aktivitas }}</p>
                        <span>{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p style="text-align: center; color: #999;">Belum ada aktivitas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection