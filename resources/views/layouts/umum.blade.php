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
    /* ========== GLOBAL RESET & VARIABLES ========== */
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
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.8rem 4%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .nav-logo {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .logo-img-nav {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--white);
    }
    .nav-logo span {
        font-family: 'Playfair Display', serif;
        font-style: italic;
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--white);
        letter-spacing: 1px;
    }
    /* Desktop menu */
    .nav-links {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .nav-links a {
        color: var(--white);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 400;
        transition: color .2s;
    }
    .nav-links a:hover, .nav-links a.active {
        color: var(--gold);
    }
    .btn-login {
        border: 1px solid var(--gold);
        color: var(--gold) !important;
        border-radius: 20px;
        padding: 0.4rem 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
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
        padding: 4rem 8% 2rem;
        border-top: 4px solid var(--gold);
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

    /* ========== RESPONSIF ========== */
    @media (max-width: 900px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        nav {
            padding: 0.8rem 5%;
        }
    }

    @media (max-width: 768px) {
        .nav-links {
            display: none;
        }
        .mobile-menu-btn {
            display: block;
        }
        .mobile-nav.open {
            display: flex;
        }
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
</style>

@stack('styles')
</head>
<body>

<nav>
    <div class="nav-logo">
        <img src="{{ asset('img/images-landing-page/galeri/logo-jeb.jpg') }}" alt="Logo JEB" class="logo-img-nav" />
        <span>JEB</span>
    </div>
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

<footer>
    <div class="footer-grid">
        <div class="footer-brand">
            <h3>Jiwa Etnik Blambangan</h3>
            <p>Sanggar Tari Jiwa Etnik Blambangan adalah pusat pelestarian seni dan budaya tari tradisional Banyuwangi. Kami berdedikasi untuk menggerakkan jiwa, merayakan budaya, dan melestarikan warisan Nusantara.</p>
        </div>
        <div class="footer-links">
            <h4>Tautan Singkat</h4>
            <ul>
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('umum.profil') }}">Profil Sanggar</a></li>
                <li><a href="{{ route('umum.berita') }}">Artikel & Berita</a></li>
                <li><a href="{{ route('umum.event') }}">Kalender Event</a></li>
                <li><a href="{{ route('umum.katalog') }}">Katalog Kostum</a></li>
                <li><a href="{{ route('umum.galeri') }}">Galeri</a></li>
            </ul>
        </div>
        <div class="footer-links">
            <h4>Hubungi Kami</h4>
            <ul>
                <li><a href="https://maps.app.goo.gl/gYaB32Nd5wr9o83h8" target="_blank"><i class="fa-solid fa-map-location-dot"></i> Sanggar Seni Jiwa Etnik Blambangan</a></li>
                <li><a href="https://api.whatsapp.com/send/?phone=6282232505355" target="_blank"><i class="fa-solid fa-phone"></i> +62 822-3250-5355</a></li>
                <li><a href="mailto:mustikasadida@gmail.com"><i class="fa-solid fa-envelope"></i> mustikasadida@gmail.com</a></li>
                <li><a href="https://www.instagram.com/jiwaetnikblambangan" target="_blank"><i class="fa-brands fa-instagram"></i> @jiwaetnikblambangan</a></li>
                <li><a href="https://www.tiktok.com/@jiwaetnikblambangan" target="_blank"><i class="fa-brands fa-tiktok"></i> @jiwaetnikblambangan</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 Sanggar Tari Jiwa Etnik Blambangan. Seluruh Hak Cipta Dilindungi.</p>
    </div>
</footer>

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