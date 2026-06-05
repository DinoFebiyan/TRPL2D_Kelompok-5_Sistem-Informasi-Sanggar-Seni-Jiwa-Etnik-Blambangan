@extends('layouts.umum')

@section('title', $berita->judul . ' - Sanggar Tari JEB')

@push('styles')
  <style>
    /* ══ RESET & GLOBAL BODY ELEMENT ══ */
    body {
      background-color: #ffffff;
      margin: 0;
      padding: 0;
    }

    /* ══ 1. HERO BANNER ARTIKEL (ATAS) ══ */
    .news-detail-hero {
      position: relative;
      width: 100%;
      height: 480px; 
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display: flex;
      align-items: flex-end;
      padding-bottom: 4rem;
      background-color: #333;
    }

    .news-detail-hero::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: linear-gradient(rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.85));
      z-index: 1;
    }

    .hero-content-wrap {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 8%;
    }

    .badge-news {
      background-color: #d4af37;
      color: #3d0c0c;
      padding: 0.4rem 1.2rem;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      border-radius: 4px;
      display: inline-block;
      margin-bottom: 1.2rem;
      letter-spacing: 0.5px;
    }

    .news-detail-hero h1 {
      font-family: 'Playfair Display', serif;
      color: #ffffff;
      font-size: 2.8rem;
      line-height: 1.2;
      font-weight: 700;
      margin-bottom: 1.5rem;
      max-width: 900px;
    }

    .news-meta-bar {
      border-top: 1px solid rgba(255, 255, 255, 0.25);
      padding-top: 1.2rem;
      display: flex;
      gap: 2.5rem;
      color: rgba(255, 255, 255, 0.85);
      font-size: 0.9rem;
    }

    .news-meta-bar span {
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    /* ══ 2. KONTEN UTAMA ARTIKEL (TENGAH) ══ */
    .article-container {
      max-width: 950px;
      margin: 0 auto;
      padding: 5rem 24px;
    }

    .article-body {
      font-family: 'Poppins', sans-serif;
      font-size: 1.05rem;
      line-height: 1.9;
      color: #2b2b2b;
    }

    .article-body p {
      margin-bottom: 1.8rem;
      text-align: justify;
    }

    .article-body p:first-of-type::first-letter {
      font-family: 'Playfair Display', serif;
      font-size: 4rem;
      float: left;
      margin-right: 12px;
      line-height: 0.85;
      font-weight: 900;
      color: #3d0c0c;
    }

    .article-body h2, .article-body h3 {
      font-family: 'Playfair Display', serif;
      color: #3d0c0c;
      font-weight: 700;
      margin: 3rem 0 1.2rem 0;
    }
    
    .article-body h2 { font-size: 1.8rem; }
    .article-body h3 { font-size: 1.5rem; }

    /* ══ 3. SECTION BERITA LAIN (BAWAH) ══ */
    .berita-lain-section {
      background-color: #E7DCDA;
      padding: 6rem 8%;
    }

    .berita-lain-header {
      font-family: 'Playfair Display', serif;
      font-size: 2.2rem;
      color: #3d0c0c;
      font-weight: 800;
      margin-bottom: 2.5rem;
    }

    .berita-lain-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
    }

    .lain-card {
      background: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.03);
      text-decoration: none;
      color: inherit;
      display: flex;
      flex-direction: column;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .lain-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    }

    /* MODIFIKASI: Ditambahkan sistem posisi relative & ganti background */
    .lain-img-wrapper {
      height: 160px;
      position: relative;
      background-color: #f3f4f6;
      overflow: hidden;
    }

    /* MODIFIKASI: Gambar diatur absolute dengan transisi opacity seperti di list utama */
    .lain-img-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      position: absolute;
      top: 0; left: 0;
      opacity: 0;
      transition: opacity 0.5s ease-in-out;
      z-index: 1;
    }

    .lain-img-wrapper img.active {
      opacity: 1;
      position: relative; /* Menjaga kestabilan aspek rasio grid layout box */
    }

    .lain-tag {
      position: absolute;
      top: 12px; left: 12px;
      background-color: #3d0c0c;
      color: #ffffff;
      font-size: 0.65rem;
      padding: 0.25rem 0.75rem;
      border-radius: 4px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      z-index: 10; /* Berada di atas tumpukan gambar */
    }

    .lain-content {
      padding: 1.2rem;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
      position: relative;
      z-index: 5;
      background: #fff;
    }

    .lain-content h5 {
      font-family: 'Poppins', sans-serif;
      font-size: 0.95rem;
      font-weight: 700;
      color: #1a1a1a;
      line-height: 1.4;
      margin-bottom: 0.6rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .lain-content p {
      font-size: 0.8rem;
      color: #555555;
      line-height: 1.6;
      margin-bottom: 1.2rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .lain-meta {
      margin-top: auto;
      font-size: 0.75rem;
      color: #888888;
      border-top: 1px solid #f0f0f0;
      padding-top: 0.8rem;
    }

    /* Fallback sistem jika semua gambar eror */
    .inline-error-fallback {
      width: 100%; height: 100%; display: none; flex-direction: column;
      align-items: center; justify-content: center; background: #e5e7eb; color: #9ca3af;
      position: absolute; top: 0; left: 0; z-index: 2;
    }
    .inline-error-fallback.active { display: flex; }

    @media (max-width: 1200px) {
      .berita-lain-grid { grid-template-columns: repeat(2, 1fr); gap: 2rem; }
    }
    @media (max-width: 768px) {
      .news-detail-hero { height: 380px; }
      .news-detail-hero h1 { font-size: 2rem; }
      .news-meta-bar { gap: 0.8rem; flex-direction: column; }
      .berita-lain-grid { grid-template-columns: 1fr; }
    }
  </style>
@endpush

@section('content')

  @php
    // Logika pengamanan gambar utama berita (Hero Atas)
    $urlGambarUtama = asset('img/images-landing-page/banner_berita.jpeg'); 
    
    if ($berita->galeri) {
        if ($berita->galeri instanceof \Illuminate\Support\Collection && $berita->galeri->isNotEmpty()) {
            $urlGambarUtama = asset('storage/' . $berita->galeri->first()->path);
        } elseif (!($berita->galeri instanceof \Illuminate\Support\Collection) && isset($berita->galeri->path)) {
            $urlGambarUtama = asset('storage/' . $berita->galeri->path);
        }
    }
  @endphp

  <div class="news-detail-hero" style="background-image: url('{{ $urlGambarUtama }}');">
    <div class="hero-content-wrap">
      <span class="badge-news">{{ $berita->jenis_berita ?? 'Berita' }}</span>
      <h1>{{ $berita->judul }}</h1>
      
      <div class="news-meta-bar">
        <span>📅 {{ \Carbon\Carbon::parse($berita->tgl_terbit)->translatedFormat('d F Y') }}</span>
        <span>👤 Oleh: {{ $berita->user->name ?? 'Admin JEB' }}</span>
      </div>
    </div>
  </div>

  <div class="article-container">
    <div class="article-body">
      {!! $berita->isi_berita !!}
    </div>
  </div>

  <section class="berita-lain-section">
    <h3 class="berita-lain-header">Berita Lain</h3>
    
    <div class="berita-lain-grid">
      @forelse($beritaLainnya as $lain)
        <a href="{{ route('umum.berita-detail', $lain->id) }}" class="lain-card">
          
          {{-- MODIFIKASI: Container diganti/ditambahkan class .slideshow-container --}}
          <div class="lain-img-wrapper slideshow-container">
            <span class="lain-tag">{{ $lain->jenis_berita ?? 'Kegiatan' }}</span>
            
            @if($lain->galeri && $lain->galeri->isNotEmpty())
              {{-- MODIFIKASI: Looping mengeluarkan seluruh gambar dari database relasi galeri berita terkait --}}
              @foreach($lain->galeri as $index => $foto)
                <img src="{{ asset('storage/' . $foto->path) }}" 
                     alt="{{ $lain->judul }}" 
                     class="{{ $index === 0 ? 'active' : '' }}"
                     onerror="this.style.display='none'; document.getElementById('lain-fallback-{{ $lain->id }}').classList.add('active');">
              @endforeach
              
              <div id="lain-fallback-{{ $lain->id }}" class="inline-error-fallback">
                <i class="fas fa-image" style="font-size: 2rem; margin-bottom: 5px;"></i>
              </div>
            @else
              {{-- Fallback default asset jika data galeri di DB kosong --}}
              <img src="{{ asset('img/images-landing-page/banner_berita.jpeg') }}" alt="{{ $lain->judul }}" class="active">
            @endif
          </div>

          <div class="lain-content">
            <h5>{{ $lain->judul }}</h5>
            <p>{{ Str::limit(strip_tags($lain->isi_berita), 85) }}</p>
            <div class="lain-meta">
              {{ \Carbon\Carbon::parse($lain->tgl_terbit)->translatedFormat('d M Y') }} • {{ $lain->user->name ?? 'Admin' }}
            </div>
          </div>
        </a>
      @empty
        <p style="grid-column: 1/-1; text-align: center; color: #555;">Tidak ada berita lainnya saat ini.</p>
      @endforelse
    </div>
  </section>

  {{-- SCRIPT JAVASCRIPT SLIDESHOW --}}
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        // AUTOMATIC SLIDESHOW BERITA LAIN (Berganti setiap 5 detik)
        const containers = document.querySelectorAll('.slideshow-container');
        containers.forEach(container => {
            const images = container.querySelectorAll('img');
            // Hanya aktifkan interval jika gambar di dalam card lebih dari 1
            if (images.length > 1) {
                let currentIndex = 0;
                setInterval(() => {
                    images[currentIndex].classList.remove('active');
                    currentIndex = (currentIndex + 1) % images.length;
                    images[currentIndex].classList.add('active');
                }, 5000); 
            }
        });
    });
  </script>
@endsection