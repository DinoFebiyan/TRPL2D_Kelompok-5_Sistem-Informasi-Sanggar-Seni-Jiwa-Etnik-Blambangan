<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
<title>@yield('title', 'Sanggar Tari Jiwa Etnik Blambangan')</title>

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
    body {
        font-family: 'Poppins', sans-serif;
        background: var(--white);
        color: var(--text-dark);
        overflow-x: hidden;
        scroll-behavior: smooth;
    }

    /* ========== NAVBAR ========== */
    nav {
        position: sticky;
        top: 0;
        z-index: 200;
        background: var(--maroon-dark);
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        width: 100%;
    }

    /* Container pembatas lebar */
    .nav-container {
        width: 100%;
        max-width: 1280px; /* Diperlebar sedikit agar lebih muat */
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: nowrap;
        padding: 0.8rem 1.5rem; /* Padding disesuaikan */
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
        gap: 0.8rem; /* Jarak antar-menu dirapatkan */
        flex-shrink: 0;
    }

    .nav-links a {
        color: var(--white);
        text-decoration: none;
        font-size: 0.82rem; /* Ukuran font disesuaikan */
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

    /* Mobile menu button */
    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        color: var(--white);
        font-size: 1.8rem;
        cursor: pointer;
    }
    /* Mobile menu container */
    .mobile-nav {
        display: none;
        flex-direction: column;
        background: var(--maroon-dark);
        padding: 1rem 4%;
        gap: 0.8rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    .mobile-nav a {
        color: var(--white);
        text-decoration: none;
        padding: 0.5rem 0;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .mobile-nav a.active, .mobile-nav a:hover {
        color: var(--gold);
    }
    .mobile-nav .btn-login-mobile {
        border: 1px solid var(--gold);
        border-radius: 20px;
        padding: 0.5rem 1rem;
        justify-content: center;
        margin-top: 0.5rem;
    }

    /* ========== FOOTER ========== */
    footer {
        background: var(--maroon-dark);
        color: var(--white);
        padding: 3.5rem 1.5rem 2rem; /* Padding samping disesuaikan */
        border-top: 4px solid var(--gold);
        width: 100%;
    }

    /* Container pembatas agar footer tepat berada di tengah */
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

    /* ========== RESPONSIF DASAR ========== */
    /* Breakpoint diubah ke 800px agar menu otomatis masuk mode mobile sebelum berhimpitan */
    @media (max-width: 850px) {
        .nav-links {
            display: none;
        }
        .mobile-menu-btn {
            display: block;
        }
        .mobile-nav.open {
            display: flex;
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

        /* Grid Katalog (beranda & halaman katalog) */
        .katalog-grid {
            grid-template-columns: 1fr !important;
            gap: 20px !important;
        }

        /* Grid Berita (beranda & halaman berita) */
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

        /* Galeri mosaik (beranda) */
        .galeri-mosaik {
            grid-template-columns: repeat(2, 1fr) !important;
            grid-auto-rows: 160px !important;
        }

        /* Grid Pengurus & Pelatih (halaman profil) */
        .cards-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 15px !important;
        }
        .avatar-wrapper {
            height: 160px !important;
        }

        /* Layout event split (halaman event) */
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
        .event-image-wrapper {
            height: 280px !important;
        }
        .event-info-body {
            padding: 20px !important;
        }
        .event-title {
            font-size: 1.8rem !important;
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
        .news-detail-hero {
            height: 300px !important;
        }
        .news-detail-hero h1 {
            font-size: 1.8rem !important;
        }
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

        /* Visi & Misi (card) */
        .visi-card,
        .misi-card {
            padding: 20px !important;
            margin: 0 0 20px 0 !important;
        }
        .visi-card h3,
        .misi-card h3 {
            font-size: 1.3rem !important;
        }

        /* Slideshow gambar (agar tidak overflow) */
        .slideshow-container,
        .lain-card-slideshow,
        .news-foto-wrapper,
        .event-slideshow-box {
            height: auto !important;
            aspect-ratio: 4 / 3 !important;
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
        .hero-title {
            font-size: 1.8rem !important;
        }
        .berita-banner-content h1 {
            font-size: 2rem !important;
        }
        .event-title {
            font-size: 1.5rem !important;
        }
        .section-title {
            font-size: 1.3rem !important;
        }
        .badge-theme,
        .badge-pill {
            font-size: 0.7rem !important;
            padding: 4px 12px !important;
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
            <img src="{{ asset('img/images-landing-page/galeri/logo-jeb.jpg') }}" alt="Logo JEB" class="logo-img-nav" />
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

        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<div class="mobile-nav" id="mobileNav">
    <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
    <a href="{{ route('umum.profil') }}"><i class="fas fa-info-circle"></i> Profile Sanggar</a>
    <a href="{{ route('umum.berita') }}"><i class="fas fa-newspaper"></i> Artikel/Berita</a>
    <a href="{{ route('umum.event') }}"><i class="fas fa-calendar-alt"></i> Kalender Event</a>
    <a href="{{ route('umum.katalog') }}"><i class="fas fa-tshirt"></i> Katalog Kostum</a>
    <a href="{{ route('umum.galeri') }}"><i class="fas fa-images"></i> Galeri</a>
    <a href="{{ route('login') }}" class="btn-login-mobile"><i class="fas fa-sign-in-alt"></i> Login</a>
</div>

<main>
    @yield('content')
</main>

{{-- Memanggil Partial Layout Footer --}}
@include('partials.footer')

<script>
    // Mobile menu toggle
    const menuBtn = document.getElementById('mobileMenuBtn');
    const mobileNav = document.getElementById('mobileNav');
    if (menuBtn && mobileNav) {
        menuBtn.addEventListener('click', function() {
            mobileNav.classList.toggle('open');
        });
    }
</script>

@stack('scripts')
</body>
</html>