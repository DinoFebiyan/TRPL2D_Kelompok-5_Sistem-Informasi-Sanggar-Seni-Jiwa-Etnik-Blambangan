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
      height: auto !important;
      min-height: 480px;
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
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
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
      word-wrap: break-word;
      overflow-wrap: break-word;
      word-break: break-word;
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

    /* ==========================================
       ✅ UKURAN FONT TERPUSAT (CSS VARIABLE)
       Ubah ukuran cukup di sini saja.
       Semua elemen (termasuk table, blockquote,
       heading) mengikuti --fs dan --lh.
       ========================================== */
    .article-body {
      --fs: 1.05rem;
      --lh: 1.9;
    }

    .article-body,
    .article-body *,
    .article-body table,
    .article-body table *,
    .article-body blockquote,
    .article-body blockquote * {
      font-family: 'Poppins', sans-serif !important;
      font-size: var(--fs) !important;
      line-height: var(--lh) !important;
    }

    /* Heading: ukuran relatif terhadap --fs */
    .article-body h1, .article-body h1 * { font-size: calc(var(--fs) * 1.7) !important; line-height: 1.35 !important; }
    .article-body h2, .article-body h2 * { font-size: calc(var(--fs) * 1.5) !important; line-height: 1.35 !important; }
    .article-body h3, .article-body h3 * { font-size: calc(var(--fs) * 1.3) !important; line-height: 1.35 !important; }
    .article-body h4, .article-body h4 * { font-size: calc(var(--fs) * 1.15) !important; line-height: 1.4 !important; }

    /* <em>, <strong>, <a> : ukuran ikut induknya */
    .article-body em,
    .article-body i,
    .article-body em *,
    .article-body i *,
    .article-body strong,
    .article-body b,
    .article-body strong *,
    .article-body b *,
    .article-body a,
    .article-body a * {
      font-size: inherit !important;
      line-height: inherit !important;
    }

    .article-body em,
    .article-body i,
    .article-body em *,
    .article-body i * {
      font-style: italic !important;
    }

    .article-body strong,
    .article-body b,
    .article-body strong *,
    .article-body b * {
      font-weight: 700 !important;
    }

    /* Paragraf */
    .article-body p {
      margin-bottom: 1.8rem;
      text-align: justify;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }

    /* List */
    .article-body ul,
    .article-body ol {
      margin: 1rem 0 1.8rem 1.8rem;
      padding-left: 0.5rem;
    }

    .article-body li {
      margin-bottom: 0.8rem;
      text-align: left;
    }

    /* Link */
    .article-body a,
    .article-body a * {
      color: #b51c1c !important;
      text-decoration: underline !important;
    }

    /* Image */
    .article-body img {
      max-width: 100% !important;
      height: auto !important;
      border-radius: 8px;
      margin: 1rem 0;
    }

    /* Blockquote */
    .article-body blockquote {
      border-left: 4px solid #d4af37;
      padding: 0.8rem 1.2rem;
      margin: 1.5rem 0;
      background: #fdf8ee;
      color: #555;
      border-radius: 0 8px 8px 0;
    }

    .article-body blockquote,
    .article-body blockquote * {
      font-style: italic !important;
    }

    /* Table */
    .article-body table {
      width: 100%;
      border-collapse: collapse;
      margin: 1.5rem 0;
    }

    .article-body table th,
    .article-body table td {
      border: 1px solid #e0d5b8;
      padding: 8px 12px;
      text-align: left;
    }

    .article-body table th {
      background: #fdf8ee;
      font-weight: 700 !important;
      color: #3d0c0c;
    }

    /* Drop Cap */
    .article-body p:first-of-type::first-letter {
      font-family: 'Playfair Display', serif !important;
      font-size: 4rem !important;
      float: left !important;
      margin-right: 12px !important;
      line-height: 0.85 !important;
      font-weight: 900 !important;
      color: #3d0c0c !important;
    }

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
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
      text-decoration: none;
      color: inherit;
      display: flex;
      flex-direction: column;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .lain-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
    }

    .lain-img-wrapper {
      height: 160px;
      position: relative;
      background-color: #f3f4f6;
      overflow: hidden;
    }

    .lain-img-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      position: absolute;
      top: 0;
      left: 0;
      opacity: 0;
      transition: opacity 0.5s ease-in-out;
      z-index: 1;
    }

    .lain-img-wrapper img.active {
      opacity: 1;
      position: relative;
    }

    .lain-tag {
      position: absolute;
      top: 12px;
      left: 12px;
      background-color: #3d0c0c;
      color: #ffffff;
      font-size: 0.65rem;
      padding: 0.25rem 0.75rem;
      border-radius: 4px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      z-index: 10;
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
      top: 0;
      left: 0;
      z-index: 2;
    }

    .inline-error-fallback.active {
      display: flex;
    }

    /* ══ TABLET ══ */
    @media (max-width: 1200px) {
      .berita-lain-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
      }
    }

    /* ══ MOBILE ══ */
    @media (max-width: 768px) {
      .news-detail-hero {
        min-height: 400px !important;
        padding: 100px 0 40px !important;
      }

      .hero-content-wrap {
        padding: 0 6% !important;
      }

      .news-detail-hero h1 {
        font-size: 1.5rem !important;
        line-height: 1.35 !important;
        margin-bottom: 1rem !important;
      }

      .news-meta-bar {
        gap: 0.8rem !important;
        flex-direction: column !important;
        font-size: 0.8rem !important;
        padding-top: 1rem !important;
      }

      .article-container {
        padding: 2.5rem 16px !important;
      }

      /* Cukup ganti variabel, semua elemen ikut */
      .article-body {
        --fs: 0.95rem;
        --lh: 1.75;
      }

      .article-body p,
      .article-body li {
        text-align: left !important;
        word-break: normal !important;
      }

      .article-body table,
      .article-body table * {
        white-space: nowrap !important;
      }

      .article-body p:first-of-type::first-letter {
        font-size: 3rem !important;
        margin-right: 8px !important;
      }

      .berita-lain-grid {
        grid-template-columns: 1fr !important;
      }

      .berita-lain-section {
        padding: 4rem 6% !important;
      }

      .berita-lain-header {
        font-size: 1.6rem !important;
      }
    }

    /* ══ EXTRA SMALL ══ */
    @media (max-width: 480px) {
      .news-detail-hero {
        min-height: 350px !important;
        padding: 90px 0 30px !important;
      }

      .news-detail-hero h1 {
        font-size: 1.25rem !important;
        line-height: 1.3 !important;
      }

      .article-container {
        padding: 2rem 14px !important;
      }

      .article-body {
        --fs: 0.9rem;
        --lh: 1.7;
      }

      .article-body p:first-of-type::first-letter {
        font-size: 2.5rem !important;
      }
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

          <div class="lain-img-wrapper slideshow-container">
            <span class="lain-tag">{{ $lain->jenis_berita ?? 'Kegiatan' }}</span>

            @if($lain->galeri && $lain->galeri->isNotEmpty())
              @foreach($lain->galeri as $index => $foto)
                <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $lain->judul }}"
                  class="{{ $index === 0 ? 'active' : '' }}"
                  onerror="this.style.display='none'; document.getElementById('lain-fallback-{{ $lain->id }}').classList.add('active');">
              @endforeach

              <div id="lain-fallback-{{ $lain->id }}" class="inline-error-fallback">
                <i class="fas fa-image" style="font-size: 2rem; margin-bottom: 5px;"></i>
              </div>
            @else
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

  {{-- SCRIPT: STRIP INLINE STYLE + SLIDESHOW --}}
  <script>
    document.addEventListener("DOMContentLoaded", function () {

      /* ==========================================
         ✅ STRIP inline style + atribut ukuran lama
         + ganti tag <font> jadi <span>
         Berlaku di .article-body
         ========================================== */
      const articleBody = document.querySelector('.article-body');
      if (articleBody) {
        // Ganti tag <font> (obsolete) jadi <span> dulu
        articleBody.querySelectorAll('font').forEach(function (el) {
          const span = document.createElement('span');
          span.innerHTML = el.innerHTML;
          if (el.parentNode) {
            el.parentNode.replaceChild(span, el);
          }
        });

        // Hapus style inline & atribut ukuran bawaan paste dari Word/Docs
        articleBody.querySelectorAll('*').forEach(function (el) {
          el.removeAttribute('style');
          el.removeAttribute('size');
          el.removeAttribute('face');
        });
      }

      /* ==========================================
         SLIDESHOW "BERITA LAIN"
         ========================================== */
      const containers = document.querySelectorAll('.slideshow-container');
      containers.forEach(container => {
        const images = container.querySelectorAll('img');
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