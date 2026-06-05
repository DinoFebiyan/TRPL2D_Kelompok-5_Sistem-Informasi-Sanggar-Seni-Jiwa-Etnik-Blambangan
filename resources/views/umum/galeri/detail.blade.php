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
        background-color: #333;
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
        z-index: 5;
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
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .grid-item-card {
        display: block; 
        border-radius: 12px; 
        overflow: hidden;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: transform 0.3s, box-shadow 0.3s;
        background-color: #f3f4f6;
        height: 200px;
    }

    .grid-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }

    .grid-item-card img {
        width: 100%; 
        height: 100%; 
        object-fit: cover;
    }

    /* RESPONSIVE DESIGN BREAKPOINTS */
    @media (max-width: 1024px) {
        .other-gallery-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 992px) {
        .hero-image-box { height: 400px; }
        .info-header-row { flex-direction: column; gap: 20px; }
        .info-meta-right { text-align: left; min-width: 100%; }
        .info-meta-right .meta-item { justify-content: flex-start; }
    }

    @media (max-width: 576px) {
        .other-gallery-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="galeri-detail-wrapper">
    <div class="detail-container">
        
    <div class="hero-image-card">
        {{-- IMAGE BOX UTAMA (STORAGE PENYIMPANAN) --}}
        <div class="hero-image-box">
            <img src="{{ asset('storage/' . $galeriUtama->path) }}" alt="{{ $galeriUtama->nama_file }}" onerror="this.src='{{ asset('img/images-landing-page/banner_berita.jpeg') }}';">
            <span class="category-badge">{{ $galeriUtama->kategori }}</span>
        </div>

        @php
        // Inisialisasi variabel fallback default
        $judulTeks = 'Dokumentasi ' . ucwords(str_replace('_', ' ', pathinfo($galeriUtama->nama_file, PATHINFO_FILENAME)));
        $tanggalTeks = \Carbon\Carbon::parse($galeriUtama->created_at)->translatedFormat('d F Y');
        $lokasiTeks = 'Lokasi Sanggar JEB';
        $jamTeks = null;
        $deskripsiTeks = 'Dokumentasi resmi dari Sanggar Tari Jiwa Etnik Blambangan (JEB) menampilkan keindahan gerak, busana adat tradisional, dan ekspresi budaya khas masyarakat Bumi Blambangan.';

        // Pengkondisian nama dinamis berdasarkan Kategori Galeri
        $kategoriTerpilih = strtolower($galeriUtama->kategori);

        if ($kategoriTerpilih === 'event' && isset($galeriUtama->event)) {
            $judulTeks = $galeriUtama->event->nama;
            $tanggalTeks = \Carbon\Carbon::parse($galeriUtama->event->tanggal)->translatedFormat('d F Y');
            $lokasiTeks = $galeriUtama->event->lokasi ?? $lokasiTeks;
            $jamTeks = $galeriUtama->event->jam ? \Carbon\Carbon::parse($galeriUtama->event->jam)->format('H:i') . ' WIB' : null;
            $deskripsiTeks = $galeriUtama->event->deskripsi ?? $deskripsiTeks;

        } elseif ($kategoriTerpilih === 'katalog' && isset($galeriUtama->katalog)) {
            // DIUBAH: Langsung mengambil nama tarian tanpa teks "Kostum / Alat:"
            $judulTeks = $galeriUtama->katalog->nama_katalog; 
            $lokasiTeks = 'Koleksi Sanggar JEB (Stok: ' . $galeriUtama->katalog->stok . ')';
            $deskripsiTeks = $galeriUtama->katalog->deskripsi ?? $deskripsiTeks;

        } elseif ($kategoriTerpilih === 'berita' && isset($galeriUtama->berita)) {
            $judulTeks = $galeriUtama->berita->judul;
            $tanggalTeks = \Carbon\Carbon::parse($galeriUtama->berita->tgl_terbit ?? $galeriUtama->berita->created_at)->translatedFormat('d F Y');
            $lokasiTeks = 'Ditulis oleh: ' . ($galeriUtama->berita->user->name ?? 'Admin');
            $deskripsiTeks = strip_tags($galeriUtama->berita->isi_berita);
        }
    @endphp

        <div class="hero-info-box">
            <div class="info-header-row">
                <h1 class="info-title">
                    {{ $judulTeks }}
                </h1>
                
                <div class="info-meta-right">
                    <div class="main-date">
                        {{ $tanggalTeks }}
                    </div>
                    
                    <div class="meta-item">
                        <span>📍</span> 
                        <span>{{ $lokasiTeks }}</span>
                    </div>

                    @if($jamTeks)
                        <div class="meta-item">
                            <span>🕒</span> 
                            <span>{{ $jamTeks }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="info-caption">
                <p>
                    {!! nl2br(e(\Illuminate\Support\Str::limit($deskripsiTeks, 400))) !!}
                </p>
            </div>
        </div>
    </div>

    </div>

    {{-- SECTION SEPARATOR REKOMENDASI GALERI LAIN (ACAK) --}}
    <div class="section-separator">
        <div class="detail-container">
            <h3 class="other-section-title">Foto Lainnya</h3>
            
            <div class="other-gallery-grid">
                @forelse($galeriLainnya as $item)
                    {{-- REDIREKSI LINK & IMAGE STORAGE REKOMENDASI --}}
                    <a href="{{ route('umum.galeri-detail', $item->id) }}" class="grid-item-card">
                        <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->nama_file }}" onerror="this.src='{{ asset('img/images-landing-page/banner_berita.jpeg') }}';">
                    </a>
                @empty
                    <p style="grid-column: 1/-1; text-align: center; color: #666; font-family: 'Poppins', sans-serif;">
                        Belum ada foto dokumentasi lainnya saat ini.
                    </p>
                @endforelse
            </div>
        </div>
    </div>
    
</div>
@endsection