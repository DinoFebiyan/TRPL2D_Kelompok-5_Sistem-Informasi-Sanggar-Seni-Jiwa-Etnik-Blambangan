@extends('layouts.umum')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Styling Banner - Menggunakan desain yang sama dengan katalog */
    .berita-banner {
        width: 100%;
        height: 350px;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('img/images-landing-page/banner_berita.jpeg') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 8%;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .berita-wrapper {
        width: 95%;
        max-width: 1400px;
        margin: 40px auto;
        font-family: 'Poppins', sans-serif;
    }

    /* Grid 3 atau 4 Kolom agar terlihat seperti layout majalah */
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
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        transition: 0.3s;
        border: 1px solid #f0f0f0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .berita-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

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
    }

    .btn-trigger-filter:hover {
        background: #A93226;
    }

    /* POPUP MODAL COMPONENT STYLE (SINKRON DENGAN PACK KELOLA) */
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
        max-width: 500px;
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

<div class="berita-banner">
    <div style="background: #C0392B; padding: 5px 15px; border-radius: 5px; width: fit-content; margin-bottom: 15px; font-weight:600; font-size:0.85rem; letter-spacing:0.5px; text-transform:uppercase;">
        Berita Terbaru
    </div>
    <h1 style="font-size: 3rem; margin: 0; font-weight:700;">Artikel & Berita</h1>
    <p style="font-size: 1.2rem; opacity:0.9;">Ikuti kabar terbaru seputar kegiatan dan perkembangan Sanggar JEB</p>
</div>

<div class="berita-wrapper">
    <div class="section-header" style="margin-bottom: 10px;">
        <h2 style="font-weight:700; color:#2c3e50;">Daftar Berita</h2>
    </div>

    {{-- INTEGRATED SEARCH AND FILTER MULTI-SELECT COMPONENT --}}
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

        {{-- MULTI-SELECT CATEGORY CHECKBOX MODAL POPUP --}}
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

    <div class="berita-grid">
        @forelse($beritas as $item)
            @php
                // Tentukan gambar default sebagai cadangan jika tidak ada foto
                $fotoBeritaPath = 'img/images-landing-page/banner_berita.jpeg'; 
                
                if ($item->galeri) {
                    if ($item->galeri instanceof \Illuminate\Support\Collection) {
                        $fotoBeritaPath = $item->galeri->first()->path ?? $fotoBeritaPath;
                    } else {
                        $fotoBeritaPath = $item->galeri->path ?? $fotoBeritaPath;
                    }
                }
            @endphp

            <a href="{{ route('umum.berita-detail', $item->id) }}" style="text-decoration: none; color: inherit;">
                <div class="berita-card">
                    <div style="position:relative; width:100%; height:200px; overflow:hidden;">
                        <img src="{{ asset('storage/' . $fotoBeritaPath) }}" alt="{{ $item->judul }}" style="width:100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('img/images-landing-page/banner_berita.jpeg') }}';">
                        <span style="position:absolute; top:15px; left:15px; background:#C0392B; color:#fff; font-size:11px; font-weight:600; padding:4px 10px; border-radius:4px; text-transform:uppercase;">
                            {{ $item->jenis_berita ?? 'Artikel' }}
                        </span>
                    </div>
                    <div style="padding: 20px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <span style="font-size: 12px; color: #888;">
                                <i class="far fa-calendar-alt"></i> {{ $item->created_at ? $item->created_at->translatedFormat('d M Y') : '-' }} 
                                &nbsp;&bull;&nbsp; <i class="far fa-user"></i> {{ $item->user->name ?? 'Admin' }}
                            </span>
                            <h4 style="margin: 10px 0; font-size:1.15rem; font-weight:700; color:#2c3e50; line-height:1.4;">{{ $item->judul }}</h4>
                            <p style="font-size: 14px; color: #666; line-height:1.6; margin-bottom:0;">{{ Str::limit(strip_tags($item->isi_berita), 110) }}</p>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div style="grid-column: span 3; text-align: center; color: #7f8c8d; padding: 60px 0;">
                <i class="far fa-newspaper" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 15px; display: block;"></i>
                Belum ada data berita atau artikel yang sesuai dengan kriteria pencarian Anda.
            </div>
        @endforelse
    </div>

    {{-- INTERACTIVE PAGINATION NAVIGATION --}}
    <div class="news-pagination">
        @if($beritas->total() > 0)
            <p>Menampilkan {{ $beritas->firstItem() }} - {{ $beritas->lastItem() }} dari {{ $beritas->total() }} berita</p>
        @else
            <p>Menampilkan 0 berita</p>
        @endif
        
        <div class="news-page-controls">
            @if($beritas->hasPages())
                {{-- Tombol Sebelumnya --}}
                @if ($beritas->onFirstPage())
                    <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $beritas->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                @endif

                {{-- Iterasi Angka Halaman --}}
                @foreach ($beritas->getUrlRange(1, $beritas->lastPage()) as $page => $url)
                    @if ($page == $beritas->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Tombol Selanjutnya --}}
                @if ($beritas->hasMorePages())
                    <a href="{{ $beritas->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
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
        document.getElementById('formPublicNews').submit();
    }

    document.getElementById('searchPublicNews').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitPublicForm();
        }
    });
</script>
@endsection