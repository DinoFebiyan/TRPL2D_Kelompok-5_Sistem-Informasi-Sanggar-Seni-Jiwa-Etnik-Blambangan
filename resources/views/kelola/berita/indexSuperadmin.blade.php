@extends('layouts.superadmin')

@section('title', 'Kelola Berita — JEB')
@section('header_title', 'Kelola Berita')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    
    html, body {
        max-width: 100vw;
        overflow-x: hidden;
    }

    .main, .content {
        min-width: 0 !important;
        max-width: 100vw !important;
        overflow-x: hidden !important;
    }

    .news-wrapper {
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
    .news-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }
    .news-stat-card {
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
    .news-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .news-icon-blue { background: #eff6ff; color: #3b82f6; }
    .news-icon-green { background: #dcfce7; color: #16a34a; }
    .news-icon-red { background: #fef2f2; color: #ef4444; }
    .adm-icon-dark-red { background-color: #7a2a214a; color: #411f1f; }
    
    .news-stat-text {
        min-width: 0;
        flex: 1;
    }
    .news-stat-text h4 {
        margin: 0;
        font-size: 0.7rem;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .news-stat-text h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
    }
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
        border: 1px solid #e8dede;
        transition: 0.2s;
        min-width: 0;
    }
    .adm-btn-tambah:hover {
        background: #d4aeae;
    }

    /* ==========================================
       3. SEARCH & FILTER ROW (DIPAKSA 1 BARIS)
       ========================================== */
    .news-search-row {
        display: flex;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        gap: 15px;
        margin-bottom: 24px;
        align-items: center;
        width: 100%;
        box-sizing: border-box;
    }
    .news-search-box {
        flex: 1 1 0%;
        min-width: 0;
        position: relative;
    }
    .news-search-box input {
        width: 100%;
        min-width: 0;
        padding: 12px 35px 12px 15px;
        border-radius: 10px;
        border: 1px solid #e8dede;
        outline: none;
        font-size: 0.95rem;
        background: #fff;
        box-sizing: border-box;
    }
    .news-search-box .fa-search {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #eab308;
        cursor: pointer;
    }
    .news-btn-filter {
        flex: 0 0 auto;
        background: #b91c1c;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        white-space: nowrap;
    }

    /* ==========================================
       4. TABEL RESPONSIF (ANTI BOCOR)
       ========================================== */
    .news-table-card {
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
    .news-table-card h3 {
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
        padding-bottom: 15px; 
    }
    
    .news-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px; /* Batas minimum lebar tabel */
    }
    .news-table thead {
        background-color: #F5EBEB; 
    }
    .news-table th {
        text-align: left;
        padding: 14px 25px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: none;
        text-transform: uppercase; 
        white-space: nowrap;
    }
    .news-table td {
        padding: 15px 25px;
        border-bottom: 1px solid #E0E0E0; 
        vertical-align: middle;
        font-size: 0.85rem;
    }
    .news-foto {
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
    .news-foto img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0; opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    .news-foto img.active {
        opacity: 1;
    }
    .badge-tayang {
        background: #dcfce7;
        color: #16a34a;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-tidak-tayang {
        background: #fee2e2;
        color: #ef4444;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .news-actions {
        white-space: nowrap;
    }
    .news-actions a, .news-actions button {
        border: none;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 5px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }
    .btn-edit-news { background: #eff6ff; color: #3b82f6; }
    .btn-hapus-news { background: #fef2f2; color: #ef4444; }

    /* ==========================================
       5. PAGINATION (LEBIH LEGA)
       ========================================== */
    .news-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 35px; /* Jarak aman dari scrollbar tabel */
        padding: 10px 5px 0 5px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .news-pagination p {
        font-size: 0.85rem;
        color: #b07d7d;
        margin: 0;
        font-weight: 500;
    }
    .news-page-controls {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .news-page-controls a, .news-page-controls span {
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
    .news-page-controls a:hover {
        background: #f5ebeb;
        border-color: #d4aeae;
    }
    .news-page-controls span.active {
        background: #5B1A1A;
        color: #fff;
        border-color: #5B1A1A;
        font-weight: 700;
    }
    .news-page-controls span.disabled {
        color: #ccc;
        cursor: not-allowed;
        background: #fdfdfd;
    }

    /* MODAL POPUP COMPONENT STYLE */
    .custom-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.4); display: none; justify-content: center; align-items: center; z-index: 9999;
    }
    .custom-modal-content {
        background: #fff; width: 90%; max-width: 480px; border-radius: 24px; padding: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15); animation: popupSlightIn 0.3s ease-out;
    }
    .custom-modal-header h3 { margin: 0; font-size: 1.3rem; font-weight: 700; color: #2D1A1A; }
    .custom-modal-header p { margin: 4px 0 0 0; font-size: 0.85rem; color: #888; }
    .modal-divider { border: 0; border-top: 1px solid #E5E7EB; margin: 18px 0; }
    .section-title { font-size: 0.95rem; font-weight: 600; color: #333; display: block; margin-bottom: 12px; }
    .checkbox-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .filter-checkbox-card {
        border: 1px solid #D1D5DB; border-radius: 12px; padding: 12px 15px; display: flex; align-items: center; gap: 10px;
        cursor: pointer; transition: all 0.2s; background: #fff; user-select: none;
    }
    .filter-checkbox-card:hover { border-color: #b07d7d; background: #fffbfb; }
    .filter-checkbox-card input[type="checkbox"] { accent-color: #EF4444; width: 17px; height: 17px; }
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

    /* ==========================================
       6. MEDIA QUERIES UNTUK HP (MOBILE)
       ========================================== */
    @media (max-width: 640px) {
        /* GRID STATISTIK 2x2 */
        .news-stats-grid {
            grid-template-columns: repeat(2, 1fr) !important; 
            gap: 10px !important;
            width: 100% !important;
        }
        .news-stat-card {
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
        .news-icon-box {
            width: 35px !important; height: 35px !important; font-size: 1rem !important;
        }
        .news-stat-text { width: 100% !important; }
        .news-stat-text h2 { font-size: 1.25rem !important; }
        .news-stat-text h4 { font-size: 0.65rem !important; }
        
        .adm-btn-tambah {
            grid-column: span 2 !important; 
            padding: 12px !important;
            font-size: 0.9rem !important;
            flex-direction: row !important;
            width: 100% !important;
        }

        /* MEMAKSA SEARCH DAN FILTER 1 BARIS DI HP */
        .news-search-row {
            display: flex !important;
            flex-direction: row !important; 
            flex-wrap: nowrap !important;
            gap: 8px !important;
            width: 100% !important;
        }
        .news-search-box {
            flex: 1 1 0% !important;
        }
        .news-search-box input {
            padding: 10px 30px 10px 12px !important;
            font-size: 0.8rem !important;
            min-width: 0 !important;
            width: 100% !important;
            height: 42px !important;
        }
        .news-search-box .fa-search {
            right: 10px !important;
            font-size: 0.9rem !important;
        }
        
        /* TOMBOL FILTER DIKUNCI JADI KOTAK PRESISI DI HP */
        .news-btn-filter {
            flex: 0 0 42px !important; 
            height: 42px !important; 
            padding: 0 !important; 
            font-size: 1rem !important;
            justify-content: center !important;
        }
        .news-btn-filter span { display: none !important; } 

        /* PERBAIKAN TABEL CARD DI HP */
        .news-table-card {
            padding: 15px 10px !important; 
            width: 100% !important;
        }
        
        .news-pagination {
            flex-direction: column !important;
            gap: 12px !important;
            margin-top: 25px !important;
        }
    }

    @keyframes popupSlightIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<div class="news-wrapper">
    <div class="news-stats-grid">
        <div class="news-stat-card">
            <div class="news-icon-box news-icon-blue"><i class="fas fa-newspaper"></i></div>
            <div class="news-stat-text">
                <h4>Total Berita</h4>
                <h2>{{ $totalBerita ?? 0 }}</h2>
            </div>
        </div>
        <div class="news-stat-card">
            <div class="news-icon-box news-icon-green"><i class="fas fa-check-circle"></i></div>
            <div class="news-stat-text">
                <h4>Ditayangkan</h4>
                <h2>{{ $ditayangkan ?? 0 }}</h2>
            </div>
        </div>
        <div class="news-stat-card">
            <div class="news-icon-box news-icon-red"><i class="fas fa-ban"></i></div>
            <div class="news-stat-text">
                <h4>Tidak Ditayangkan</h4>
                <h2>{{ $tidakDitayangkan ?? 0 }}</h2>
            </div>
        </div>
        <a href="{{ route('superadmin.tambah-berita') }}" class="adm-btn-tambah">
            <div class="news-icon-box adm-icon-dark-red">
                <i class="fas fa-plus"></i>
            </div>
            <span>Tambah Berita</span>
        </a>
    </div>

    {{-- INTEGRATED FORM ACTION GET --}}
    <form action="{{ route('superadmin.kelola-berita') }}" method="GET" id="formFilterNews">
        <div class="news-search-row">
            <div class="news-search-box">
                <input type="text" name="search" id="searchBerita" placeholder="Cari judul atau penulis..." value="{{ request('search') }}">
                <i class="fas fa-search" onclick="submitFormFilter()"></i>
            </div>
            <button type="button" class="news-btn-filter" onclick="openFilterModal()">
                <i class="fas fa-filter"></i> <span>Filter</span>
            </button>
        </div>

        {{-- FILTER MODAL POPUP INSIDE FORM --}}
        <div id="filterModal" class="custom-modal-overlay">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h3>Filter Pencarian anda</h3>
                    <p>Sesuaikan apa yang ingin anda tampilkan</p>
                </div>
                <hr class="modal-divider">

                <div class="custom-modal-body">
                    <div class="filter-group-section">
                        <label class="section-title">Status Tayang</label>
                        <div class="checkbox-grid">
                            <label class="filter-checkbox-card {{ in_array('tayang', request('status', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="status[]" value="tayang" onchange="syncCardVisual(this)" {{ in_array('tayang', request('status', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Tayang</span>
                            </label>
                            <label class="filter-checkbox-card {{ in_array('tidak tayang', request('status', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="status[]" value="tidak tayang" onchange="syncCardVisual(this)" {{ in_array('tidak tayang', request('status', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Tidak Ditayangkan</span>
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

    <div class="news-table-card">
        <h3>Daftar Berita</h3>
        
        {{-- BUNGKUSAN SCROLL TABEL (GEMBOK ANTI BOCOR) --}}
        <div class="table-responsive-wrapper">
            <table class="news-table" id="newsTable">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Judul Berita</th>
                        <th>Penulis</th>
                        <th>Tanggal Terbit</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beritas as $berita)
                    <tr>
                        <td>
                            <div class="news-foto slideshow-container">
                                @if($berita->galeri && $berita->galeri->isNotEmpty())
                                    @foreach($berita->galeri as $index => $foto)
                                        <img src="{{ asset('storage/' . $foto->path) }}" 
                                             alt="Foto Berita" 
                                             class="{{ $index === 0 ? 'active' : '' }}">
                                    @endforeach
                                @else
                                    <div style="display:flex; align-items:center; justify-content:center; width:100%; height:100%; background:#f3f4f6; color:#9ca3af;">No Img</div>
                                @endif
                            </div>
                        </td>
                        <td><strong>{{ $berita->judul }}</strong><br>
                            <span style="font-size:0.7rem; color:#888;">{{ \Illuminate\Support\Str::limit(strip_tags($berita->isi_berita), 60) }}</span>
                        </td>
                        <td>{{ $berita->user->name ?? 'Admin' }}</td>
                        <td>{{ $berita->created_at ? $berita->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            @if($berita->status == 'tayang')
                                <span class="badge-tayang">Tayang</span>
                            @else
                                <span class="badge-tidak-tayang">Tidak Ditayangkan</span>
                            @endif
                        </td>
                        <td class="news-actions">
                            <a href="{{ route('superadmin.edit-berita', $berita->id) }}" class="btn-edit-news">Edit</a>
                           <button class="btn-hapus-news" onclick="showPopupHapus('delete-form-{{ $berita->id }}')">Hapus</button>
                            <form id="delete-form-{{ $berita->id }}" action="{{ route('superadmin.hapus-berita', $berita->id) }}" method="POST" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center">Belum ada data berita yang sesuai kriteria.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="news-pagination">
            @if($beritas->total() > 0)
                <p>Menampilkan {{ $beritas->firstItem() }} - {{ $beritas->lastItem() }} dari {{ $beritas->total() }} berita</p>
            @else
                <p>Menampilkan 0 berita</p>
            @endif
            
            <div class="news-page-controls">
                @if ($beritas->onFirstPage())
                    <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $beritas->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                @endif

                <span class="active">{{ $beritas->currentPage() }}</span>

                @if ($beritas->hasMorePages())
                    <a href="{{ $beritas->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
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
        document.getElementById('formFilterNews').submit();
    }

    document.getElementById('searchBerita').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitFormFilter();
        }
    });

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
@include('popup.hapus')
@endsection