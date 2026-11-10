<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
<title>@yield('title', 'Sanggar Tari Jiwa Etnik Blambangan')</title>
{{-- 🟢 KODE FAVICON DINAMIS (DB & ASSET) --}}
    @php
        $globalProfil = \App\Models\ProfilSanggar::with('logo')->first();
        $faviconUrl = ($globalProfil && $globalProfil->logo && $globalProfil->logo->path) 
            ? asset('storage/' . $globalProfil->logo->path) 
            : asset('img/images-landing-page/galeri/logo-jeb.jpg');
    @endphp
    
<link rel="icon" type="image/jpeg" href="{{ $faviconUrl }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
        --maroon-dark: #4a0c0c;
        --red-bg: #b51c1c;
        --red-card: #c42020;
        --gold: #dfb15b;
        --cream: #f8f1e1;
        --text-dark: #333333;
        --text-gray: #666666;
        --white: #ffffff;
    }

    /* ✅ Cegah browser HP membesarkan sebagian teks (text autosizing) */
    html {
        -webkit-text-size-adjust: 100%;
        text-size-adjust: 100%;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--white);
        color: var(--text-dark);
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    body.nav-open {
        overflow: hidden;
    }

    /* ========== NAVBAR ========== */
    nav {
        position: sticky;
        top: 0;
        z-index: 200;
        background: var(--maroon-dark);
        width: 100%;
        box-shadow: 0 4px 15px rgba(223, 177, 91, 0.4),
                    0 2px 6px rgba(0, 0, 0, 0.3);
    }

    nav::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 0;
        width: 100%;
        height: 15px;
        background: linear-gradient(to bottom, rgba(223, 177, 91, 0.6) 0%, rgba(223, 177, 91, 0) 100%);
        pointer-events: none;
    }

    .nav-container {
        width: 100%;
        max-width: 1280px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: nowrap;
        padding: 0.8rem 1.5rem;
    }

    .nav-logo {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-shrink: 0;
    }

    .logo-img-nav {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--white);
    }
    .nav-logo span {
        font-family: 'Playfair Display', serif;
        font-style: italic;
        font-weight: 700;
        font-size: 1.4rem;
        color: var(--white);
        letter-spacing: 1px;
    }

    /* Desktop menu */
    .nav-links {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        flex-shrink: 0;
    }

    .nav-links a {
        color: var(--white);
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 400;
        transition: color .2s;
        white-space: nowrap;
    }

    .nav-links a:hover, .nav-links a.active {
        color: var(--gold);
    }

    .btn-login {
        border: 1px solid var(--gold);
        color: var(--gold) !important;
        border-radius: 20px;
        padding: 0.35rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-shrink: 0;
    }
    .btn-login:hover {
        background: var(--gold);
        color: var(--maroon-dark) !important;
    }

    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        color: var(--white);
        font-size: 1.8rem;
        cursor: pointer;
        padding: 4px 8px;
        transition: color 0.2s;
    }
    .mobile-menu-btn:hover {
        color: var(--gold);
    }

    /* ==========================================
       ✅ MOBILE NAV — POPUP MENU (gaya menu Chrome)
       Muncul di pojok kanan atas, sudut membulat,
       ikon + teks, dan garis pemisah antar grup.
       ========================================== */

    .mobile-nav-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.35);
        z-index: 998;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .mobile-nav-overlay.active {
        display: block;
        opacity: 1;
    }

    .mobile-nav {
        position: fixed;
        top: 10px;
        right: 10px;
        width: min(300px, calc(100vw - 20px));
        max-height: calc(100vh - 20px);
        max-height: calc(100dvh - 20px);
        overflow-y: auto;
        background: #4a0c0c;
        border: 1px solid rgba(223, 177, 91, 0.25);
        border-radius: 24px;
        padding: 0.6rem 0.6rem;
        z-index: 999;
        display: flex;
        flex-direction: column;
        gap: 2px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.45),
                    0 2px 8px rgba(0, 0, 0, 0.3);

        /* animasi buka: membesar dari pojok kanan atas */
        transform-origin: top right;
        transform: scale(0.85);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: transform 0.18s ease-out, opacity 0.18s ease-out, visibility 0.18s;
    }

    .mobile-nav.open {
        transform: scale(1);
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .mobile-nav a {
        color: var(--white);
        text-decoration: none;
        padding: 0.85rem 1rem;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-radius: 14px;
        transition: background 0.15s, color 0.15s;
        flex-shrink: 0;
    }

    .mobile-nav a i {
        width: 22px;
        text-align: center;
        font-size: 1.05rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .mobile-nav a:hover,
    .mobile-nav a:active {
        background: rgba(255, 255, 255, 0.08);
    }

    .mobile-nav a.active {
        color: var(--gold);
        background: rgba(223, 177, 91, 0.15);
        font-weight: 600;
    }

    .mobile-nav a.active i {
        color: var(--gold);
    }

    /* garis pemisah antar grup menu */
    .mobile-nav-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.15);
        margin: 0.35rem 1rem;
        flex-shrink: 0;
    }

    .mobile-nav .btn-login-mobile {
        color: var(--gold);
    }

    .mobile-nav .btn-login-mobile i {
        color: var(--gold);
    }

    /* ========== FOOTER ========== */
    footer {
        background: var(--maroon-dark);
        color: var(--white);
        padding: 3.5rem 1.5rem 2rem;
        border-top: 4px solid var(--gold);
        width: 100%;
    }

    .footer-container {
        max-width: 1280px;
        margin: 0 auto;
        width: 100%;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }

    .footer-brand h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--gold);
        margin-bottom: 1rem;
    }

    .footer-brand p {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.8;
        max-width: 400px;
    }

    .footer-links h4 {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        margin-bottom: 1.2rem;
        color: var(--white);
    }

    .footer-links ul {
        list-style: none;
    }

    .footer-links ul li {
        margin-bottom: 0.8rem;
    }

    .footer-links ul li a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        font-size: 0.85rem;
        transition: color 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-links ul li a:hover {
        color: var(--gold);
    }

    .footer-bottom {
        text-align: center;
        padding-top: 2rem;
        border-top: 1px solid rgba(255,255,255,0.1);
        font-size: 0.8rem;
        color: rgba(255,255,255,0.5);
    }

    /* ==========================================
       CATATAN: Style isi artikel (font-size, tabel,
       blockquote, heading) SENGAJA tidak diatur di
       layout ini. Semuanya diatur di
       resources/views/umum/berita/detail.blade.php
       lewat variabel --fs / --lh pada .article-body.
       ========================================== */

    /* ========== RESPONSIF DASAR ========== */
    @media (max-width: 850px) {
        .nav-links {
            display: none;
        }
        .mobile-menu-btn {
            display: block;
        }
    }

    @media (min-width: 851px) {
        .mobile-nav {
            display: none !important;
        }
        .mobile-nav-overlay {
            display: none !important;
        }
    }

    @media (max-width: 900px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }

    @media (max-width: 768px) {
        .footer-grid {
            text-align: center;
        }
        .footer-brand p {
            margin: 0 auto;
        }
    }

    @media (max-width: 480px) {
        .nav-logo span {
            font-size: 1.2rem;
        }
        .logo-img-nav {
            width: 38px;
            height: 38px;
        }
        footer {
            padding: 2rem 5% 1.5rem;
        }
    }

    /* ==========================================
       ✅ RESPONSIVE — MOBILE & TABLET
       ========================================== */
    @media (max-width: 768px) {
        /* Container global */
        .container,
        .detail-container,
        .katalog-wrapper,
        .galeri-wrapper,
        .berita-wrapper,
        .event-wrapper {
            width: 95% !important;
            padding: 0 12px !important;
        }

        /* Hero banner */
        .berita-banner-container {
            height: 280px !important;
        }
        .hero-title {
            font-size: 2rem !important;
        }
        .hero-subtitle-top {
            font-size: 0.75rem !important;
        }

        /* Judul section */
        .section-title,
        .title-maroon,
        .section-title-center {
            font-size: 1.5rem !important;
        }

        /* Grid Katalog */
        .katalog-grid {
            grid-template-columns: 1fr !important;
            gap: 20px !important;
        }

        /* Grid Berita */
        .berita-grid {
            grid-template-columns: 1fr !important;
            gap: 20px !important;
        }
        .berita-main {
            min-height: auto !important;
            padding: 1.5rem !important;
        }
        .berita-main h3 {
            font-size: 1.3rem !important;
        }

        /* Galeri mosaik */
        .galeri-mosaik {
            grid-template-columns: repeat(2, 1fr) !important;
            grid-auto-rows: 160px !important;
        }

        /* Grid Pengurus & Pelatih */
        .cards-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 15px !important;
        }
        .avatar-wrapper {
            height: 160px !important;
        }

        /* Layout event split */
        .event-split-layout {
            grid-template-columns: 1fr !important;
            gap: 30px !important;
        }

        /* Search & filter bar */
        .search-filter-wrapper {
            flex-direction: column !important;
            gap: 12px !important;
        }
        .btn-trigger-filter {
            width: 100% !important;
            justify-content: center !important;
        }
        .search-box-container input {
            font-size: 0.9rem !important;
        }

        /* Pagination */
        .news-pagination {
            flex-direction: column !important;
            gap: 15px !important;
            align-items: center !important;
        }

        /* Tombol aksi */
        .btn-dark-pill,
        .btn-gray-pill,
        .btn-dark-red-pill,
        .btn-red-pill {
            padding: 0.6rem 1.2rem !important;
            font-size: 0.85rem !important;
        }

        /* Halaman Profil Sanggar */
        .deskripsi-grid,
        .sambutan-row,
        .sejarah-row {
            grid-template-columns: 1fr !important;
            gap: 1.5rem !important;
        }
        .sejarah-logo {
            width: 120px !important;
            height: 120px !important;
            margin: 0 auto !important;
        }
        .pembina-image {
            height: 240px !important;
        }
        .vm-card {
            padding: 1rem !important;
        }

        /* Halaman Detail Event */
        .event-info-body {
            padding: 20px !important;
        }
        .event-header-row {
            flex-direction: column !important;
            gap: 15px !important;
        }
        .event-time-place {
            text-align: left !important;
        }
        .lain-slideshow-box {
            width: 100% !important;
            height: 180px !important;
        }
        .event-lain-grid {
            grid-template-columns: 1fr !important;
        }

        /* Halaman Detail Berita */
        .article-container {
            padding: 2rem 16px !important;
        }
        .berita-lain-grid {
            grid-template-columns: 1fr !important;
        }

        /* Halaman Detail Galeri */
        .hero-image-box {
            height: 350px !important;
        }
        .info-header-row {
            flex-direction: column !important;
            gap: 15px !important;
        }
        .info-meta-right {
            text-align: left !important;
        }
        .other-gallery-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }

        /* Halaman Detail Katalog */
        .katalog-main-slideshow {
            height: 280px !important;
        }
        .detail-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 15px !important;
        }

        /* Visi & Misi */
        .visi-card,
        .misi-card {
            padding: 20px !important;
            margin: 0 0 20px 0 !important;
        }
        .visi-card h3,
        .misi-card h3 {
            font-size: 1.3rem !important;
        }

        /* Slideshow gambar */
        .slideshow-container,
        .lain-card-slideshow,
        .news-foto-wrapper,
        .event-slideshow-box {
            height: auto !important;
            aspect-ratio: 4 / 3 !important;
        }

        /* ==========================================
           ✅ FIX: HERO JUDUL PANJANG TIDAK KEPOTONG
           ========================================== */
        .news-detail-hero,
        .event-image-wrapper,
        .hero-section {
            height: auto !important;
            min-height: 400px !important;
            padding: 110px 20px 50px !important;
            display: flex !important;
            align-items: flex-end !important;
            box-sizing: border-box !important;
        }

        .news-detail-hero h1,
        .event-title,
        .hero-title {
            font-size: 1.5rem !important;
            line-height: 1.35 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            hyphens: auto !important;
            max-width: 100% !important;
            margin-bottom: 15px !important;
        }

        .news-detail-hero > div,
        .event-image-wrapper > div,
        .hero-content {
            width: 100% !important;
            max-width: 100% !important;
            position: relative !important;
            box-sizing: border-box !important;
        }

        .news-detail-hero .meta-info,
        .event-info-meta {
            font-size: 0.78rem !important;
            flex-wrap: wrap !important;
            gap: 8px !important;
        }
    }

    /* Untuk layar sangat kecil (<=480px) */
    @media (max-width: 480px) {
        .galeri-mosaik {
            grid-template-columns: 1fr !important;
            grid-auto-rows: 200px !important;
        }
        .cards-grid {
            grid-template-columns: 1fr !important;
        }
        .other-gallery-grid {
            grid-template-columns: 1fr !important;
        }
        .berita-banner-content h1 {
            font-size: 2rem !important;
        }
        .section-title {
            font-size: 1.3rem !important;
        }
        .badge-theme,
        .badge-pill {
            font-size: 0.7rem !important;
            padding: 4px 12px !important;
        }

        .news-detail-hero h1,
        .event-title,
        .hero-title {
            font-size: 1.25rem !important;
            line-height: 1.3 !important;
        }

        .news-detail-hero,
        .event-image-wrapper,
        .hero-section {
            min-height: 350px !important;
            padding: 100px 16px 40px !important;
        }
    }

    /* Agar tabel (jika ada) bisa di-scroll horizontal */
    .table-responsive-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>

