@extends('layouts.superadmin')

@section('title', 'Kelola Katalog — JEB')
@section('header_title', 'Kelola Katalog')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* ==========================================
       1. GEMBOK UTAMA (ANTI-BLOWOUT HORIZONTAL)
       ========================================== */
    .main, .content {
        min-width: 0 !important;
        max-width: 100vw !important;
        overflow-x: hidden !important;
    }

    .katalog-wrapper {
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
    .katalog-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }
    .katalog-stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid #f0f0f0;
        min-width: 0;
    }
    .katalog-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .katalog-icon-blue { background: #eff6ff; color: #3b82f6; }
    .katalog-icon-green { background: #f0fdf4; color: #16a34a; }
    .katalog-icon-red { background: #fef2f2; color: #ef4444; }
    .katalog-icon-dark-red { background-color: #7a2a214a; color: #411f1f; }
    
    .katalog-stat-text {
        min-width: 0;
        flex: 1;
    }
    .katalog-stat-text h4 {
        margin: 0;
        font-size: 0.7rem;
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .katalog-stat-text h2 {
        margin: 0;
        font-size: 1.5rem;
        color: #333;
        font-weight: 700;
    }
    .katalog-btn-tambah {
        background: #e2c0c0;
        color: #5a1a1a;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid #e8dede;
        padding: 0 15px;
        min-width: 0;
    }
    .katalog-btn-tambah:hover {
        background: #d4aeae;
    }

    /* ==========================================
       3. SEARCH & FILTER ROW (DIPAKSA 1 BARIS)
       ========================================== */
    .katalog-search-row {
        display: flex;
        flex-direction: row !important; 
        flex-wrap: nowrap !important; 
        gap: 15px;
        margin-bottom: 24px;
        align-items: center;
        width: 100%;
        box-sizing: border-box;
    }
    .katalog-search-box {
        flex: 1 1 0%;
        min-width: 0; 
        position: relative;
    }
    .katalog-search-box input {
        width: 100%;
        min-width: 0;
        padding: 12px 35px 12px 15px;
        border-radius: 10px;
        border: 1px solid #e8dede;
        outline: none;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        background: #fff;
        text-overflow: ellipsis;
        box-sizing: border-box; 
    }
    .katalog-search-box .fa-search {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #eab308;
        font-size: 1rem;
        cursor: pointer;
    }
    .katalog-btn-filter {
        flex: 0 0 auto;
        background: #b91c1c;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
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
    .katalog-table-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid #f0f0f0;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    .katalog-table-card h3 {
        margin: 0 0 16px 0;
        font-size: 1rem;
        color: #333;
    }
    
    .table-responsive-wrapper {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 10px; 
        padding-bottom: 10px;
    }
    .katalog-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px; 
    }
    .katalog-table thead {
        background-color: #F5EBEB;
    }
    .katalog-table th {
        text-align: left;
        padding: 14px 16px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.75rem;
        border-bottom: none;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .katalog-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #E0E0E0;
        vertical-align: middle;
        font-size: 0.8rem;
        color: #333;
    }
    
    .katalog-foto-preview {
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
    .katalog-foto-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0; opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    .katalog-foto-preview img.active {
        opacity: 1;
    }
    
    .badge-tersedia {
        background: #dcfce7;
        color: #16a34a;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-tidak-tersedia {
        background: #fee2e2;
        color: #ef4444;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .katalog-actions {
        white-space: nowrap;
    }
    .katalog-actions a, .katalog-actions button {
        border: none;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 4px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }
    .btn-edit-katalog { background: #eff6ff; color: #3b82f6; }
    .btn-hapus-katalog { background: #fef2f2; color: #ef4444; }

    /* ==========================================
       5. PAGINATION
       ========================================== */
    .katalog-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding: 0 5px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .katalog-pagination p {
        font-size: 0.75rem;
        color: #b07d7d;
        margin: 0;
        font-weight: 500;
    }
    .katalog-page-controls {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .katalog-page-controls a, .katalog-page-controls span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #e8dede;
        background: #fff;
        color: #333;
        font-size: 0.75rem;
        text-decoration: none;
        cursor: pointer;
        transition: 0.2s;
    }
    .katalog-page-controls a:hover {
        background: #f5ebeb;
        border-color: #d4aeae;
    }
    .katalog-page-controls span.active {
        background: #5B1A1A;
        color: #fff;
        border-color: #5B1A1A;
        font-weight: 700;
    }
    .katalog-page-controls span.disabled {
        color: #ccc;
        cursor: not-allowed;
        background: #fdfdfd;
    }

    /* MODAL POPUP COMPONENT STYLE */
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
        font-weight: 600; font-size: 0.85rem; border: none; cursor: pointer; text-align: center;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-modal-batal { background: #E5E7EB; color: #374151; }
    .btn-modal-batal:hover { background: #D1D5DB; }
    .btn-modal-terapkan { background: #EF4444; color: white; }
    .btn-modal-terapkan:hover { background: #DC2626; }

    /* ==========================================
       6. MEDIA QUERIES (MOBILE)
       ========================================== */
    @media (max-width: 640px) {
        
        .katalog-stats-grid {
            grid-template-columns: repeat(2, 1fr) !important; 
            gap: 10px !important;
            width: 100% !important;
        }
        .katalog-stat-card {
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
        .katalog-icon-box {
            width: 35px !important; height: 35px !important; font-size: 1rem !important;
        }
        .katalog-stat-text { width: 100% !important; }
        .katalog-stat-text h2 { font-size: 1.25rem !important; }
        .katalog-stat-text h4 { font-size: 0.65rem !important; }
        
        .katalog-btn-tambah {
            grid-column: span 2 !important; 
            padding: 12px !important;
            font-size: 0.9rem !important;
            flex-direction: row !important;
            width: 100% !important;
        }

        /* MEMAKSA SEARCH DAN FILTER 1 BARIS DI HP */
        .katalog-search-row {
            gap: 8px !important;
            width: 100% !important;
        }
        .katalog-search-box input {
            padding: 10px 30px 10px 12px !important;
            font-size: 0.8rem !important;
            height: 42px !important; /* Disamakan tingginya dengan tombol filter */
        }
        .katalog-search-box .fa-search {
            right: 10px !important;
            font-size: 0.9rem !important;
        }
        
        /* TOMBOL FILTER DIKUNCI JADI KOTAK PRESISI DI HP */
        .katalog-btn-filter {
            flex: 0 0 42px !important; /* Lebar mutlak 42px */
            height: 42px !important; /* Tinggi mutlak 42px */
            padding: 0 !important; /* Hapus padding agar tidak gepeng */
            font-size: 1rem !important;
            justify-content: center !important;
        }
        .katalog-btn-filter span { display: none !important; } 

        /* PERBAIKAN TABEL CARD DI HP */
        .katalog-table-card {
            padding: 15px 10px !important; 
            width: 100% !important;
        }
        
        .katalog-pagination {
            flex-direction: column !important;
            gap: 12px !important;
        }
    }

    @keyframes popupSlightIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

<div class="katalog-wrapper">
    <!-- Grid Statistik diubah strukturnya agar rapi di HP -->
    <div class="katalog-stats-grid">
        <div class="katalog-stat-card">
            <div class="katalog-icon-box katalog-icon-blue"><i class="fas fa-box"></i></div>
            <div class="katalog-stat-text">
                <h4>Total Katalog</h4>
                <h2>{{ $totalKatalog ?? 0 }}</h2>
            </div>
        </div>
        <div class="katalog-stat-card">
            <div class="katalog-icon-box katalog-icon-green"><i class="fas fa-check-circle"></i></div>
            <div class="katalog-stat-text">
                <h4>Tersedia</h4>
                <h2>{{ $tersedia ?? 0 }}</h2>
            </div>
        </div>
        <div class="katalog-stat-card">
            <div class="katalog-icon-box katalog-icon-red"><i class="fas fa-ban"></i></div>
            <div class="katalog-stat-text">
                <h4>Tidak Tersedia</h4>
                <h2>{{ $tidakTersedia ?? 0 }}</h2>
            </div>
        </div>
        <a href="{{ route('superadmin.tambah-katalog') }}" class="katalog-btn-tambah">
            <div class="katalog-icon-box katalog-icon-dark-red">
                <i class="fas fa-plus"></i>
            </div>
            <span>Tambah Katalog</span>
        </a>
    </div>

    <form action="{{ route('superadmin.kelola-katalog') }}" method="GET" id="formFilterKatalog">
        <div class="katalog-search-row">
            <div class="katalog-search-box">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, deskripsi..." id="searchKatalog">
                <i class="fas fa-search" onclick="submitFormFilter()"></i>
            </div>
            <button type="button" class="katalog-btn-filter" onclick="openFilterModal()">
                <i class="fas fa-filter"></i> <span>Filter</span>
            </button>
        </div>

        {{-- POPUP MODAL --}}
        <div id="filterModal" class="custom-modal-overlay">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h3>Filter Pencarian anda</h3>
                    <p>Sesuaikan apa yang ingin anda tampilkan</p>
                </div>
                <hr class="modal-divider">

                <div class="custom-modal-body">
                    <div class="filter-group-section">
                        <label class="section-title">Status</label>
                        <div class="checkbox-grid">
                            <label class="filter-checkbox-card {{ in_array('tersedia', request('status', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="status[]" value="tersedia" onchange="syncCardVisual(this)" {{ in_array('tersedia', request('status', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Tersedia</span>
                            </label>
                            <label class="filter-checkbox-card {{ in_array('tidak tersedia', request('status', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="status[]" value="tidak tersedia" onchange="syncCardVisual(this)" {{ in_array('tidak tersedia', request('status', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Tidak Tersedia</span>
                            </label>
                        </div>
                    </div>

                    <hr class="modal-divider">

                    <div class="filter-group-section">
                        <label class="section-title">Kategori</label>
                        <div class="checkbox-grid">
                            <label class="filter-checkbox-card {{ in_array('Kostum', request('kategori', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="kategori[]" value="Kostum" onchange="syncCardVisual(this)" {{ in_array('Kostum', request('kategori', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Kostum Tari</span>
                            </label>
                            <label class="filter-checkbox-card {{ in_array('Properti', request('kategori', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="kategori[]" value="Properti" onchange="syncCardVisual(this)" {{ in_array('Properti', request('kategori', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Properti</span>
                            </label>
                            <label class="filter-checkbox-card {{ in_array('Alat Musik', request('kategori', [])) ? 'selected-box' : '' }}" style="grid-column: span 2;">
                                <input type="checkbox" name="kategori[]" value="Alat Musik" onchange="syncCardVisual(this)" {{ in_array('Alat Musik', request('kategori', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Alat Musik</span>
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

    <div class="katalog-table-card">
        <h3 style="margin-left: 10px;">Daftar Katalog</h3>
        
        <div class="table-responsive-wrapper">
            <table class="katalog-table" id="katalogTable">
                <thead>
                    <tr>
                        <th>FOTO</th>
                        <th>NAMA</th>
                        <th>KATEGORI</th>
                        <th>DESKRIPSI</th>
                        <th>STOK</th>
                        <th>STATUS</th> 
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($katalogs as $katalog)
                    <tr>
                        <td>
                            <div class="katalog-foto-preview slideshow-container">
                                @if($katalog->getRelation('galeri') && $katalog->galeri->isNotEmpty())
                                    @foreach($katalog->galeri as $index => $foto)
                                        <img src="{{ asset('storage/' . $foto->path) }}" alt="Foto Produk" class="{{ $index === 0 ? 'active' : '' }}">
                                    @endforeach
                                @else
                                    <div style="display:flex; align-items:center; justify-content:center; width:100%; height:100%; background:#f3f4f6; color:#9ca3af;">
                                        <i class="fas fa-image" style="font-size:1.1rem;"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td style="font-weight:600">{{ $katalog->nama_katalog }}</td>
                        <td>{{ $katalog->kategori }}</td>
                        <td>{{ Str::limit($katalog->deskripsi, 60) }}</td>
                        <td><strong style="color: #333;">{{ $katalog->stok }}</strong></td>
                        <td>
                            @if($katalog->status == 'tersedia')
                                <span class="badge-tersedia">Tersedia</span>
                            @else
                                <span class="badge-tidak-tersedia">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td class="katalog-actions">
                            <a href="{{ route('superadmin.edit-katalog', $katalog->id) }}" class="btn-edit-katalog">Edit</a>
                            <button class="btn-hapus-katalog" onclick="confirmDelete({{ $katalog->id }})">Hapus</button>
                            <form id="delete-form-{{ $katalog->id }}" action="{{ route('superadmin.hapus-katalog', $katalog->id) }}" method="POST" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;">Belum ada data katalog yang sesuai kriteria.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="katalog-pagination">
            @if($katalogs->total() > 0)
                <p>Menampilkan {{ $katalogs->firstItem() }} - {{ $katalogs->lastItem() }} dari {{ $katalogs->total() }} katalog</p>
            @else
                <p>Menampilkan 0 katalog</p>
            @endif
            
            <div class="katalog-page-controls">
                @if ($katalogs->onFirstPage())
                    <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $katalogs->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                @endif

                <span class="active">{{ $katalogs->currentPage() }}</span>

                @if ($katalogs->hasMorePages())
                    <a href="{{ $katalogs->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if(confirm('Yakin ingin menghapus katalog ini?')) {
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
        document.getElementById('formFilterKatalog').submit();
    }

    document.getElementById('searchKatalog').addEventListener('keypress', function(e) {
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
@endsection