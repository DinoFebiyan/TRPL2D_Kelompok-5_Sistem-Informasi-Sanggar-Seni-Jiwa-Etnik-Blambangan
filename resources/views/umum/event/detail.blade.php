@extends('layouts.umum')

@section('title', $event->nama . ' - Sanggar Tari JEB')

@push('styles')
<style>
    body {
        background-color: #ffffff;
        margin: 0;
        padding: 0;
    }

    .detail-event-container {
        max-width: 1300px;
        margin: 50px auto;
        padding: 0 4%;
    }

    .event-main-card {
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        border: 1px solid #EAEAEA;
    }

    .event-image-wrapper {
        position: relative;
        width: 100%;
        height: 520px;
        background-color: #333;
        overflow: hidden;
    }

    .event-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
        z-index: 1;
    }

    .event-image-wrapper img.active {
        opacity: 1;
    }

    .badge-kategori {
        position: absolute;
        top: 25px;
        left: 35px;
        background-color: #D4AF37;
        color: #3D0C0C;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 6px;
        text-transform: uppercase;
        z-index: 10;
    }

    .event-info-body {
        padding: 40px 45px;
    }

    .event-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 30px;
        margin-bottom: 20px;
    }

    .event-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: #111111;
        font-weight: 700;
        margin: 0;
    }

    .event-time-place {
        text-align: right;
        font-family: 'Poppins', sans-serif;
        color: #333333;
        font-size: 1rem;
        line-height: 1.6;
        min-width: 250px;
    }

    .event-time-place .date {
        font-weight: 700;
        color: #111111;
        font-size: 1.1rem;
    }

    .event-description {
        font-family: 'Poppins', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #555555;
        margin-top: 15px;
        text-align: justify;
    }

    /* ══ SECTION EVENT LAIN (BAWAH) ══ */
    .event-lain-section {
        background-color: #E7DCDA;
        padding: 6rem 8%;
    }

    .event-lain-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.6rem;
        color: #3D0C0C;
        font-weight: 800;
        margin: 0 0 35px 0;
    }

    .event-lain-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }

    /* MODIFIKASI: Penataan ulang card agar layout foto berada penuh di kiri */
    .lain-card {
        background: #ffffff;
        border-radius: 16px;
        display: flex;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .lain-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    /* MODIFIKASI: Lebar box slideshow diperbesar agar menampung aspek rasio foto komersial */
    .lain-slideshow-box {
        width: 180px;
        min-width: 180px;
        height: auto;
        position: relative;
        background-color: #f3f4f6;
        overflow: hidden;
    }

    .lain-slideshow-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        z-index: 1;
    }

    .lain-slideshow-box img.active {
        opacity: 1;
        position: relative;
    }

    /* MODIFIKASI: Menghapus .lain-date-box lama yang berwarna marun di samping */
    
    .lain-details-box {
        padding: 25px;
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Membuat metadata otomatis berada di baris paling bawah */
        flex-grow: 1;
        background: #fff;
    }

    .lain-details-box h4 {
        margin: 0 0 8px 0;
        font-size: 1.25rem;
        color: #111;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        line-height: 1.4;
    }

    /* MODIFIKASI: Cuplikan deskripsi singkat event */
    .lain-desc-excerpt {
        font-size: 0.88rem;
        color: #666;
        line-height: 1.5;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* MODIFIKASI: Desain grid informasi waktu & tempat di bawah deskripsi */
    .lain-meta-info {
        display: flex;
        flex-wrap: wrap;
        gap: 12px 18px;
        font-size: 0.8rem;
        color: #555;
        font-family: 'Poppins', sans-serif;
        border-top: 1px solid #f0f0f0;
        padding-top: 12px;
    }
    
    .lain-meta-info span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .inline-error-fallback {
        width: 100%; height: 100%; display: none; flex-direction: column;
        align-items: center; justify-content: center; background: #e5e7eb; color: #9ca3af;
        position: absolute; top: 0; left: 0; z-index: 2;
    }
    .inline-error-fallback.active { display: flex; }

    @media (max-width: 992px) {
        .event-image-wrapper { height: 380px; }
        .event-header-row { flex-direction: column; gap: 15px; }
        .event-time-place { text-align: left; }
        .event-lain-grid { grid-template-columns: 1fr; }
        .lain-card { flex-direction: column; }
        .lain-slideshow-box { width: 100%; height: 200px; }
    }
</style>
@endpush

@section('content')

<div class="detail-event-container">
    <div class="event-main-card">
        
        {{-- SLIDESHOW UTAMA: Foto Detail Event --}}
        <div class="event-image-wrapper slideshow-container-main">
            <span class="badge-kategori">{{ $event->kategori }}</span>
            
            @if($event->galeri && $event->galeri->isNotEmpty())
                @foreach($event->galeri as $index => $foto)
                    <img src="{{ asset('storage/' . $foto->path) }}" 
                         alt="{{ $event->nama }}" 
                         class="{{ $index === 0 ? 'active' : '' }}"
                         onerror="this.style.display='none'; document.getElementById('main-fallback').classList.add('active');">
                @endforeach
                <div id="main-fallback" class="inline-error-fallback">
                    <i class="fas fa-image" style="font-size: 3.5rem; color: #9ca3af;"></i>
                </div>
            @else
                <img src="{{ asset('img/images-landing-page/banner_berita.jpeg') }}" alt="{{ $event->nama }}" class="active">
            @endif
        </div>

        <div class="event-info-body">
            <div class="event-header-row">
                <h1 class="event-title">{{ $event->nama }}</h1>
                <div class="event-time-place">
                    <div class="date">{{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') }}</div>
                    <div>{{ $event->lokasi ?? 'Lokasi belum ditentukan' }}</div>
                    <div style="font-size: 0.9rem; color: #666; margin-top: 4px;">
                        🕒 {{ \Carbon\Carbon::parse($event->jam)->format('H:i') }} WIB
                    </div>
                </div>
            </div>
            
            <div class="event-description">
                {!! $event->deskripsi !!}
            </div>
        </div>

    </div>
</div>

{{-- SECTION EVENT LAIN (ACAK) --}}
<section class="event-lain-section">
    <h3 class="event-lain-title">Event Lain</h3>
    
    <div class="event-lain-grid">
        @forelse($eventLainnya as $lain)
            <a href="{{ route('umum.event-detail', $lain->id) }}" class="lain-card">
                
                {{-- SLIDESHOW MINI: Foto pada kartu rekomendasi event --}}
                <div class="lain-slideshow-box slideshow-container-mini">
                    @if($lain->galeri && $lain->galeri->isNotEmpty())
                        @foreach($lain->galeri as $index => $fotoLain)
                            <img src="{{ asset('storage/' . $fotoLain->path) }}" 
                                 alt="{{ $lain->nama }}" 
                                 class="{{ $index === 0 ? 'active' : '' }}"
                                 onerror="this.style.display='none'; document.getElementById('lain-fallback-{{ $lain->id }}').classList.add('active');">
                        @endforeach
                        <div id="lain-fallback-{{ $lain->id }}" class="inline-error-fallback">
                            <i class="fas fa-image" style="font-size: 1.5rem;"></i>
                        </div>
                    @else
                        <img src="{{ asset('img/images-landing-page/banner_berita.jpeg') }}" alt="{{ $lain->nama }}" class="active">
                    @endif
                </div>
                
                <div class="lain-details-box">
                    <div>
                        <h4>{{ $lain->nama }}</h4>
                        {{-- Menampilkan cuplikan deskripsi pendek --}}
                        <div class="lain-desc-excerpt">{{ strip_tags($lain->deskripsi) }}</div>
                    </div>
                    
                    {{-- MODIFIKASI: Komponen tanggal digabungkan ke bagian paling bawah dari deskripsi --}}
                    <div class="lain-meta-info">
                        <span><i class="far fa-calendar-alt" style="color: #3D0C0C;"></i> {{ \Carbon\Carbon::parse($lain->tanggal)->translatedFormat('d M Y') }}</span>
                        <span><i class="fas fa-map-marker-alt" style="color: #3D0C0C;"></i> {{ Str::limit($lain->lokasi, 20) }}</span>
                        <span><i class="far fa-clock" style="color: #3D0C0C;"></i> {{ \Carbon\Carbon::parse($lain->jam)->format('H:i') }} WIB</span>
                    </div>
                </div>
            </a>
        @empty
            <p style="grid-column: 1/-1; text-align: center; color: #666; font-family: 'Poppins', sans-serif;">
                Tidak ada event lainnya saat ini.
            </p>
        @endforelse
    </div>
</section>

{{-- JAVASCRIPT SYSTEM AUTOMATIC SLIDESHOW --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Slideshow untuk Foto Utama Event (7 Detik)
        const mainContainer = document.querySelector('.slideshow-container-main');
        if (mainContainer) {
            const mainImages = mainContainer.querySelectorAll('img');
            if (mainImages.length > 1) {
                let mainIndex = 0;
                setInterval(() => {
                    mainImages[mainIndex].classList.remove('active');
                    mainIndex = (mainIndex + 1) % mainImages.length;
                    mainImages[mainIndex].classList.add('active');
                }, 7000);
            }
        }

        // 2. Slideshow untuk Setiap Kartu Event Lainnya (5 Detik)
        const miniContainers = document.querySelectorAll('.slideshow-container-mini');
        miniContainers.forEach(container => {
            const miniImages = container.querySelectorAll('img');
            if (miniImages.length > 1) {
                let miniIndex = 0;
                setInterval(() => {
                    miniImages[miniIndex].classList.remove('active');
                    miniIndex = (miniIndex + 1) % miniImages.length;
                    miniImages[miniIndex].classList.add('active');
                }, 5000);
            }
        });
    });
</script>
@endsection