@stack('styles')
</head>
<body>

<nav>
    <div class="nav-container">
    <!-- Logo di Kiri -->
    <div class="nav-logo">
        @if(isset($profil) && $profil->logo && $profil->logo->path)
            <img src="{{ asset('storage/' . $profil->logo->path) }}" alt="Logo JEB" class="logo-img-nav" />
        @else
            <img src="{{ asset('img/images-landing-page/galeri/logo-jeb.jpg') }}" alt="Logo JEB" class="logo-img-nav" />
        @endif
        <span>JEB</span>
    </div>

        <!-- Links di Kanan -->
        <div class="nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('umum.profil') }}" class="{{ request()->routeIs('umum.profil') ? 'active' : '' }}">Profile Sanggar</a>
            <a href="{{ route('umum.berita') }}" class="{{ request()->routeIs('umum.berita*') ? 'active' : '' }}">Artikel/Berita</a>
            <a href="{{ route('umum.event') }}" class="{{ request()->routeIs('umum.event*') ? 'active' : '' }}">Kalender Event</a>
            <a href="{{ route('umum.katalog') }}" class="{{ request()->routeIs('umum.katalog*') ? 'active' : '' }}">Katalog Kostum</a>
            <a href="{{ route('umum.galeri') }}" class="{{ request()->routeIs('umum.galeri*') ? 'active' : '' }}">Galeri</a>
            <a href="{{ route('login') }}" class="btn-login">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                </svg>
                Login
            </a>
        </div>

        <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

