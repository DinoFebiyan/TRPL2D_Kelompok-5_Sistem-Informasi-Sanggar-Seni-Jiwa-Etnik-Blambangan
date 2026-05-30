@extends('layouts.superadmin')

@section('title', 'Kelola Event — JEB')
@section('header_title', 'Kelola Event')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .event-wrapper {
        width: 100%;
        font-family: 'Poppins', sans-serif;
        color: #333;
    }
    
    .event-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 24px;
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
    }
    .event-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .event-icon-blue { background: #eff6ff; color: #3b82f6; }
    .event-icon-green { background: #f0fdf4; color: #16a34a; }
    .event-icon-red { background: #fef2f2; color: #ef4444; }
    .event-stat-text h4 {
        margin: 0;
        font-size: 0.7rem;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
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
    }
    .event-btn-tambah:hover {
        background: #d4aeae;
    }
    .event-search-row {
        display: flex;
        gap: 15px;
        margin-bottom: 24px;
    }
    .event-search-box {
        flex: 1;
        position: relative;
    }
    .event-search-box input {
        width: 100%;
        padding: 14px 20px;
        border-radius: 12px;
        border: 1px solid #e8dede;
        outline: none;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
    }
    .event-search-box .fa-search {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #eab308;
        font-size: 1.2rem;
        cursor: pointer;
    }
    .event-btn-filter {
        background: #b91c1c;
        color: #fff;
        border: none;
        padding: 0 35px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    .event-table-card {
        background: #fff;
        border-radius: 12px;
        padding: 25px 0; 
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        overflow-x: auto;
    }
    .event-table-card h3 {
        margin: 0 0 20px 0;
        padding: 0 25px; 
        font-size: 1.1rem;
        color: #333;
    }
    .event-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }
    .event-table thead {
        background-color: #F5EBEB; 
    }
    .event-table th {
        text-align: left;
        padding: 14px 25px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: none;
        text-transform: uppercase; 
    }
    .event-table td {
        padding: 15px 25px;
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
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-belum-selesai {
        background: #fef08a;
        color: #a16207;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .event-actions a, .event-actions button {
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 5px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }
    .btn-edit-event { background: #eff6ff; color: #3b82f6; }
    .btn-hapus-event { background: #fef2f2; color: #ef4444; }
    
    .event-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding: 0 25px;
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
        background: #fff; width: 100%; max-width: 480px;
        border-radius: 24px; padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        animation: popupSlightIn 0.3s ease-out;
    }
    .custom-modal-header h3 { margin: 0; font-size: 1.3rem; font-weight: 700; color: #2D1A1A; }
    .custom-modal-header p { margin: 4px 0 0 0; font-size: 0.85rem; color: #888; }
    .modal-divider { border: 0; border-top: 1px solid #E5E7EB; margin: 18px 0; }
    .section-title { font-size: 0.95rem; font-weight: 600; color: #333; display: block; margin-bottom: 12px; }
    .checkbox-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .filter-checkbox-card {
        border: 1px solid #D1D5DB; border-radius: 12px; padding: 12px 15px;
        display: flex; align-items: center; gap: 10px; cursor: pointer;
        transition: all 0.2s; background: #fff; user-select: none;
    }
    .filter-checkbox-card:hover { border-color: #b07d7d; background: #fffbfb; }
    .filter-checkbox-card input[type="checkbox"] { accent-color: #EF4444; width: 17px; height: 17px; cursor: pointer; }
    .filter-checkbox-card.selected-box { border-color: #EF4444; background-color: #FFF5F5; }
    .filter-checkbox-card.selected-box .checkbox-label { font-weight: 600; color: #EF4444; }
    .checkbox-label { font-size: 0.85rem; color: #4B5563; cursor: pointer; }
    .custom-modal-footer { display: flex; gap: 15px; margin-top: 25px; }
    .btn-modal-batal, .btn-modal-terapkan {
        flex: 1; padding: 12px; border-radius: 10px; font-family: 'Poppins', sans-serif;
        font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: 0.2s;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-modal-batal { background: #E5E7EB; color: #374151; }
    .btn-modal-batal:hover { background: #D1D5DB; }
    .btn-modal-terapkan { background: #EF4444; color: white; }
    .btn-modal-terapkan:hover { background: #DC2626; }

    .adm-btn-tambah {
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
        transition: all 0.2s;
        border: 1px solid #e8dede;
    }
    .adm-btn-tambah:hover {
        background: #d4aeae;
    }
.adm-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
 .adm-icon-dark-red { background-color: #7a2a214a; color: #411f1f; }


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
        <a href="{{ route('superadmin.tambah-event') }}" class="adm-btn-tambah">
            <div class="adm-icon-box adm-icon-dark-red">
                <i class="fas fa-plus"></i>
            </div>
            <span>Tambah Event</span>
        </a>
    </div>

    {{-- INTEGRATED FORM GET --}}
    <form action="{{ route('superadmin.kelola-event') }}" method="GET" id="formFilterEvent">
        <div class="event-search-row">
            <div class="event-search-box">
                <input type="text" name="search" id="searchEvent" placeholder="Cari berdasarkan nama, lokasi, kategori, dll..." value="{{ request('search') }}">
                <i class="fas fa-search" onclick="submitFormFilter()"></i>
            </div>
            <button type="button" class="event-btn-filter" onclick="openFilterModal()"><i class="fas fa-filter"></i> Filter</button>
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
                    <td>{{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ $event->jam ? \Carbon\Carbon::parse($event->jam)->format('H:i') : '-' }} WIB</td>
                    <td>{{ $event->lokasi }}</td>
                    <td>{{ $event->kategori }}</td>
                    <td>
                        @if(strtolower($event->status) == 'selesai')
                            <span class="badge-selesai">Selesai</span>
                        @else
                            <span class="badge-belum-selesai">Belum Selesai</span>
                        @endif
                    </td>
                    <td class="event-actions">
                        <a href="{{ route('superadmin.edit-event', $event->id) }}" class="btn-edit-event">Edit</a>
                        <button class="btn-hapus-event" onclick="confirmDelete({{ $event->id }})">Hapus</button>
                        <form id="delete-form-{{ $event->id }}" action="{{ route('superadmin.hapus-event', $event->id) }}" method="POST" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;">Belum ada data event yang sesuai kriteria.</td></tr>
                @endforelse
            </tbody>
        </table>
        
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

    // Perbaikan typo fungsi penutup modal window click
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