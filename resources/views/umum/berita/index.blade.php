@extends('layouts.umum')

@section('title', 'Artikel & Berita - Sanggar JEB')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* ══ SYSTEM BACKGROUND MATCHING ══ */
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

    .berita-wrapper {
        width: 90%;
        max-width: 1300px;
        margin: 40px auto;
        font-family: 'Poppins', sans-serif;
    }

    /* ══ 2. GRID KONTEN MAJALAH ══ */
    .berita-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .berita-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        transition: 0.3s;
        border: 1px solid #f0f0f0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .berita-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    /* ══ 3. SEARCH & FILTER ROW BAR ══ */
    .search-filter-wrapper {
        background: transparent;
        padding: 20px 0;
        display: flex;
        gap: 15px;
        align-items: center;
        position: relative;
    }

    .search-box-container {
        flex: 1;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border-radius: 10px;
        border: 1px solid #ddd;
        background: white;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        outline: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
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
        box-shadow: 0 2px 10px rgba(192, 57, 43, 0.2);
        white-space: nowrap;
    }

    .btn-trigger-filter:hover {
        background: #A93226;
    }

    /* ══ 4. SLIDESHOW CARD BERITA & FALLBACK ══ */
    .news-foto-wrapper {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: #f3f4f6;
    }
    
    .news-foto-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    
    .news-foto-wrapper img.active {
        opacity: 1;
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

    /* ══ 5. FILTER MODAL POPUP COMPONENTS ══ */
    .custom-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    .custom-modal-content {
        background: #fff;
        width: 100%;
        max-width: 480px;
        border-radius: 20px;
        padding: 30px;
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

    /* ══ 6. UNIFIED PUBLIC PAGINATION NAV ══ */
    .news-pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 35px; }
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

    @keyframes popupSlightIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<div class="public-page-wrapper">
    {{-- Header Slider Banner --}}
    <div class="berita-banner-container">
        <div class="banner-slideshow-wrapper header-slideshow-container">
            @if(isset($profil) && $profil->headers && $profil->headers->isNotEmpty())
                @foreach($profil->headers as $index => $headerFoto)
                    <img src="{{ asset('storage/' . $headerFoto->path) }}" 
                         alt="Header Sanggar JEB" 
                         class="{{ $index === 0 ? 'active' : '' }}" 
                         onerror="this.style.display='none'; document.getElementById('header-fallback').classList.add('active');">
                @endforeach

                <div id="header-fallback" class="banner-error-fallback">
                    <i class="fas fa-image" style="font-size: 3.5rem; color: rgba(255,255,255,0.3); margin-bottom: 8px;"></i>
                    <span style="font-size: 0.85rem; color: rgba(255,255,255,0.5); font-weight: 500;">Gagal Memuat Header</span>
                </div>
            @else
                <div class="banner-error-fallback active">
                    <i class="fas fa-image" style="font-size: 3.5rem; color: rgba(255,255,255,0.3); margin-bottom: 8px;"></i>
                    <span style="font-size: 0.85rem; color: rgba(255,255,255,0.5); font-weight: 500;">Gagal Memuat Header</span>
                </div>
            @endif
        </div>
        
        <div class="banner-overlay-dark"></div>

        <div class="berita-banner-content">
            <span class="badge-theme">Berita Terbaru</span>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin: 0; font-weight:700;">Artikel & Berita</h1>
            <p style="font-size: 1.1rem; margin-top: 10px; color: rgba(255,255,255,0.9);">Ikuti kabar terbaru seputar kegiatan dan perkembangan Sanggar JEB</p>
        </div>
    </div>

    <div class="berita-wrapper">
        <div class="section-header" style="margin-bottom: 10px;">
            <h2 style="font-family: 'Playfair Display', serif; font-weight:700; color:#6b1414; font-size:1.8rem;">Daftar Berita</h2>
        </div>

        {{-- SEARCH DAN FILTER --}}
        <form action="{{ route('umum.berita') }}" method="GET" id="formPublicNews">
            <div class="search-filter-wrapper">
                <div class="search-box-container">
                    <input type="text" name="search" id="searchPublicNews" class="search-input" placeholder="Cari judul, kata kunci berita atau nama penulis..." value="{{ request('search') }}">
                    <i class="fas fa-search" onclick="submitPublicForm()"></i>
                </div>
                <button type="button" class="btn-trigger-filter" onclick="openFilterModal()">
                    <i class="fas fa-filter"></i> Filter Kategori
                </button>
            </div>

            {{-- POPUP FILTER MODAL --}}
            <div id="filterModal" class="custom-modal-overlay">
                <div class="custom-modal-content">
                    <div class="custom-modal-header">
                        <h3>Filter Kategori Artikel</h3>
                        <p>Saring berita berdasarkan jenis topik informasi</p>
                    </div>
                    <hr class="modal-divider">

                    <div class="custom-modal-body">
                        <div class="filter-group-section">
                            <label class="section-title">Jenis Kategori</label>
                            <div class="checkbox-grid">
                                @foreach(['Pengumuman', 'Prestasi', 'Kolaborasi', 'Event', 'Lainnya'] as $kategori)
                                    <label class="filter-checkbox-card {{ in_array($kategori, request('kategori', [])) ? 'selected-box' : '' }}">
                                        <input type="checkbox" name="kategori[]" value="{{ $kategori }}" onchange="syncCardVisual(this)" {{ in_array($kategori, request('kategori', [])) ? 'checked' : '' }}> 
                                        <span class="checkbox-label">{{ $kategori }}</span>
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

        {{-- GRID BERITA --}}
        <div class="berita-grid">
            @forelse($beritas as $item)
                <div class="berita-card">
                    <div class="news-foto-wrapper slideshow-container">
                        @if($item->galeri && $item->galeri->isNotEmpty())
                            @foreach($item->galeri as $index => $foto)
                                <img src="{{ asset('storage/' . $foto->path) }}" 
                                     alt="{{ $item->judul }}" 
                                     class="{{ $index === 0 ? 'active' : '' }}"
                                     onerror="this.style.display='none'; document.getElementById('news-fallback-{{ $item->id }}').classList.add('active');">
                            @endforeach

                            <div id="news-fallback-{{ $item->id }}" class="inline-error-fallback">
                                <i class="fas fa-image-slash" style="font-size: 2.2rem; margin-bottom: 5px;"></i>
                                <span style="font-size: 0.75rem; font-weight: 500;">Gagal Memuat Gambar</span>
                            </div>
                        @else
                            <div class="inline-error-fallback active">
                                <i class="fas fa-image-slash" style="font-size: 2.2rem; margin-bottom: 5px;"></i>
                                <span style="font-size: 0.75rem; font-weight: 500;">Gagal Memuat Gambar</span>
                            </div>
                        @endif

                        <span style="position:absolute; top:15px; left:15px; background:#C0392B; color:#fff; font-size:11px; font-weight:600; padding:4px 10px; border-radius:4px; text-transform:uppercase; z-index: 10;">
                            {{ $item->jenis_berita ?? 'Artikel' }}
                        </span>
                    </div>

                    <a href="{{ route('umum.berita-detail', $item->id) }}" style="text-decoration: none; color: inherit; flex: 1; display: flex; flex-direction: column;">
                        <div style="padding: 20px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <span style="font-size: 12px; color: #7f8c8d;">
                                    <i class="far fa-calendar-alt"></i> {{ $item->created_at ? $item->created_at->translatedFormat('d M Y') : '-' }} 
                                    &nbsp;&bull;&nbsp; <i class="far fa-user"></i> {{ $item->user->name ?? 'Admin' }}
                                </span>
                                <h4 style="margin: 10px 0; font-size:1.15rem; font-weight:700; color:#2c3e50; line-height:1.4;">{{ $item->judul }}</h4>
                                <p style="font-size: 14px; color: #555; line-height:1.6; margin-bottom:0;">{{ Str::limit(strip_tags($item->isi_berita), 110) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; color: #7f8c8d; padding: 60px 0;">
                    <i class="far fa-newspaper" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 15px; display: block;"></i>
                    Belum ada data berita atau artikel yang sesuai dengan kriteria pencarian Anda.
                </div>
            @endforelse
        </div>

        {{-- PAGINASI --}}
        <div class="news-pagination">
            @if($beritas->total() > 0)
                <p>Menampilkan {{ $beritas->firstItem() }} - {{ $beritas->lastItem() }} dari {{ $beritas->total() }} berita</p>
            @else
                <p>Menampilkan 0 berita</p>
            @endif
            
            <div class="news-page-controls">
                @if($beritas->hasPages())
                    @if ($beritas->onFirstPage())
                        <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $beritas->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    @foreach ($beritas->getUrlRange(1, $beritas->lastPage()) as $page => $url)
                        @if ($page == $beritas->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($beritas->hasMorePages())
                        <a href="{{ $beritas->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function openFilterModal() { document.getElementById('filterModal').style.display = 'flex'; }
    function closeFilterModal() { document.getElementById('filterModal').style.display = 'none'; }
    window.onclick = function(event) { if (event.target == document.getElementById('filterModal')) closeFilterModal(); }

    function syncCardVisual(checkboxElement) {
        let parentCard = checkboxElement.closest('.filter-checkbox-card');
        if (checkboxElement.checked) parentCard.classList.add('selected-box');
        else parentCard.classList.remove('selected-box');
    }

    function submitPublicForm() { document.getElementById('formPublicNews').submit(); }

    document.getElementById('searchPublicNews').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); submitPublicForm(); }
    });

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

        document.querySelectorAll('.slideshow-container').forEach(container => {
            setInterval(() => {
                const elements = container.querySelectorAll('img:not([style*="display: none"]), .inline-error-fallback.active');
                if (elements.length > 1) {
                    let currentIndex = Array.from(elements).findIndex(el => el.classList.contains('active'));
                    if (currentIndex !== -1) {
                        elements[currentIndex].classList.remove('active');
                        elements[(currentIndex + 1) % elements.length].classList.add('active');
                    }
                }
            }, 5000);
        });
    });
</script>
@endsection