{{-- ✅ Overlay gelap --}}
<div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

{{-- ✅ Popup menu (gaya menu Chrome) --}}
<div class="mobile-nav" id="mobileNav" role="menu">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fas fa-home"></i> Beranda
    </a>
    <a href="{{ route('umum.profil') }}" class="{{ request()->routeIs('umum.profil') ? 'active' : '' }}">
        <i class="fas fa-info-circle"></i> Profile Sanggar
    </a>

    <div class="mobile-nav-divider"></div>

    <a href="{{ route('umum.berita') }}" class="{{ request()->routeIs('umum.berita*') ? 'active' : '' }}">
        <i class="fas fa-newspaper"></i> Artikel/Berita
    </a>
    <a href="{{ route('umum.event') }}" class="{{ request()->routeIs('umum.event*') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i> Kalender Event
    </a>

    <div class="mobile-nav-divider"></div>

    <a href="{{ route('umum.katalog') }}" class="{{ request()->routeIs('umum.katalog*') ? 'active' : '' }}">
        <i class="fas fa-tshirt"></i> Katalog Kostum
    </a>
    <a href="{{ route('umum.galeri') }}" class="{{ request()->routeIs('umum.galeri*') ? 'active' : '' }}">
        <i class="fas fa-images"></i> Galeri
    </a>

    <div class="mobile-nav-divider"></div>

    <a href="{{ route('login') }}" class="btn-login-mobile">
        <i class="fas fa-sign-in-alt"></i> Login
    </a>
</div>

<main>
    @yield('content')
</main>

{{-- Memanggil Partial Layout Footer --}}
@include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        const overlay = document.getElementById('mobileNavOverlay');

        if (!menuBtn || !mobileNav || !overlay) return;

        function openMenu() {
            mobileNav.classList.add('open');
            overlay.classList.add('active');
            document.body.classList.add('nav-open');
        }

        function closeMenu() {
            mobileNav.classList.remove('open');
            overlay.classList.remove('active');
            document.body.classList.remove('nav-open');
        }

        function toggleMenu() {
            if (mobileNav.classList.contains('open')) {
                closeMenu();
            } else {
                openMenu();
            }
        }

        menuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleMenu();
        });

        overlay.addEventListener('click', closeMenu);

        mobileNav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeMenu);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 850) closeMenu();
        });
    });
</script>

@stack('scripts')
</body>
</html>