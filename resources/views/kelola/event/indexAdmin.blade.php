@extends('layouts.admin')

@section('title', 'Kelola Event — JEB')
@section('header_title', 'Kelola Event')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* ==========================================
       1. GEMBOK UTAMA (ANTI-BLOWOUT HORIZONTAL)
       ========================================== */
    html, body {
        max-width: 100vw;
        overflow-x: hidden;
    }

    .main, .content {
        min-width: 0 !important;
        max-width: 100vw !important;
        overflow-x: hidden !important;
    }

    .event-wrapper {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
        font-family: 'Poppins', sans-serif;
        color: #333;
    }
    
    /* ==========================================
       2. RESPONSIF STATS GRID
       ========================================== */
    .event-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }
    .event-stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        min-width: 0;
    }
    .event-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .event-icon-blue { background: #eff6ff; color: #3b82f6; }
    .event-icon-green { background: #f0fdf4; color: #16a34a; }
    .event-icon-red { background: #fef2f2; color: #ef4444; }
    .event-icon-dark-red { background-color: #7a2a214a; color: #411f1f; }
    
    .event-stat-text {
        min-width: 0;
        flex: 1;
    }
    .event-stat-text h4 {
        margin: 0;
        font-size: 0.7rem;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .event-stat-text h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
    }
    .event-btn-tambah {
        background: #e2c0c0;
        color: #5a1a1a;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        border: 1px solid #e8dede;
        transition: 0.2s;
        min-width: 0;
    }
    .event-btn-tambah:hover {
        background: #d4aeae;
    }

    /* ==========================================
       3. SEARCH & FILTER ROW (DIPAKSA 1 BARIS)
       ========================================== */
    .event-search-row {
        display: flex;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        gap: 15px;
        margin-bottom: 24px;
        align-items: center;
        width: 100%;
        box-sizing: border-box;
    }
    .event-search-box {
        flex: 1 1 0%;
        min-width: 0;
        position: relative;
    }
    .event-search-box input {
        width: 100%;
        min-width: 0;
        padding: 12px 35px 12px 15px;
        border-radius: 10px;
        border: 1px solid #e8dede;
        outline: none;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        box-sizing: border-box;
    }
    .event-search-box .fa-search {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #eab308;
        font-size: 1.1rem;
        cursor: pointer;
    }
    .event-btn-filter {
        flex: 0 0 auto;
        background: #b91c1c;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        white-space: nowrap;
    }

    /* ==========================================
       4. TABEL RESPONSIF (ANTI BOCOR)
       ========================================== */
    .event-table-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px; 
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    .event-table-card h3 {
        margin: 0 0 16px 0;
        font-size: 1.1rem;
        color: #333;
    }
    
    /* WADAH SCROLL MENDATAR KHUSUS TABEL */
    .table-responsive-wrapper {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 10px;
        padding-bottom: 15px; /* Ruang lega agar scrollbar tidak numpuk */
    }
    
    .event-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1100px; /* Event butuh space besar untuk 8 kolom */
    }
    .event-table thead {
        background-color: #F5EBEB; 
    }
    .event-table th {
        text-align: left;
        padding: 14px 12px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: none;
        text-transform: uppercase; 
        white-space: nowrap;
    }
    .event-table td {
        padding: 15px 12px;
        border-bottom: 1px solid #E0E0E0; 
        vertical-align: middle;
        font-size: 0.85rem;
    }
    
    .event-foto-preview {
        width: 60px;
        height: 50px;
        background: #f0f0f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: #999;
        overflow: hidden;
        position: relative;
    }
    .event-foto-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0; opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    .event-foto-preview img.active {
        opacity: 1;
    }
    
    .badge-selesai {
        background: #dcfce7;
        color: #16a34a;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-belum-selesai {
        background: #fef08a;
        color: #a16207;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .event-actions {
        white-space: nowrap;
    }
    .event-actions a, .event-actions button {
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 4px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }
    .btn-edit-event { background: #eff6ff; color: #3b82f6; }
    .btn-hapus-event { background: #fef2f2; color: #ef4444; }
    
    /* ==========================================
       5. PAGINATION (LEBIH LEGA)
       ========================================== */
    .event-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 35px; /* Jarak aman dari scrollbar tabel */
        padding: 10px 5px 0 5px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .event-pagination p {
        font-size: 0.85rem;
        color: #b07d7d;
        margin: 0;
        font-weight: 500;
    }
    .event-page-controls {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .event-page-controls a, .event-page-controls span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #e8dede;
        background: #fff;
        color: #333;
        font-size: 0.85rem;
        text-decoration: none;
        cursor: pointer;
        transition: 0.2s;
    }
    .event-page-controls a:hover {
        background: #f5ebeb;
        border-color: #d4aeae;
    }
    .event-page-controls span.active {
        background: #5B1A1A;
        color: #fff;
        border-color: #5B1A1A;
        font-weight: 700;
    }
    .event-page-controls span.disabled {
        color: #ccc;
        cursor: not-allowed;
        background: #fdfdfd;
    }

    /* MODAL POPUP FILTER COMPONENT STYLE */
    .custom-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        justify-content: center; align-items: center;
        z-index: 9999;
    }
    .custom-modal-content {
        background: #fff; width: 90%; max-width: 480px;
        border-radius: 24px; padding: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        animation: popupSlightIn 0.3s ease-out;
    }
    .custom-modal-header h3 { margin: 0; font-size: 1.2rem; font-weight: 700; color: #2D1A1A; }
    .custom-modal-header p { margin: 4px 0 0 0; font-size: 0.8rem; color: #888; }
    .modal-divider { border: 0; border-top: 1px solid #E5E7EB; margin: 16px 0; }
    .section-title { font-size: 0.9rem; font-weight: 600; color: #333; display: block; margin-bottom: 12px; }
    .checkbox-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .filter-checkbox-card {
        border: 1px solid #D1D5DB; border-radius: 12px; padding: 10px 12px;
        display: flex; align-items: center; gap: 8px; cursor: pointer;
        transition: all 0.2s; background: #fff; user-select: none;
    }
    .filter-checkbox-card:hover { border-color: #b07d7d; background: #fffbfb; }
    .filter-checkbox-card input[type="checkbox"] { accent-color: #EF4444; width: 16px; height: 16px; cursor: pointer; }
    .filter-checkbox-card.selected-box { border-color: #EF4444; background-color: #FFF5F5; }
    .filter-checkbox-card.selected-box .checkbox-label { font-weight: 600; color: #EF4444; }
    .checkbox-label { font-size: 0.85rem; color: #4B5563; cursor: pointer; }
    .custom-modal-footer { display: flex; gap: 12px; margin-top: 20px; }
    .btn-modal-batal, .btn-modal-terapkan {
        flex: 1; padding: 10px; border-radius: 10px; font-family: 'Poppins', sans-serif;
        font-weight: 600; font-size: 0.85rem; border: none; cursor: pointer; transition: 0.2s;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-modal-batal { background: #E5E7EB; color: #374151; }
    .btn-modal-batal:hover { background: #D1D5DB; }
    .btn-modal-terapkan { background: #EF4444; color: white; }
    .btn-modal-terapkan:hover { background: #DC2626; }

    /* ==========================================
       6. MEDIA QUERIES UNTUK HP (MOBILE)
       ========================================== */
    @media (max-width: 640px) {
        /* GRID STATISTIK 2x2 */
        .event-stats-grid {
            grid-template-columns: repeat(2, 1fr) !important; 
            gap: 10px !important;
            width: 100% !important;
        }
        .event-stat-card {
            padding: 12px 10px !important;
            flex-direction: column !important;
            text-align: center !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 5px !important;
            min-width: 0 !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .event-icon-box {
            width: 35px !important; height: 35px !important; font-size: 1rem !important;
        }
        .event-stat-text { width: 100% !important; }
        .event-stat-text h2 { font-size: 1.25rem !important; }
        .event-stat-text h4 { font-size: 0.65rem !important; }
        
        .event-btn-tambah {
            grid-column: span 2 !important; 
            padding: 12px !important;
            font-size: 0.9rem !important;
            flex-direction: row !important;
            width: 100% !important;
        }

        /* MEMAKSA SEARCH DAN FILTER 1 BARIS DI HP */
        .event-search-row {
            display: flex !important;
            flex-direction: row !important; 
            flex-wrap: nowrap !important;
            gap: 8px !important;
            width: 100% !important;
        }
        .event-search-box {
            flex: 1 1 0% !important;
        }
        .event-search-box input {
            padding: 10px 30px 10px 12px !important;
            font-size: 0.8rem !important;
            min-width: 0 !important;
            width: 100% !important;
            height: 42px !important;
        }
        .event-search-box .fa-search {
            right: 10px !important;
            font-size: 0.9rem !important;
        }
        
        /* TOMBOL FILTER DIKUNCI JADI KOTAK PRESISI DI HP */
        .event-btn-filter {
            flex: 0 0 42px !important; /* Lebar mutlak 42px */
            height: 42px !important; /* Tinggi mutlak 42px */
            padding: 0 !important; /* Hapus padding agar tidak gepeng */
            font-size: 1rem !important;
            justify-content: center !important;
        }
        .event-btn-filter span { display: none !important; } 

        /* PERBAIKAN TABEL CARD DI HP */
        .event-table-card {
            padding: 15px 10px !important; 
            width: 100% !important;
        }
        
        .event-pagination {
            flex-direction: column !important;
            gap: 12px !important;
            margin-top: 25px !important;
        }
    }

    @keyframes popupSlightIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

<div class="event-wrapper">
    <div class="event-stats-grid">
        <div class="event-stat-card">
            <div class="event-icon-box event-icon-blue"><i class="fas fa-calendar-alt"></i></div>
            <div class="event-stat-text">
                <h4>Total Event</h4>
                <h2>{{ $totalEvent ?? 0 }}</h2>
            </div>
        </div>
        <div class="event-stat-card">
            <div class="event-icon-box event-icon-green"><i class="fas fa-check-circle"></i></div>
            <div class="event-stat-text">
                <h4>Selesai</h4>
                <h2>{{ $selesai ?? 0 }}</h2>
            </div>
        </div>
        <div class="event-stat-card">
            <div class="event-icon-box event-icon-red"><i class="fas fa-ban"></i></div>
            <div class="event-stat-text">
                <h4>Belum Selesai</h4>
                <h2>{{ $belumSelesai ?? 0 }}</h2>
            </div>
        </div>
        <a href="{{ route('admin.tambah-event') }}" class="event-btn-tambah">
            <div class="event-icon-box event-icon-dark-red">
                <i class="fas fa-plus"></i>
            </div>
            <span>Tambah Event</span>
        </a>
    </div>

    {{-- INTEGRATED FORM GET --}}
    <form action="{{ route('admin.kelola-event') }}" method="GET" id="formFilterEvent">
        <div class="event-search-row">
            <div class="event-search-box">
                <input type="text" name="search" id="searchEvent" placeholder="Cari event..." value="{{ request('search') }}">
                <i class="fas fa-search" onclick="submitFormFilter()"></i>
            </div>
            <button type="button" class="event-btn-filter" onclick="openFilterModal()">
                <i class="fas fa-filter"></i> <span>Filter</span>
            </button>
        </div>

        {{-- POPUP FILTER DI DALAM TAG FORM --}}
        <div id="filterModal" class="custom-modal-overlay">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h3>Filter Pencarian anda</h3>
                    <p>Sesuaikan apa yang ingin anda tampilkan</p>
                </div>
                <hr class="modal-divider">

                <div class="custom-modal-body">
                    <div class="filter-group-section">
                        <label class="section-title">Status Event</label>
                        <div class="checkbox-grid">
                            <label class="filter-checkbox-card {{ in_array('selesai', request('status', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="status[]" value="selesai" onchange="syncCardVisual(this)" {{ in_array('selesai', request('status', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Selesai</span>
                            </label>
                            <label class="filter-checkbox-card {{ in_array('belum selesai', request('status', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="status[]" value="belum selesai" onchange="syncCardVisual(this)" {{ in_array('belum selesai', request('status', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Belum Selesai</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="custom-modal-footer">
                    <button type="button" class="btn-modal-batal" onclick="closeFilterModal()">Batal</button>
                    <button type="submit" class="btn-modal-terapkan">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </form>

    <div class="event-table-card">
        <h3>Daftar Event</h3>
        
        {{-- BUNGKUSAN SCROLL TABEL (GEMBOK ANTI BOCOR) --}}
        <div class="table-responsive-wrapper">
            <table class="event-table" id="eventTable">
                <thead>
                    <tr>
                        <th>FOTO</th>
                        <th>NAMA EVENT</th>
                        <th>TANGGAL</th>
                        <th>JAM</th>
                        <th>LOKASI</th>
                        <th>KATEGORI</th>
                        <th>STATUS</th> 
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>
                            <div class="event-foto-preview slideshow-container">
                                @if($event->galeri && $event->galeri->isNotEmpty())
                                    @foreach($event->galeri as $index => $foto)
                                        <img src="{{ asset('storage/' . $foto->path) }}" 
                                             alt="Foto Event" 
                                             class="{{ $index === 0 ? 'active' : '' }}">
                                    @endforeach
                                @else
                                    <div style="display:flex; align-items:center; justify-content:center; width:100%; height:100%; background:#f3f4f6; color:#9ca3af;">
                                        <i class="fas fa-image" style="font-size:1.1rem;"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td style="font-weight:600">{{ $event->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d M Y') }}</td>
                        <td>{{ $event->jam ? \Carbon\Carbon::parse($event->jam)->format('H:i') : '-' }} WIB</td>
                        <td>{{ \Illuminate\Support\Str::limit($event->lokasi, 25) }}</td>
                        <td>{{ $event->kategori }}</td>
                        <td>
                            @if(strtolower($event->status) == 'selesai')
                                <span class="badge-selesai">Selesai</span>
                            @else
                                <span class="badge-belum-selesai">Belum Selesai</span>
                            @endif
                        </td>
                        <td class="event-actions">
                            <a href="{{ route('admin.edit-event', $event->id) }}" class="btn-edit-event">Edit</a>
                            <button class="btn-hapus-event" onclick="confirmDelete({{ $event->id }})">Hapus</button>
                            <form id="delete-form-{{ $event->id }}" action="{{ route('admin.hapus-event', $event->id) }}" method="POST" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center;">Belum ada data event yang sesuai kriteria.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="event-pagination">
            @if($events->total() > 0)
                <p>Menampilkan {{ $events->firstItem() }} - {{ $events->lastItem() }} dari {{ $events->total() }} event</p>
            @else
                <p>Menampilkan 0 event</p>
            @endif
            
            <div class="event-page-controls">
                @if ($events->onFirstPage())
                    <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $events->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                @endif

                <span class="active">{{ $events->currentPage() }}</span>

                @if ($events->hasMorePages())
                    <a href="{{ $events->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if(confirm('Yakin ingin menghapus event ini?')) {
            document.getElementById('delete-form-'+id).submit();
        }
    }
    
    function openFilterModal() {
        document.getElementById('filterModal').style.display = 'flex';
    }

    function closeFilterModal() {
        document.getElementById('filterModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('filterModal');
        if (event.target == modal) {
            closeFilterModal();
        }
    }

    function syncCardVisual(checkboxElement) {
        let parentCard = checkboxElement.closest('.filter-checkbox-card');
        if (checkboxElement.checked) {
            parentCard.classList.add('selected-box');
        } else {
            parentCard.classList.remove('selected-box');
        }
    }

    function submitFormFilter() {
        document.getElementById('formFilterEvent').submit();
    }

    document.getElementById('searchEvent').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitFormFilter();
        }
    });

    // AUTOMATIC SLIDESHOW MULTI-FOTO EVENT (5 DETIK)
    document.addEventListener("DOMContentLoaded", function() {
        const containers = document.querySelectorAll('.slideshow-container');
        containers.forEach(container => {
            const images = container.querySelectorAll('img');
            if (images.length > 1) {
                let currentIndex = 0;
                setInterval(() => {
                    images[currentIndex].classList.remove('active');
                    currentIndex = (currentIndex + 1) % images.length;
                    images[currentIndex].classList.add('active');
                }, 5000);
            }
        });
    });
</script>
@endsection