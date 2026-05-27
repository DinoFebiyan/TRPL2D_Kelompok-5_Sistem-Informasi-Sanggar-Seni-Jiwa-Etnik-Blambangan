@extends('layouts.umum')

@section('title', 'Detail Dokumentasi - Jiwa Etnik Blambangan')

@push('styles')
<style>
    /* ══ CANVAS SETUP ══ */
    body {
        margin: 0;
        padding: 0;
        background-color: #ffffff;
    }

    /* Mengikuti spesifikasi CSS canvas yang kamu berikan */
    .galeri-detail-wrapper {
        background: #FFFFFF;
        width: 100%;
        min-height: 100vh;
        position: relative;
        padding: 50px 0 100px 0;
    }

    .detail-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 4%;
    }

    /* ══ 1. MAIN HERO PICTURE AREA ══ */
    .hero-image-card {
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        border: 1px solid #EAEAEA;
        margin-bottom: 50px;
    }

    .hero-image-box {
        position: relative;
        width: 100%;
        height: 550px;
    }

    .hero-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .category-badge {
        position: absolute;
        top: 25px;
        left: 35px;
        background-color: #D4AF37;
        color: #3D0C0C;
        padding: 8px 20px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ══ 2. HEADER INFO (JUDUL & META) ══ */
    .hero-info-box {
        padding: 40px 45px;
        font-family: 'Poppins', sans-serif;
    }

    .info-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 40px;
        border-bottom: 1px solid #F0F0F0;
        padding-bottom: 25px;
        margin-bottom: 25px;
    }

    .info-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.6rem;
        color: #3D0C0C;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    .info-meta-right {
        text-align: right;
        min-width: 280px;
        color: #555555;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .info-meta-right .main-date {
        font-size: 1.15rem;
        font-weight: 700;
        color: #111111;
        margin-bottom: 4px;
    }

    .info-meta-right .meta-item {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 4px;
    }

    .info-caption {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #475569;
        text-align: justify;
    }

    /* ══ 3. GRID SECTION: EVENT/GALERI LAIN ══ */
    .section-separator {
        background-color: #E7DCDA;
        padding: 80px 0;
        margin-top: 40px;
    }

    .other-section-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.4rem;
        color: #3D0C0C;
        font-weight: 800;
        margin: 0 0 40px 0;
    }

    .other-gallery-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }

    .grid-item-card {
        background: #ffffff;
        border-radius: 16px;
        display: flex;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        border: 1px solid #E2E8F0;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .grid-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }

    .card-date-side {
        background: #3D0C0C;
        color: #ffffff;
        width: 100px;
        min-width: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        font-family: 'Poppins', sans-serif;
        text-align: center;
    }

    .card-date-side .day-num {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
    }

    .card-date-side .month-txt {
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-top: 5px;
    }

    .card-details-side {
        padding: 25px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-grow: 1;
        font-family: 'Poppins', sans-serif;
    }

    .card-details-side h4 {
        margin: 0 0 10px 0;
        font-size: 1.25rem;
        color: #111111;
        font-weight: 700;
    }

    .card-meta-line {
        display: flex;
        gap: 20px;
        font-size: 0.85rem;
        color: #555555;
    }

    /* RESPONSIVE DESIGN BREAKPOINTS */
    @media (max-width: 992px) {
        .hero-image-box { height: 400px; }
        .info-header-row { flex-direction: column; gap: 20px; }
        .info-meta-right { text-align: left; min-width: 100%; }
        .info-meta-right .meta-item { justify-content: flex-start; }
        .other-gallery-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="galeri-detail-wrapper">
    <div class="detail-container">
        
        <div class="hero-image-card">
            <div class="hero-image-box">
                <img src="{{ asset($galeriUtama->path) }}" alt="{{ $galeriUtama->nama_file }}">
                <span class="category-badge">{{ $galeriUtama->kategori }}</span>
            </div>

            <div class="hero-info-box">
                <div class="info-header-row">
                    <h1 class="info-title">
                        {{ $galeriUtama->event->nama_event ?? 'Dokumentasi ' . ucwords(str_replace('_', ' ', pathinfo($galeriUtama->nama_file, PATHINFO_FILENAME))) }}
                    </h1>
                    
                    <div class="info-meta-right">
                        <div class="main-date">
                            {{ \Carbon\Carbon::parse($galeriUtama->event->tanggal ?? $galeriUtama->created_at)->format('d M Y') }}
                        </div>
                        
                        <div class="meta-item">
                            <span>📍</span> 
                            <span>{{ $galeriUtama->event->lokasi ?? 'Lokasi Sanggar JEB' }}</span>
                        </div>

                        @if(isset($galeriUtama->event->jam))
                            <div class="meta-item">
                                <span>🕒</span> 
                                <span>{{ \Carbon\Carbon::parse($galeriUtama->event->jam)->format('H:i') }} WIB</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="info-caption">
                    <p>
                        {{ $galeriUtama->event->deskripsi ?? 'Dokumentasi resmi dari Sanggar Tari Jiwa Etnik Blambangan (JEB) menampilkan keindahan gerak, busana adat tradisional, dan ekspresi budaya khas masyarakat Bumi Blambangan yang diabadikan dalam berbagai pertunjukan seni.' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

                <div class="section-separator">
                <div class="detail-container">
                    <h3 class="other-section-title">Galeri Lainnya</h3>
                    
                    <div class="other-gallery-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                        @forelse($galeriLainnya as $item)
                            <a href="{{ route('umum.galeri-detail', $item->id) }}" class="grid-item-card" style="display: block; border-radius: 12px; overflow: hidden;">
                                <img src="{{ asset($item->path) }}" alt="{{ $item->nama_file }}" style="width: 100%; height: 200px; object-fit: cover;">
                            </a>
                        @empty
                            <p style="grid-column: 1/-1; text-align: center; color: #666;">Belum ada foto lainnya.</p>
                        @endforelse
                    </div>
                </div>
            </div>
                        

        </div>
    </div>
</div>
@endsection