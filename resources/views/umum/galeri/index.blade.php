@extends('layouts.umum')

@section('title', 'Galeri Sanggar - Jiwa Etnik Blambangan')

@push('styles')
<style>
    /* ══ RESET & GLOBAL BACKGROUND BLOCK ══ */
    body {
        margin: 0;
        padding: 0;
        background-color: #ffffff;
    }

    /* Base Wrapper mengikuti spesifikasi CSS halaman Galeri */
    .galeri-page-wrapper {
        background: #E7DCDA;
        width: 100%;
        min-height: 100vh;
        position: relative;
        padding-bottom: 80px;
    }

    /* ══ 1. HERO BANNER ATAS ══ */
    .galeri-banner {
        width: 100%;
        height: 380px;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('img/images-landing-page/banner_berita.jpeg') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 8%;
        color: white;
    }

    .badge-dokumentasi {
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

    /* ══ 2. TOP BAR (SEARCH & FILTER) ══ */
    .galeri-top-bar {
        max-width: 1300px;
        margin: 40px auto 30px auto;
        padding: 0 4%;
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .search-wrapper {
        position: relative;
        flex-grow: 1;
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 100%;
        padding: 14px 20px 14px 50px;
        border: 1px solid #ffffff;
        border-radius: 8px;
        background-color: #ffffff;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        color: #333;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        outline: none;
        box-sizing: border-box;
    }

    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%; 
        transform: translateY(-50%); 
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .btn-filter {
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: #C0392B;
        color: #ffffff;
        border: none;
        padding: 14px 28px;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background-color 0.2s;
        box-shadow: 0 2px 10px rgba(192, 57, 43, 0.2);
        white-space: nowrap;
    }

    .btn-filter:hover {
        background-color: #A02E22;
    }

    /* POPUP MODAL COMPONENT STYLE */
    .custom-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); display: none; justify-content: center; align-items: center; z-index: 9999;
    }
    .custom-modal-content {
        background: #fff; width: 100%; max-width: 480px; border-radius: 20px; padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15); animation: popupSlightIn 0.3s ease-out;
    }
    .custom-modal-header h3 { margin: 0; font-size: 1.3rem; font-weight: 700; color: #2D1A1A; font-family: 'Poppins', sans-serif; }
    .custom-modal-header p { margin: 4px 0 0 0; font-size: 0.85rem; color: #888; font-family: 'Poppins', sans-serif; }
    .modal-divider { border: 0; border-top: 1px solid #E5E7EB; margin: 18px 0; }
    .section-title { font-size: 0.95rem; font-weight: 600; color: #333; display: block; margin-bottom: 12px; font-family: 'Poppins', sans-serif; }
    
    .checkbox-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .filter-checkbox-card {
        border: 1px solid #D1D5DB; border-radius: 12px; padding: 12px 15px; display: flex; align-items: center; gap: 10px;
        cursor: pointer; transition: all 0.2s; background: #fff; user-select: none; font-family: 'Poppins', sans-serif;
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
    .btn-modal-terapkan:hover { background: #A02E22; }

    /* ══ 3. GRID GALERI FOTO ══ */
    .galeri-grid-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 4%;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .galeri-card {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        aspect-ratio: 4 / 3;
        position: relative;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .galeri-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .galeri-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .galeri-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        padding: 15px;
        color: white;
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .galeri-card:hover .galeri-overlay {
        opacity: 1;
    }

    /* ══ 4. UNIFIED PUBLIC PAGINATION STYLES ══ */
    .news-pagination-wrapper {
        max-width: 1300px;
        margin: 40px auto 0 auto;
        padding: 0 4%;
    }
    .news-pagination { display: flex; justify-content: space-between; align-items: center; font-family: 'Poppins', sans-serif; }
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

    /* RESPONSIVE DESIGN SCREEN */
    @media (max-width: 1200px) {
        .galeri-grid-container { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .galeri-top-bar { flex-direction: column; align-items: stretch; }
        .galeri-grid-container { grid-template-columns: repeat(2, 1fr); }
        .news-pagination { flex-direction: column; gap: 15px; text-align: center; }
    }
    @media (max-width: 480px) {
        .galeri-grid-container { grid-template-columns: 1fr; }
    }
    @keyframes popupSlightIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>
@endpush

@section('content')
<div class="galeri-page-wrapper">

    <div class="galeri-banner">
        <span class="badge-dokumentasi">Dokumentasi</span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin: 0; font-weight: 700;">Galeri Sanggar</h1>
        <p style="font-size: 1.1rem; margin-top: 10px; color: rgba(255,255,255,0.9);">Momen indah, pendidikan seni dan dari Budaya Sanggar Tari Jiwa Etnik Blambangan</p>
    </div>

    {{-- INTEGRATED FORM ACTION GET FOR SEARCH & FILTER --}}
    <form action="{{ route('umum.galeri') }}" method="GET" id="formPublicGaleri">
        <div class="galeri-top-bar">
            <div class="search-wrapper">
                <span class="search-icon" onclick="submitPublicForm()">🔍</span>
                <input type="text" name="search" id="searchPublicGaleri" class="search-input" placeholder="Cari berdasarkan nama file atau kata kunci..." value="{{ request('search') }}">
            </div>
            
            <button type="button" class="btn-filter" onclick="openFilterModal()">
                <span>🔻</span> Filter Kategori
            </button>
        </div>

        {{-- MULTI-SELECT CATEGORY CHECKBOX MODAL POPUP --}}
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

    <div class="galeri-grid-container">
        @forelse($galeriData as $item)
            {{-- HOTFIX: Otomatis sembunyikan data personel/pembina jika tidak sengaja masuk ke tabel galeri --}}
            @if(
                \Illuminate\Support\Str::contains($item->path, ['personel', 'pembina']) || 
                \Illuminate\Support\Str::contains($item->nama_file, ['pengurus', 'pelatih', 'pembina', 'dummy'])
            )
                @continue
            @endif

            <a href="{{ route('umum.galeri-detail', $item->id) }}" class="galeri-card" style="text-decoration: none; color: inherit;">
                {{-- Perbaikan Render Path: Diarahkan ke storage path agar gambar muncul dinamis --}}
                <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->nama_file }}" onerror="this.onerror=null; this.src='{{ asset('img/images-landing-page/banner_berita.jpeg') }}';">
                
                <div class="galeri-overlay">
                    <span style="text-transform: capitalize; font-weight: 600;"><i class="fas fa-tag"></i> {{ $item->kategori }}</span>
                </div>
            </a>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 80px 20px; font-family: 'Poppins', sans-serif; color: #666;">
                <p style="font-size: 1.2rem; font-weight: 600; margin: 0;">Foto belum tersedia</p>
                <p style="font-size: 0.9rem; color: #888; margin-top: 5px;">Belum ada dokumentasi foto untuk kategori atau pencarian ini.</p>
            </div>
        @endforelse
    </div>

    {{-- INTERACTIVE PAGINATION LINK DISPLAY --}}
    <div class="news-pagination-wrapper">
        <div class="news-pagination">
            @if($galeriData->total() > 0)
                <p>Menampilkan {{ $galeriData->firstItem() }} - {{ $galeriData->lastItem() }} dari {{ $galeriData->total() }} dokumentasi foto</p>
            @else
                <p>Menampilkan 0 dokumentasi foto</p>
            @endif
            
            <div class="news-page-controls">
                @if($galeriData->hasPages())
                    {{-- Tombol Sebelumnya --}}
                    @if ($galeriData->onFirstPage())
                        <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $galeriData->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    {{-- Loop range nomor halaman --}}
                    @foreach ($galeriData->getUrlRange(1, $galeriData->lastPage()) as $page => $url)
                        @if ($page == $galeriData->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Tombol Selanjutnya --}}
                    @if ($galeriData->hasMorePages())
                        <a href="{{ $galeriData->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
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

    function submitPublicForm() {
        document.getElementById('formPublicGaleri').submit();
    }

    document.getElementById('searchPublicGaleri').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitPublicForm();
        }
    });
</script>
@endsection