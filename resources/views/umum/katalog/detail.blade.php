@extends('layouts.umum')

@section('content')
<style>
    /* Styling Dasar Halaman */
    .detail-container {
        background: #E7DCDA;
        padding: 50px 8%;
        min-height: 100vh;
    }

    /* Kartu Detail Utama */
    .main-detail-card {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 60px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .sewa-btn {
        background: #800000;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 12px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .sewa-btn:hover { background: #5a0000; }

    /* MODIFIKASI: Container slideshow foto utama katalog */
    .katalog-main-slideshow {
        position: relative;
        width: 100%;
        height: 500px;
        border-radius: 15px;
        overflow: hidden;
        background-color: #333;
    }

    .katalog-main-slideshow img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
        z-index: 1;
    }

    .katalog-main-slideshow img.active {
        opacity: 1;
    }

    /* Grid Katalog Lain */
    .katalog-lain-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .lain-card {
        background: white;
        padding: 10px;
        border-radius: 12px;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .lain-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

    /* MODIFIKASI: Wrapper slideshow kartu katalog rekomendasi bawah */
    .lain-card-slideshow {
        position: relative;
        width: 100%;
        height: 160px;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f3f4f6;
    }

    .lain-card-slideshow img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        z-index: 1;
    }

    .lain-card-slideshow img.active {
        opacity: 1;
        position: relative; /* Menjaga konsistensi tinggi box card */
    }

    .inline-error-fallback {
        width: 100%; height: 100%; display: none; flex-direction: column;
        align-items: center; justify-content: center; background: #e5e7eb; color: #9ca3af;
        position: absolute; top: 0; left: 0; z-index: 2;
    }
    .inline-error-fallback.active { display: flex; }

    @media (max-width: 992px) {
        .katalog-lain-grid { grid-template-columns: repeat(2, 1fr); }
        .katalog-main-slideshow { height: 350px; }
    }
    @media (max-width: 576px) {
        .katalog-lain-grid { grid-template-columns: 1fr; }
        .detail-header { flex-direction: column; align-items: flex-start; gap: 15px; }
    }
</style>

<div class="detail-container">
    <div class="main-detail-card">
        <div class="detail-header">
            <h1 style="margin: 0; font-family: 'Playfair Display', serif; color: #3d0c0c;">{{ $katalog->nama_katalog }}</h1>
            <a href="https://wa.me/6282232505355" target="_blank" class="sewa-btn">Sewa Via Whatsapp</a>
        </div>
        
        {{-- MODIFIKASI: Merender seluruh isi galeri dari folder storage ke dalam container slideshow utama --}}
        <div class="katalog-main-slideshow slideshow-container-main">
            @if($katalog->galeri && $katalog->galeri->isNotEmpty())
                @foreach($katalog->galeri as $index => $foto)
                    <img src="{{ asset('storage/' . $foto->path) }}" 
                         alt="{{ $katalog->nama_katalog }}" 
                         class="{{ $index === 0 ? 'active' : '' }}"
                         onerror="this.style.display='none'; document.getElementById('main-fallback').classList.add('active');">
                @endforeach
                <div id="main-fallback" class="inline-error-fallback">
                    <i class="fas fa-image" style="font-size: 3.5rem;"></i>
                </div>
            @else
                <img src="{{ asset('img/images-landing-page/banner_katalog.jpeg') }}" alt="{{ $katalog->nama_katalog }}" class="active">
            @endif
        </div>
        
        <div style="margin-top: 30px; font-family: 'Poppins', sans-serif;">
            <p style="color: #555; line-height: 1.8; text-align: justify;">{{ $katalog->deskripsi }}</p>
            <div style="display: flex; gap: 30px; font-weight: bold; margin-top: 25px;">
                <span style="color: {{ $katalog->status == 'tersedia' ? 'green' : 'red' }};">
                    ● {{ ucfirst($katalog->status) }}
                </span>
                <span style="color: #333;">Stok Alat/Kostum: {{ $katalog->stok }}</span>
            </div>
        </div>
    </div>

    <h2 style="margin-bottom: 25px; font-family: 'Playfair Display', serif; color: #3d0c0c;">Katalog Lainnya</h2>
    <div class="katalog-lain-grid">
        @foreach($katalogLain as $lain)
            <a href="{{ route('umum.katalog-detail', $lain->id) }}" style="text-decoration: none; color: inherit;">
                <div class="lain-card">
                    
                    {{-- MODIFIKASI: Slideshow mini untuk item rekomendasi bawah --}}
                    <div class="lain-card-slideshow slideshow-container-mini">
                        @if($lain->galeri && $lain->galeri->isNotEmpty())
                            @foreach($lain->galeri as $index => $fotoLain)
                                <img src="{{ asset('storage/' . $fotoLain->path) }}" 
                                     alt="{{ $lain->nama_katalog }}" 
                                     class="{{ $index === 0 ? 'active' : '' }}"
                                     onerror="this.style.display='none'; document.getElementById('lain-fallback-{{ $lain->id }}').classList.add('active');">
                            @endforeach
                            <div id="lain-fallback-{{ $lain->id }}" class="inline-error-fallback">
                                <i class="fas fa-image" style="font-size: 1.5rem;"></i>
                            </div>
                        @else
                            <img src="{{ asset('img/images-landing-page/banner_katalog.jpeg') }}" alt="{{ $lain->nama_katalog }}" class="active">
                        @endif
                    </div>

                    <h4 style="margin: 15px 0 5px 0; font-family: 'Poppins', sans-serif; font-weight: 700; color: #111;">{{ $lain->nama_katalog }}</h4>
                    <p style="font-size: 13px; color: #666; line-height: 1.5; margin: 0; font-family: 'Poppins', sans-serif;">{{ Str::limit($lain->deskripsi, 55) }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>

{{-- JAVASCRIPT SLIDESHOW MANAGEMENT SYSTEM --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Automatic Slideshow Gambar Utama (7 Detik)
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

        // 2. Automatic Slideshow Mini-Card Rekomendasi (5 Detik)
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