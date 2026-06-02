@extends('layouts.umum')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Styling Banner */
    .katalog-banner {
        width: 100%;
        height: 350px;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('img/images-landing-page/banner_katalog.jpeg') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 8%;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .katalog-wrapper {
        width: 95%; 
        max-width: 1400px;
        margin: 40px auto;
        font-family: 'Poppins', sans-serif;
    }

    /* Grid 4 Kolom Responsive */
    .katalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
        gap: 25px;
        margin-bottom: 40px;
    }

    .katalog-card {
        background: #fff;
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: 0.3s;
        border: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .katalog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .search-filter-wrapper {
        background: transparent; 
        padding: 20px 0; 
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .search-box-container {
        flex: 1;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border-radius: 10px; 
        border: 1px solid #ccc;
        background: white; 
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        outline: none;
    }

    .search-box-container .fa-search {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #C0392B;
        cursor: pointer;
        font-size: 1.1rem;
    }

    .btn-trigger-filter {
        padding: 15px 35px; 
        background: #C0392B; 
        color: white; 
        border: none; 
        border-radius: 10px; 
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .btn-trigger-filter:hover {
        background: #A93226;
    }

    /* BADGE STATUS CUSTOM */
    .status-tersedia { color: #2ecc71; }
    .status-kosong { color: #e74c3c; }

    /* POPUP MODAL FILTER STYLE */
    .custom-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); display: none; justify-content: center; align-items: center; z-index: 9999;
    }
    .custom-modal-content {
        background: #fff; width: 100%; max-width: 480px; border-radius: 20px; padding: 30px;
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
    .filter-checkbox-card input[type="checkbox"] { accent-color: #C0392B; width: 17px; height: 17px; cursor: pointer; }
    .filter-checkbox-card.selected-box { border-color: #C0392B; background-color: #FFF5F5; }
    .filter-checkbox-card.selected-box .checkbox-label { font-weight: 600; color: #C0392B; }
    .checkbox-label { font-size: 0.85rem; color: #4B5563; cursor: pointer; }
    
    .custom-modal-footer { display: flex; gap: 15px; margin-top: 25px; }
    .btn-modal-batal, .btn-modal-terapkan {
        flex: 1; padding: 12px; border-radius: 10px; font-family: 'Poppins', sans-serif;
        font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: 0.2s;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-modal-batal { background: #E5E7EB; color: #374151; }
    .btn-modal-batal:hover { background: #D1D5DB; }
    .btn-modal-terapkan { background: #C0392B; color: white; }
    .btn-modal-terapkan:hover { background: #A93226; }

    /* UNIFIED PUBLIC PAGINATION STYLES */
    .news-pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 35px; }
    .news-pagination p { font-size: 0.85rem; color: #7f8c8d; margin: 0; font-weight: 500; }
    .news-page-controls { display: flex; gap: 8px; }
    .news-page-controls a, .news-page-controls span {
        display: inline-flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 8px; border: 1px solid #e8dede;
        background: #fff; color: #333; font-size: 0.85rem; text-decoration: none; transition: 0.2s;
    }
    .news-page-controls a:hover { background: #FFF5F5; border-color: #C0392B; color: #C0392B; }
    .news-page-controls span.active { background: #C0392B; color: #fff; border-color: #C0392B; font-weight: 700; }
    .news-page-controls span.disabled { color: #ccc; cursor: not-allowed; background: #fdfdfd; }

    @keyframes popupSlightIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<div class="katalog-banner">
    <div style="background: #C0392B; padding: 5px 15px; border-radius: 5px; width: fit-content; margin-bottom: 15px; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
        Dokumentasi
    </div>
    <h1 style="font-size: 3rem; margin: 0; font-weight: 700;">Katalog Sanggar</h1>
    <p style="font-size: 1.2rem; opacity: 0.9;">Momen indah perjalanan seni dan Budaya Sanggar Tari Jiwa Etnik Blambangan</p>
</div>

<div class="katalog-wrapper">
    <div class="section-header" style="margin-bottom: 15px;">
        <h2 style="font-weight: 700; color: #2c3e50; margin-bottom: 5px;">Katalog Kostum</h2>
        <p style="color: #7f8c8d; margin: 0;">Koleksi Kostum tari tradisional dan kreasi Sanggar JEB</p>
    </div>

    {{-- INTEGRATED SEARCH AND FILTER COMPONENT --}}
    <form action="{{ route('umum.katalog') }}" method="GET" id="formPublicKatalog">
        <div class="search-filter-wrapper">
            <div class="search-box-container">
                <input type="text" name="search" id="searchPublicKatalog" class="search-input" placeholder="Cari nama tari, deskripsi baju, atau perlengkapan kostum..." value="{{ request('search') }}">
                <i class="fas fa-search" onclick="submitPublicForm()"></i>
            </div>
            <button type="button" class="btn-trigger-filter" onclick="openFilterModal()">
                <i class="fas fa-filter"></i> Filter Kategori
            </button>
        </div>

        {{-- MULTI-SELECT CATEGORY CHECKBOX MODAL POPUP --}}
        <div id="filterModal" class="custom-modal-overlay">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h3>Filter Jenis Baju Tari</h3>
                    <p>Saring koleksi berdasarkan jenis klasifikasi tari</p>
                </div>
                <hr class="modal-divider">

                <div class="custom-modal-body">
                    <div class="filter-group-section">
                        <label class="section-title">Kategori Kostum</label>
                        <div class="checkbox-grid">
                            @foreach(['Tradisional', 'Kreasi'] as $kat)
                                <label class="filter-checkbox-card {{ in_array($kat, request('kategori', [])) ? 'selected-box' : '' }}">
                                    <input type="checkbox" name="kategori[]" value="{{ $kat }}" onchange="syncCardVisual(this)" {{ in_array($kat, request('kategori', [])) ? 'checked' : '' }}> 
                                    <span class="checkbox-label">{{ $kat }}</span>
                                </label>
                            @endforeach
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

    <div class="katalog-grid">
        @forelse($katalogs as $item)
            @php
                // Tentukan gambar default sebagai cadangan jika tidak ada foto
                $fotoPath = 'img/images-landing-page/banner_katalog.jpeg'; 
                
                if ($item->galeri) {
                    if ($item->galeri instanceof \Illuminate\Support\Collection) {
                        $fotoPath = $item->galeri->first()->path ?? $fotoPath;
                    } else {
                        $fotoPath = $item->galeri->path ?? $fotoPath;
                    }
                }
            @endphp

            <a href="{{ route('umum.katalog-detail', $item->id) }}" style="text-decoration: none; color: inherit;">
                <div class="katalog-card">
                    <img src="{{ asset('storage/' . $fotoPath) }}" alt="{{ $item->nama_tari }}" style="width:100%; height: 180px; object-fit: cover; border-radius:8px;" onerror="this.onerror=null; this.src='{{ asset('img/images-landing-page/banner_katalog.jpeg') }}';">
                    <div style="padding: 12px 0 4px 0; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <span style="font-size: 10px; background: #f1c40f; color: #2c3e50; font-weight: 700; padding: 3px 8px; border-radius: 4px; text-transform: uppercase;">{{ $item->kategori }}</span>
                            <h4 style="margin: 10px 0 6px 0; font-size: 1.1rem; font-weight: 700; color: #2c3e50;">{{ $item->nama_tari }}</h4>
                            <p style="font-size: 13px; color: #666; line-height: 1.5; margin-bottom: 12px;">{{ Str::limit($item->deskripsi, 85) }}</p>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; font-weight: 700; border-top: 1px solid #f9f9f9; padding-top: 8px;">
                            <span class="{{ $item->status === 'tersedia' ? 'status-tersedia' : 'status-kosong' }}">
                                <i class="fas {{ $item->status === 'tersedia' ? 'fa-check-circle' : 'fa-times-circle' }}"></i> {{ ucfirst($item->status) }}
                            </span>
                            <span style="color: #7f8c8d; font-weight: 500;">Stok: <strong style="color:#2c3e50;">{{ $item->stok }}</strong></span>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div style="grid-column: span 4; text-align: center; color: #7f8c8d; padding: 60px 0;">
                <i class="fas fa-tshirt" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 15px; display: block;"></i>
                Tidak ada koleksi baju atau kostum tari yang sesuai dengan kriteria pencarian saat ini.
            </div>
        @endforelse
    </div>

    {{-- INTERACTIVE PAGINATION LINK DISPLAY --}}
    <div class="news-pagination">
        @if($katalogs->total() > 0)
            <p>Menampilkan {{ $katalogs->firstItem() }} - {{ $katalogs->lastItem() }} dari {{ $katalogs->total() }} item kostum</p>
        @else
            <p>Menampilkan 0 item kostum</p>
        @endif
        
        <div class="news-page-controls">
            @if($katalogs->hasPages())
                {{-- Sebelumnya --}}
                @if ($katalogs->onFirstPage())
                    <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $katalogs->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                @endif

                {{-- Render Angka Halaman --}}
                @foreach ($katalogs->getUrlRange(1, $katalogs->lastPage()) as $page => $url)
                    @if ($page == $katalogs->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Selanjutnya --}}
                @if ($katalogs->hasMorePages())
                    <a href="{{ $katalogs->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            @endif
        </div>
    </div>
</div>

<script>
    function openFilterModal() {
        document.getElementById('filterModal').style.display = 'flex';
    }

    // Penjagaan Modal Keluar
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

    function submitPublicForm() {
        document.getElementById('formPublicKatalog').submit();
    }

    document.getElementById('searchPublicKatalog').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitPublicForm();
        }
    });
</script>
@endsection