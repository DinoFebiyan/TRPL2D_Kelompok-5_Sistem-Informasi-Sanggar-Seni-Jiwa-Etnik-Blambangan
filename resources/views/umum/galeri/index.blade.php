@extends('layouts.umum')

@section('title', 'Galeri Sanggar - Jiwa Etnik Blambangan')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* ══ GLOBAL PUBLIC WRAPPER ══ */
    .public-page-wrapper {
        background-color: #f4eae6;
        width: 100%;
        min-height: 100vh;
        position: relative;
        padding-bottom: 80px;
    }

    /* ══ 1. HERO BANNER ATAS DINAMIS (SLIDESHOW) ══ */
    .berita-banner-container {
        width: 100%;
        height: 380px;
        position: relative;
        overflow: hidden;
        background: #333;
    }

    .banner-slideshow-wrapper {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: 1;
    }

    .banner-slideshow-wrapper img, 
    .banner-slideshow-wrapper .banner-error-fallback {
        width: 100%; height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
    }

    .banner-slideshow-wrapper img.active,
    .banner-slideshow-wrapper .banner-error-fallback.active {
        opacity: 1;
    }

    .banner-overlay-dark {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5));
        z-index: 2;
    }

    .berita-banner-content {
        position: relative;
        z-index: 3;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 8%;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .banner-error-fallback {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #2c3e50;
    }
    .banner-error-fallback.active { 
        display: flex; 
    }

    .badge-theme {
        background-color: #C0392B;
        color: white;
        padding: 6px 16px;
        border-radius: 4px;
        width: fit-content;
        margin-bottom: 15px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ══ 2. CONTENT CONTAINER WRAPPER ══ */
    .galeri-wrapper {
        width: 90%;
        max-width: 1300px;
        margin: 40px auto;
        font-family: 'Poppins', sans-serif;
    }

    /* ══ 3. SEARCH & FILTER ROW BAR ══ */
    .search-filter-wrapper {
        background: transparent;
        padding: 20px 0;
        display: flex;
        gap: 15px;
        align-items: stretch;
        position: relative;
        width: 100%;
        max-width: 100%;
    }

    .search-box-container {
        flex: 1;
        position: relative;
        min-width: 0;
    }

    /* Icon search di KIRI */
    .search-box-container .fa-search {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #C0392B;
        cursor: pointer;
        font-size: 1.1rem;
        z-index: 2;
    }

    /* Tinggi seragam untuk search input & tombol filter */
    .search-input,
    .btn-trigger-filter {
        height: 52px;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        border-radius: 10px;
    }

    .search-input {
        width: 100%;
        padding: 0 20px 0 50px;
        border: 1px solid #ddd;
        background: white;
        outline: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        line-height: 50px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .search-input:focus {
        border-color: #C0392B;
        box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.1);
    }

    .search-input::placeholder {
        color: #999;
        font-size: 0.95rem;
    }

    .btn-trigger-filter {
        padding: 0 25px;
        background: #C0392B;
        color: white;
        border: none;
        cursor: pointer;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: background 0.2s;
        white-space: nowrap;
        flex-shrink: 0;
        box-shadow: 0 2px 10px rgba(192, 57, 43, 0.2);
    }

    .btn-trigger-filter:hover {
        background: #A93226;
    }

    /* ══ RESPONSIVE SEARCH & FILTER ══ */
    @media (max-width: 768px) {
        .search-filter-wrapper {
            flex-direction: column;
            gap: 10px;
            max-width: 100%;
        }

        .search-input,
        .btn-trigger-filter {
            height: 48px;
            font-size: 0.88rem;
        }

        .search-input {
            line-height: 46px;
            padding: 0 16px 0 45px;
        }

        .search-input::placeholder {
            font-size: 0.88rem;
        }

        .search-box-container .fa-search {
            left: 16px;
            font-size: 1rem;
        }

        .btn-trigger-filter {
            width: 100%;
            padding: 0 16px;
        }
    }

    @media (max-width: 480px) {
        .search-input,
        .btn-trigger-filter {
            height: 44px;
            font-size: 0.82rem;
        }

        .search-input {
            line-height: 42px;
            padding: 0 14px 0 42px;
        }

        .search-input::placeholder {
            font-size: 0.82rem;
        }

        .search-box-container .fa-search {
            left: 14px;
            font-size: 0.95rem;
        }

        .btn-trigger-filter {
            padding: 0 14px;
        }
    }

    /* ══ 4. GRID GALERI FOTO ══ */
    .galeri-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .galeri-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        aspect-ratio: 4 / 3;
        position: relative;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #f0f0f0;
    }

    .galeri-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .galeri-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .inline-error-fallback {
        width: 100%;
        height: 100%;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #e5e7eb;
        color: #9ca3af;
        position: absolute;
        top: 0; left: 0;
    }

    .inline-error-fallback.active {
        display: flex;
    }

    .galeri-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        padding: 15px;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 5;
    }

    .galeri-card:hover .galeri-overlay {
        opacity: 1;
    }

    /* ══ 5. FILTER POPUP MODAL STYLES ══ */
    .custom-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        padding: 16px;
    }

    .custom-modal-content {
        background: #fff;
        width: 100%;
        max-width: 480px;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        animation: popupSlightIn 0.3s ease-out;
        max-height: 90vh;
        overflow-y: auto;
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

    /* ══ 6. UNIFIED PUBLIC PAGINATION STYLES ══ */
    .news-pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 35px; flex-wrap: wrap; gap: 15px; }
    .news-pagination p { font-size: 0.85rem; color: #555; margin: 0; font-weight: 500; }
    .news-page-controls { display: flex; gap: 8px; }
    .news-page-controls a, .news-page-controls span {
        display: inline-flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 8px; border: 1px solid #c8bcba;
        background: #fff; color: #333; font-size: 0.85rem; text-decoration: none; transition: 0.2s;
    }
    .news-page-controls a:hover { background: #FFF5F5; border-color: #C0392B; color: #C0392B; }
    .news-page-controls span.active { background: #C0392B; color: #fff; border-color: #C0392B; font-weight: 700; }
    .news-page-controls span.disabled { color: #ccc; cursor: not-allowed; background: #fdfdfd; }

    /* ══ RESPONSIVE TAMBAHAN ══ */
    @media (max-width: 768px) {
        .berita-banner-container { height: 280px; }
        .berita-banner-content h1 { font-size: 2rem !important; }
        .berita-banner-content p { font-size: 0.95rem !important; }
        .galeri-wrapper { width: 95%; margin: 24px auto; }
        .galeri-grid { grid-template-columns: 1fr; gap: 18px; }
        .news-pagination { flex-direction: column; align-items: center; }
    }

    @media (max-width: 480px) {
        .berita-banner-container { height: 240px; }
        .berita-banner-content h1 { font-size: 1.6rem !important; }
        .berita-banner-content p { font-size: 0.85rem !important; }
        .badge-theme { font-size: 0.75rem; padding: 5px 12px; }
        .checkbox-grid { grid-template-columns: 1fr; }
        .custom-modal-content { padding: 22px; }
    }

    @keyframes popupSlightIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<div class="public-page-wrapper">
    {{-- Hero Banner Slider --}}
    <div class="berita-banner-container">
        <div class="banner-slideshow-wrapper header-slideshow-container">
            @if(isset($profil) && $profil->headers && $profil->headers->isNotEmpty())
                @foreach($profil->headers as $index => $headerFoto)
                    <img src="{{ asset('storage/' . $headerFoto->path) }}" alt="Header Sanggar JEB" class="{{ $index === 0 ? 'active' : '' }}" onerror="this.style.display='none'; document.getElementById('header-fallback').classList.add('active');">
                @endforeach
                <div id="header-fallback" class="banner-error-fallback">
                    <i class="fas fa-image" style="font-size: 3.5rem; color: rgba(255,255,255,0.3); margin-bottom: 8px;"></i>
                    <span style="font-size: 0.85rem; color: rgba(255,255,255,0.5); font-weight: 500;">Gagal Memuat Gambar Banner</span>
                </div>
            @else
                <div class="banner-error-fallback active">
                    <i class="fas fa-image" style="font-size: 3.5rem; color: rgba(255,255,255,0.3); margin-bottom: 8px;"></i>
                    <span style="font-size: 0.85rem; color: rgba(255,255,255,0.5); font-weight: 500;">Gagal Memuat Gambar Banner</span>
                </div>
            @endif
        </div>
        <div class="banner-overlay-dark"></div>
        <div class="berita-banner-content">
            <span class="badge-theme">Dokumentasi</span>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin: 0; font-weight: 700;">Galeri Sanggar</h1>
            <p style="font-size: 1.1rem; margin-top: 10px; color: rgba(255,255,255,0.9);">Momen indah perjalanan seni dan Budaya Sanggar Tari Jiwa Etnik Blambangan</p>
        </div>
    </div>

    <div class="galeri-wrapper">
        <div class="section-header" style="margin-bottom: 15px;">
            <h2 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #6b1414; font-size: 1.8rem; margin-bottom: 5px;">Daftar Foto Dokumentasi</h2>
        </div>

        {{-- Form Pencarian dan Filter --}}
        <form action="{{ route('umum.galeri') }}" method="GET" id="formPublicGaleri">
            <div class="search-filter-wrapper">
                <div class="search-box-container">
                    <i class="fas fa-search" onclick="submitPublicForm()"></i>
                    <input type="text" name="search" id="searchPublicGaleri" class="search-input" placeholder="Cari dokumentasi..." value="{{ request('search') }}">
                </div>
                <button type="button" class="btn-trigger-filter" onclick="openFilterModal()">
                    <i class="fas fa-filter"></i> Filter Kategori
                </button>
            </div>

            {{-- Modal Filter Pop-up --}}
            <div id="filterModal" class="custom-modal-overlay">
                <div class="custom-modal-content">
                    <div class="custom-modal-header">
                        <h3>Filter Kategori Dokumentasi</h3>
                        <p>Saring foto berdasarkan asal unggahan modul</p>
                    </div>
                    <hr class="modal-divider">
                    <div class="custom-modal-body">
                        <div class="filter-group-section">
                            <label class="section-title">Daftar Modul Kategori</label>
                            <div class="checkbox-grid">
                                @foreach(['katalog', 'berita', 'event', 'umum'] as $kategori)
                                    <label class="filter-checkbox-card {{ in_array($kategori, request('kategori', [])) ? 'selected-box' : '' }}">
                                        <input type="checkbox" name="kategori[]" value="{{ $kategori }}" onchange="syncCardVisual(this)" {{ in_array($kategori, request('kategori', [])) ? 'checked' : '' }}> 
                                        <span class="checkbox-label" style="text-transform: capitalize;">{{ $kategori }}</span>
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

        {{-- Grid Render File Foto --}}
        <div class="galeri-grid">
            @forelse($galeriData as $item)
                <a href="{{ route('umum.galeri-detail', $item->id) }}" class="galeri-card" style="text-decoration: none; color: inherit;">
                    <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->nama_file }}" onerror="this.style.display='none'; document.getElementById('galeri-fallback-{{ $item->id }}').classList.add('active');">
                    <div id="galeri-fallback-{{ $item->id }}" class="inline-error-fallback">
                        <i class="fas fa-image-slash" style="font-size: 2.2rem; margin-bottom: 5px;"></i>
                        <span style="font-size: 0.75rem; font-weight: 500;">Gagal Memuat Gambar</span>
                    </div>
                    <div class="galeri-overlay">
                        <span style="text-transform: capitalize; font-weight: 600;"><i class="fas fa-tag"></i> {{ $item->kategori }}</span>
                    </div>
                </a>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 80px 20px; color: #7f8c8d;">
                    <i class="far fa-image" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 15px; display: block;"></i>
                    <p style="font-size: 1.1rem; font-weight: 600; margin: 0;">Foto belum tersedia</p>
                    <p style="font-size: 0.9rem; color: #888; margin-top: 5px;">Belum ada dokumentasi foto untuk kriteria pencarian ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Navigasi Paginasi Bar --}}
        <div class="news-pagination">
            @if($galeriData->total() > 0)
                <p>Menampilkan {{ $galeriData->firstItem() }} - {{ $galeriData->lastItem() }} dari {{ $galeriData->total() }} dokumentasi foto</p>
            @else
                <p>Menampilkan 0 dokumentasi foto</p>
            @endif
            <div class="news-page-controls">
                @if($galeriData->hasPages())
                    @if ($galeriData->onFirstPage()) <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                    @else <a href="{{ $galeriData->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a> @endif
                    @foreach ($galeriData->getUrlRange(1, $galeriData->lastPage()) as $page => $url)
                        @if ($page == $galeriData->currentPage()) <span class="active">{{ $page }}</span>
                        @else <a href="{{ $url }}">{{ $page }}</a> @endif
                    @endforeach
                    @if ($galeriData->hasMorePages()) <a href="{{ $galeriData->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else <span class="disabled"><i class="fas fa-chevron-right"></i></span> @endif
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function openFilterModal() { 
        document.getElementById('filterModal').style.display = 'flex'; 
        document.body.style.overflow = 'hidden';
    }
    function closeFilterModal() { 
        document.getElementById('filterModal').style.display = 'none'; 
        document.body.style.overflow = '';
    }
    window.onclick = function(e) { if (e.target == document.getElementById('filterModal')) closeFilterModal(); }
    function syncCardVisual(c) { let p = c.closest('.filter-checkbox-card'); if (c.checked) p.classList.add('selected-box'); else p.classList.remove('selected-box'); }
    function submitPublicForm() { document.getElementById('formPublicGaleri').submit(); }

    const searchGaleriInput = document.getElementById('searchPublicGaleri');
    if (searchGaleriInput) {
        searchGaleriInput.addEventListener('keypress', function(e) { 
            if (e.key === 'Enter') { e.preventDefault(); submitPublicForm(); } 
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const headerContainer = document.querySelector('.header-slideshow-container');
        if (headerContainer) {
            setInterval(() => {
                const slides = headerContainer.querySelectorAll('img:not([style*="display: none"]), .banner-error-fallback.active');
                if (slides.length > 1) {
                    let currentIndex = Array.from(slides).findIndex(el => el.classList.contains('active'));
                    if (currentIndex !== -1) {
                        slides[currentIndex].classList.remove('active');
                        slides[(currentIndex + 1) % slides.length].classList.add('active');
                    }
                }
            }, 7000);
        }
    });
</script>
@endsection