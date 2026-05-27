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
        pointer-events: none;
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
    }

    .btn-filter:hover {
        background-color: #A02E22;
    }

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

    /* Efek Overlay Halus saat Hover (Opsional, untuk menampilkan Kategori/Caption) */
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

    /* RESPONSIVE DESIGN SCREEN */
    @media (max-width: 1200px) {
        .galeri-grid-container { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .galeri-top-bar { flex-direction: column; align-items: stretch; }
        .galeri-grid-container { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .galeri-grid-container { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="galeri-page-wrapper">

    <div class="galeri-banner">
        <span class="badge-dokumentasi">Dokumentasi</span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin: 0; font-weight: 700;">Galeri Sanggar</h1>
        <p style="font-size: 1.1rem; margin-top: 10px; color: rgba(255,255,255,0.9);">Momen indah, pendidikan seni dan dari Budaya Sanggar Tari Jiwa Etnik Blambangan</p>
    </div>

    <div class="galeri-top-bar">
        <form action="{{ route('umum.galeri') }}" method="GET" class="search-wrapper">
            <span class="search-icon">🔍</span>
            <input type="text" name="search" class="search-input" placeholder="Cari nama tari..." value="{{ request('search') }}">
        </form>
        
        <button class="btn-filter" id="filterBtn">
            <span>🔻</span> Filter
        </button>
    </div>

    <div class="galeri-grid-container">
        @forelse($galeriData as $item)
        <a href="{{ route('umum.galeri-detail', $item->id) }}" class="galeri-card" style="text-decoration: none; color: inherit;">
            <div class="galeri-card">
                <img src="{{ asset($item->path) }}" alt="{{ $item->nama_file }}">
                
                <div class="galeri-overlay">
                    <span style="text-transform: capitalize; font-weight: 600;">{{ $item->kategori }}</span>
                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 80px 20px; font-family: 'Poppins', sans-serif; color: #666;">
                <p style="font-size: 1.2rem; font-weight: 600; margin: 0;">Foto belum tersedia</p>
                <p style="font-size: 0.9rem; color: #888; margin-top: 5px;">Belum ada dokumentasi foto untuk kategori atau pencarian ini.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection