@extends('layouts.umum')

@section('title', 'Beranda - Sanggar Tari JEB')

@push('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* ══ RESET & GLOBAL VARIABLES fallback ══ */
    :root {
      --maroon-dark: #6b1414;
      --maroon-light: #a71818;
      --gold: #f1c40f;
      --white: #ffffff;
      --text-dark: #333333;
      --text-gray: #555555;
    }

    section {
      padding: 5rem 8%;
    }

    .section-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    /* ══ HERO ══ */
    #beranda {
      background: linear-gradient(135deg, var(--maroon-light) 0%, #c42020 100%);
      min-height: 90vh;
      display: flex;
      align-items: center;
      padding: 4rem 8%;
    }

    .hero-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      gap: 2rem;
      width: 100%;
    }

    .hero-text {
      text-align: center;
    }

    .hero-text h1 {
      font-family: 'Playfair Display', serif;
      font-weight: 900;
      color: var(--white);
      font-size: 3rem;
      line-height: 1.1;
      margin-bottom: 0.5rem;
    }

    .hero-text h1 .script {
      display: block;
      font-family: 'Brush Script MT', cursive;
      font-style: italic;
      font-weight: 400;
      color: var(--gold);
      font-size: 5.5rem;
      line-height: 0.9;
      margin: 0.2rem 0 1.2rem 0;
    }

    .hero-text p {
      font-family: 'Poppins', sans-serif;
      color: rgba(255, 255, 255, 0.95);
      font-size: 0.95rem;
      line-height: 1.5;
      max-width: 450px;
      margin: 0 auto 2rem auto;
      font-weight: 400;
    }

    .btn-gray-pill {
      font-family: 'Poppins', sans-serif;
      background: #e0e0e0;
      color: #b51c1c;
      font-weight: 700;
      font-size: 1rem;
      padding: 0.8rem 2.5rem;
      border-radius: 30px;
      text-decoration: none;
      display: inline-block;
      transition: transform .2s, background-color 0.2s;
    }

    .btn-gray-pill:hover {
      transform: translateY(-3px);
      background: var(--white);
    }

    .hero-logo-wrap {
      display: flex;
      justify-content: flex-end;
    }

    .logo-img-hero {
      width: clamp(280px, 40vw, 400px);
      height: clamp(280px, 40vw, 400px);
      border-radius: 50%;
      object-fit: cover;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      background: var(--white);
      border: 8px solid var(--white);
    }

    /* ══ GLOBAL SECTIONS ══ */
    .badge-pill {
      display: inline-block;
      background: var(--maroon-dark);
      color: var(--white);
      font-size: 0.7rem;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      padding: 0.3rem 1.2rem;
      border-radius: 20px;
      margin-bottom: 0.8rem;
    }

    .section-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--maroon-dark);
      margin-bottom: 0.5rem;
    }

    .section-subtitle {
      font-size: 0.9rem;
      color: var(--text-gray);
    }

    .section-title-center {
        font-family: 'Playfair Display', serif; 
        color: var(--maroon-dark); 
        font-size: 1.8rem; 
        font-weight: 700; 
        margin-top: 0.5rem; 
        margin-bottom: 0.5rem;
    }

    /* ══ GARIS PEMBATAS DEKORATIF ══ */
    .section-divider {
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      width: 80%;
      max-width: 900px;
    }
    .section-divider::before, .section-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(to var(--direction, right), transparent, #d4af37);
    }
    .section-divider::after {
      --direction: left;
    }
    .section-divider i {
      color: #d4af37;
      margin: 0 15px;
      font-size: 1.2rem;
    }

    /* ══ PROFILE ══ */
    #profile {
      background: var(--white);
      padding-bottom: 3rem;
    }

    .profile-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      align-items: center;
    }

    .profile-text h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--maroon-dark);
      margin-bottom: 1.2rem;
    }

    .profile-text p {
      font-size: 0.95rem;
      line-height: 1.8;
      color: var(--text-dark);
      margin-bottom: 1rem;
    }

    .profile-card {
      background: #7a1212;
      border-radius: 20px;
      padding: 3rem 2rem;
      text-align: center;
      color: var(--white);
      position: relative;
      margin-top: 4rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }

    .profile-card-img {
      width: 180px;
      height: 180px;
      object-fit: cover;
      position: absolute;
      top: -100px;
      left: 50%;
      transform: translateX(-50%);
      border-radius: 50%;
      border: 5px solid var(--white);
      background: #fff;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .profile-card h4 {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--gold);
      margin: 5rem 0 0.5rem;
    }

    .profile-card .role-title {
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--gold);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 1rem;
    }

    .profile-card p {
      font-size: 0.9rem;
      line-height: 1.7;
      color: rgba(255, 255, 255, 0.9);
      font-style: italic;
    }

    .center-action {
      display: flex;
      justify-content: center;
      margin-top: 40px;
      width: 100%;
    }

    /* ══ KATALOG KOSTUM ══ */
    #katalog {
      background: #f4eae6;
    }

    .white {
      color: var(--maroon-dark) !important;
    }

    .katalog-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
      margin-bottom: 3rem;
    }

    .katalog-link-card {
      text-decoration: none;
      color: inherit;
      display: block;
    }

    .katalog-card {
      background: var(--white);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s, box-shadow 0.3s;
      border: 1px solid #eee;
    }
    .katalog-link-card:hover .katalog-card {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .katalog-img {
      height: 220px;
      background: linear-gradient(to bottom, #b3b3b3, #e5e5e5);
      position: relative;
    }

    .katalog-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .katalog-img .badge {
      position: absolute;
      top: 1.2rem;
      left: 1.2rem;
      background: var(--gold);
      color: #333;
      font-size: 0.75rem;
      font-weight: 700;
      padding: 0.3rem 1rem;
      border-radius: 20px;
    }

    .katalog-info {
      padding: 1.5rem;
    }

    .katalog-info h4 {
      font-family: 'Poppins', sans-serif;
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
    }

    .katalog-info p {
      font-size: 0.85rem;
      color: var(--text-gray);
      margin-bottom: 1.5rem;
      line-height: 1.6;
    }

    .katalog-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top: 1px solid #eee;
      padding-top: 1rem;
    }

    .katalog-footer .tipe {
      font-size: 0.9rem;
      font-weight: 700;
    }

    .katalog-actions {
      display: flex;
      gap: 0.5rem;
    }

    .katalog-actions span {
      font-size: 0.8rem;
      font-family: 'Poppins', sans-serif;
      color: #555;
    }

    .btn-dark-red-pill {
      background: var(--maroon-dark);
      color: var(--white);
      padding: 0.8rem 2.5rem;
      border-radius: 30px;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 600;
      transition: background 0.3s;
    }

    .btn-dark-red-pill:hover {
      background: #5c0d0d;
    }

    /* ══ BERITA ══ */
    #berita {
      background: var(--white);
    }

    .berita-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
      margin-bottom: 2rem;
    }

    .berita-main {
      background: var(--maroon-dark);
      border-radius: 12px;
      padding: 2.5rem;
      display: flex;
      flex-direction: column;
      min-height: 380px;
    }

    .badge-yellow {
      align-self: flex-start;
      margin-bottom: auto;
      background: var(--gold);
      color: var(--maroon-dark);
      font-size: 0.75rem;
      font-weight: 700;
      padding: 0.4rem 1.2rem;
      border-radius: 20px;
      text-transform: uppercase;
    }

    .berita-main h3 {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      color: var(--white);
      font-size: 1.6rem;
      margin-bottom: 1.2rem;
      line-height: 1.4;
      margin-top: 1.5rem;
    }

    .berita-main p {
      color: rgba(255, 255, 255, 0.7);
      font-size: 0.85rem;
      line-height: 1.6;
      margin-bottom: 2rem;
    }

    .meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .meta span {
      color: rgba(255, 255, 255, 0.5);
      font-size: 0.8rem;
    }

    .btn-red-pill {
      background: #8b1010;
      color: var(--white);
      text-decoration: none;
      padding: 0.6rem 1.5rem;
      border-radius: 25px;
      font-size: 0.85rem;
      font-weight: 600;
      transition: background 0.3s;
    }

    .btn-red-pill:hover {
      background: #6b0808;
    }

    .berita-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .berita-item {
      display: flex;
      gap: 1rem;
      background: #f9f9f9;
      border-radius: 12px;
      border: 1px solid #eee;
      overflow: hidden;
      transition: background-color 0.2s;
    }
    .berita-list a:hover .berita-item {
      background: #f1f1f1;
    }

    .berita-img-placeholder {
      width: 100px;
      background: var(--maroon-dark);
    }

    .berita-item-content {
      padding: 1rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .berita-item-content .tag {
      color: var(--maroon-dark);
      font-size: 0.7rem;
      font-weight: 600;
      margin-bottom: 0.3rem;
      text-transform: uppercase;
    }

    .berita-item-content h5 {
      font-family: 'Poppins', sans-serif;
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 0.3rem;
    }

    .berita-item-content span {
      font-size: 0.75rem;
      color: #999;
    }

    .btn-dark-pill {
      background: var(--maroon-dark);
      color: var(--white);
      padding: 0.6rem 2rem;
      border-radius: 30px;
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 500;
      transition: background-color 0.2s;
    }
    .btn-dark-pill:hover {
      background: #520e0e;
    }

    /* ══ GALERI ══ */
    #galeri {
      background: #e8ded7;
    }

    .galeri-mosaik {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-auto-rows: 240px;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .g-box {
      background: var(--maroon-dark);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .g-box:hover {
      transform: scale(1.02);
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    /* ══ GRID TIM PENGURUS & PELATIH ══ */
    .cards-grid { 
        display: grid; 
        grid-template-columns: repeat(5, 1fr); 
        gap: 1.2rem; 
        margin-top: 1.5rem; 
        margin-bottom: 3rem; 
    }
    .profile-card-team { 
        background: #ffffff; 
        border-radius: 12px; 
        overflow: hidden; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.04); 
        text-align: center; 
        padding-bottom: 1rem; 
        border: 1px solid #f0f0f0;
    }
    
    .avatar-wrapper { 
        width: 100%; 
        height: 180px; 
        overflow: hidden; 
        background-color: #eee; 
        position: relative; 
    }
    .avatar-wrapper img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        position: absolute; 
        top: 0; left: 0; 
        opacity: 0; 
        transition: opacity 0.5s ease-in-out; 
    }
    .avatar-wrapper img.active { opacity: 1; }

    .inline-error-fallback {
        width: 100%; height: 100%; display: none; flex-direction: column; align-items: center; justify-content: center; background-color: #e5e7eb; color: #9ca3af; position: absolute; top: 0; left: 0; z-index: 10;
    }
    .inline-error-fallback.active { display: flex; }

    .profile-info { padding: 0.8rem 0.5rem 0.4rem; position: relative; z-index: 15; background: #fff; }
    .profile-info h4 { margin: 0; font-size: 0.95rem; color: #333; font-weight: 700; }
    .profile-info p { margin: 2px 0 8px; font-size: 0.75rem; color: #888; font-weight: 500; }
    .profile-socials { display: flex; justify-content: center; gap: 8px; }
    
    .social-btn {
        width: 28px; 
        height: 28px; 
        border-radius: 50%; 
        background-color: var(--maroon-dark); 
        color: white; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 0.75rem; 
        text-decoration: none; 
        transition: background-color 0.2s;
    }
    .social-btn:hover { background-color: #b22222; }

    /* ══ KALENDER EVENT ══ */
    #kalender {
        background: #ffffff !important;
        padding: 80px 0;
        width: 100%;
        display: block;
    }

    .kalender-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .kalender-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 3rem;
        align-items: start;
    }

    .cal-widget {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .cal-header {
        background: #5a1a1a;
        color: #ffffff !important;
        padding: 1.2rem;
        text-align: center;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .cal-body {
        padding: 1.5rem;
        background: #ffffff;
    }

    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        gap: 8px;
    }

    .cal-grid span {
        font-size: 0.9rem;
        color: #444444 !important;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .day-name {
        font-weight: 700;
        color: #aaa !important;
        font-size: 0.75rem;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .cal-grid .event-day {
        background-color: #b91c1c !important;
        color: #ffffff !important;
        border-radius: 50%;
        font-weight: 700;
        width: 35px;
        height: 35px;
        margin: auto;
    }

    .cal-grid .active-today {
        border: 2px solid #b91c1c;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        margin: auto;
    }

    .event-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .event-link-card {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    #kalender .event-card {
        background-color: #ffffff !important;
        border: 1px solid #eee;
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        gap: 1.5rem;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .event-link-card:hover .event-card {
        transform: translateX(10px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    #kalender .event-date {
        background: #5a1a1a !important;
        color: #ffffff !important;
        text-align: center;
        border-radius: 12px;
        padding: 1rem;
        min-width: 85px;
    }

    #kalender .event-date h2 {
        margin: 0;
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        line-height: 1;
        color: #ffffff !important;
    }

    #kalender .event-date span {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #ffffff !important;
    }

    #kalender .event-info h4 {
        color: #222222 !important;
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 8px;
        font-family: 'Poppins', sans-serif;
    }

    #kalender .event-info p {
        color: #555555 !important;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 12px;
    }
    
    #kalender .event-info .meta span {
        background: transparent !important; 
        color: #b91c1c !important;      
        padding: 0 !important;            
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* ══ SECTION CTA BERGABUNG ══ */
    #cta-join {
        background-color: #3D0C0C;
        color: var(--white);
        padding: 5rem 10%;
    }

    .cta-container {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        align-items: center;
        gap: 3rem;
    }

    .cta-badge {
        background-color: #D4AF37;
        color: white;
        padding: 0.4rem 1.2rem;
        border-radius: 4px;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 1px;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .cta-content h2 {
        font-size: 2.8rem;
        line-height: 1.2;
        margin-bottom: 2rem;
        font-family: 'Playfair Display', serif;
    }

    .cta-content p {
        font-size: 1rem;
        line-height: 1.7;
        opacity: 0.8;
        max-width: 600px;
    }

    .cta-action {
        text-align: right;
    }

    .btn-daftar-cta {
        background-color: #A8762E;
        color: white;
        padding: 1.2rem 3.5rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .btn-daftar-cta:hover {
        background-color: #C5A059;
        transform: translateY(-5px);
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 1024px) { .cards-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 992px) {
        .hero-grid, .profile-grid, .berita-grid, .kalender-grid, .cta-container { grid-template-columns: 1fr; }
        .katalog-grid { grid-template-columns: 1fr 1fr; }
        .hero-logo-wrap, .cta-action { justify-content: center; text-align: center; margin-top: 2rem; }
        .galeri-mosaik { grid-template-columns: 1fr 1fr; }
        .cal-widget { max-width: 400px; margin: 0 auto; }
    }
    @media (max-width: 768px) {
        .cards-grid { grid-template-columns: repeat(2, 1fr); }
        .profile-grid { gap: 2rem; }
    }
    @media (max-width: 600px) {
        .katalog-grid, .galeri-mosaik { grid-template-columns: 1fr; }
        .cta-content h2 { font-size: 2rem; }
    }
  </style>
@endpush

@section('content')
  {{-- ══ HERO SECTION ══ --}}
  <section id="beranda">
    <div class="hero-grid">
      <div class="hero-text">
        <h1>Sanggar Tari <span class="script">Jiwa Etnik Blambangan</span></h1>
        <p>Pusat pelestarian seni dan budaya tari tradisional Banyuwangi. Menggerakkan jiwa, merayakan budaya, melestarikan warisan Nusantara.</p>
        <a href="{{ route('umum.galeri') }}" class="btn-gray-pill">Lihat Galeri</a>
      </div>
      <div class="hero-logo-wrap">
        @if($profil && $profil->logo && $profil->logo->path)
          <img src="{{ asset('storage/' . $profil->logo->path) }}" alt="Logo Sanggar Jiwa Etnik Blambangan" class="logo-img-hero" />
        @else
          <img src="{{ asset('img/images-landing-page/galeri/logo-jeb.jpg') }}" alt="Logo Default Sanggar" class="logo-img-hero" />
        @endif
      </div>
    </div>
  </section>

  {{-- ══ PROFILE SECTION ══ --}}
  <section id="profile">
    <div class="section-header">
      <span class="badge-pill">TENTANG KAMI</span>
      <h2 class="section-title">Profile Sanggar</h2>
      <p class="section-subtitle">Mengenal lebih dekat Sanggar Tari Jiwa Etnik Blambangan</p>
    </div>
    <div class="profile-grid">
      <div class="profile-text">
        <h3>Jiwa Etnik Blambangan</h3>
        <p>{{ $profil->deskripsi ?? 'Sanggar Tari Jiwa Etnik Blambangan (JEB) adalah pusat seni tari tradisional yang berdedikasi untuk melestarikan kekayaan budaya Banyuwangi.'}}</p>
        <p>{{ $profil->sejarah ?? 'Kami membina generasi muda untuk mencintai dan menguasai seni tari tradisional, mulai dari tari Gandrung, hingga tari kreasi baru berbasis budaya lokal Blambangan.' }}</p>
      </div>
      <div class="profile-card">
        @if($profil && $profil->fotoPembina && $profil->fotoPembina->path)
          <img src="{{ asset('storage/' . $profil->fotoPembina->path) }}" alt="Foto Pembina Sanggar" class="profile-card-img">
        @else
          <img src="{{ asset('img/images-landing-page/galeri/foto_pembina.jpeg') }}" alt="Default Pembina Sanggar" class="profile-card-img">
        @endif
        <h4>{{ $profil->nama_pembina ?? 'Adlin Mustika Alam' }}</h4>
        <div class="role-title">Pembina Sanggar JEB</div>
        <p>"{{ $profil->sambutan_pembina ?? 'Tari Tradisional adalah jiwa dari identitas budaya kita. Bersama kami, lestarikan dan rayakan kekayaan seni Nusantara.' }}"</p>
      </div>
    </div>
    <div class="center-action">
      <a href="{{ route('umum.profil') }}" class="btn-dark-pill">Profil Lengkap Sanggar</a>
    </div>
  </section>
  
  {{-- ══ GARIS PEMBATAS ELEGAN VIA FONTAWESOME ══ --}}
  <div class="section-divider">
    <i class="fas fa-gopuram"></i>
  </div>

  {{-- ══ VISI & MISI SECTION ══ --}}
  <section class="section-visi-misi" style="padding: 60px 0; background-color: #fff;">
    <div class="container" style="max-width: 900px; margin: auto; text-align: center;">
        <h2 style="font-family: 'Playfair Display', serif; color: #3D0C0C; margin-bottom: 40px; font-weight: 700; font-size: 2.2rem;">VISI & MISI</h2>

        <div class="visi-card" style="background: #a51c1c; color: white; padding: 40px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; font-family: 'Poppins', sans-serif; font-weight:700; color:#f1c40f; letter-spacing:1px;">VISI</h3>
            <p style="font-size: 1.1rem; line-height: 1.6; margin: 0;">
                {{ $profil->visi ?? 'Menjadi sanggar seni terdepan dalam melestarikan warisan luhur adat tari Blambangan di kancah nasional maupun internasional.' }}
            </p>
        </div>

        <div class="misi-card" style="background: #fdf5e6; color: #3D0C0C; padding: 40px; border-radius: 15px; border: 1px solid #e0d0c0; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
            <h3 style="margin-top: 0; font-family: 'Poppins', sans-serif; font-weight:700; color:#a51c1c; letter-spacing:1px;">MISI</h3>
            <div style="font-size: 1.1rem; line-height: 1.8; text-align: left; color:#555; padding-left: 10px;">
                @if($profil && $profil->misi)
                    {!! $profil->misi !!}
                @else
                    <p>1. Membina generasi muda aktif dalam mencintai kesenian lokal daerah.</p>
                    <p>2. Menyelenggarakan pelatihan tari berkala bagi masyarakat umum.</p>
                @endif
            </div>
        </div>
    </div>
  </section>

  {{-- ══ KATALOG KOSTUM SECTION ══ --}}
  <section id="katalog">
    <div class="section-header">
      <h2 class="section-title white">Katalog Kostum</h2>
      <p class="section-subtitle white" style="color: var(--text-gray) !important;">Koleksi Kostum tari tradisional dan kreasi Sanggar JEB</p>
    </div>
    <div class="katalog-grid">
      @forelse($katalogs as $item)
      <a href="{{ route('umum.katalog-detail', $item->id) }}" class="katalog-link-card">
          <div class="katalog-card">
            <div class="katalog-img">
              @php
                // Ambil path gambar secara aman, baik relasinya tunggal maupun kumpulan data (Collection)
                $gambarPath = null;
                if ($item->galeri) {
                    if ($item->galeri instanceof \Illuminate\Support\Collection || $item->galeri instanceof \Illuminate\Database\Eloquent\Collection) {
                        $gambarPath = $item->galeri->first()?->path;
                    } else {
                        $gambarPath = $item->galeri->path;
                    }
                }
              @endphp

              @if($gambarPath)
                <img src="{{ asset('storage/' . $gambarPath) }}" alt="{{ $item->nama_tari }}" onerror="this.onerror=null; this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; this.style.backgroundColor='#ffffff';">
              @else
                <img src="{{ asset('img/images-landing-page/banner_katalog.jpeg') }}" alt="{{ $item->nama_tari }}" onerror="this.onerror=null; this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; this.style.backgroundColor='#ffffff';">
              @endif
              
              <span class="badge">{{ $item->kategori }}</span>
            </div>
            <div class="katalog-info">
              <h4>{{ $item->nama_tari }}</h4>
              <p>{{ Str::limit(strip_tags($item->deskripsi), 80) }}</p>
              <div class="katalog-footer">
                <span class="tipe" style="color: {{ $item->status == 'tersedia' ? '#28a745' : '#dc3545' }} ;">
                    {{ $item->status == 'tersedia' ? 'Tersedia' : 'Tidak Tersedia' }}
                </span>
                <div class="katalog-actions">
                  <span>Stok: {{ $item->stok ?? 0 }}</span>
                </div>
              </div>
            </div>
          </div>
      </a>
      @empty
      <div class="katalog-card" style="grid-column: 1/-1; text-align: center; padding: 30px;">
        <div class="katalog-info">
          <h4>Belum ada data katalog kostum</h4>
          <p>Koleksi kostum panggung sanggar menanti untuk diperbarui.</p>
        </div>
      </div>
      @endforelse
    </div>
    <div class="center-action">
      <a href="{{ route('umum.katalog') }}" class="btn-dark-red-pill">Lihat semua katalog</a>
    </div>
  </section>  

  {{-- ══ BERITA SECTION ══ --}}
  <section id="berita">
    <div class="section-header">
      <span class="badge-pill">INFORMASI</span>
      <h2 class="section-title">Berita & Artikel</h2>
      <p class="section-subtitle">Kabar terbaru kegiatan dan prestasi sanggar JEB</p>
    </div>
    <div class="berita-grid">
      @if($beritaUtama)
        @php
          $pathBeritaUtama = 'img/images-landing-page/banner_berita.jpeg';
          if ($beritaUtama->galeri && $beritaUtama->galeri->isNotEmpty()) {
              $pathBeritaUtama = 'storage/' . $beritaUtama->galeri->first()->path;
          } elseif ($beritaUtama->galeri && isset($beritaUtama->galeri->path)) {
              $pathBeritaUtama = 'storage/' . $beritaUtama->galeri->path;
          }
          $coverUtama = asset($pathBeritaUtama);
        @endphp
        <div class="berita-main" style="background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(30, 5, 5, 0.8)), url('{{ $coverUtama }}'); background-size: cover; background-position: center;">
          <span class="badge-yellow">BERITA UTAMA</span>
          <h3>{{ $beritaUtama->judul }}</h3>
          <p>{{ Str::limit(strip_tags($beritaUtama->isi_berita), 150) }}</p>
          <div class="meta">
            <span>Berita • {{ \Carbon\Carbon::parse($beritaUtama->tgl_terbit)->translatedFormat('d M Y') }} • {{ $beritaUtama->user->name ?? 'Admin' }}</span>
            <a href="{{ route('umum.berita-detail', $beritaUtama->id) }}" class="btn-red-pill">Baca Selengkapnya</a>
          </div>
        </div>
      @else
        <div class="berita-main" style="background: #5a1a1a; justify-content: center; align-items: center; text-align: center;">
          <span class="badge-yellow">BERITA UTAMA</span>
          <h3 style="margin-top: 1rem;">Belum ada berita utama</h3>
          <p>Kabar info menarik seputar sanggar tari JEB akan segera hadir.</p>
        </div>
      @endif

      <div class="berita-list">
        @forelse($beritaTerbaru as $item)
          @php
            $pathBeritaTerbaru = 'img/images-landing-page/banner_berita.jpeg';
            if ($item->galeri && $item->galeri->isNotEmpty()) {
                $pathBeritaTerbaru = 'storage/' . $item->galeri->first()->path;
            } elseif ($item->galeri && isset($item->galeri->path)) {
                $pathBeritaTerbaru = 'storage/' . $item->galeri->path;
            }
            $coverTerbaru = asset($pathBeritaTerbaru);
          @endphp
          <a href="{{ route('umum.berita-detail', $item->id) }}" style="text-decoration: none; color: inherit;">
            <div class="berita-item">
              <div class="berita-img-placeholder" style="background: url('{{ $coverTerbaru }}'); background-size: cover; background-position: center; width: 120px; min-width: 120px; height: 100px;"></div>
              <div class="berita-item-content">
                <span class="tag">Berita</span>
                <h5>{{ Str::limit($item->judul, 60) }}</h5>
                <span>{{ \Carbon\Carbon::parse($item->tgl_terbit)->translatedFormat('d M Y') }} - {{ $item->user->name ?? 'Admin' }}</span>
              </div>
            </div>
          </a>
        @empty
          <div class="berita-item" style="padding: 20px; text-align: center;">
            <div class="berita-item-content" style="align-items: center; width: 100%;">
              <span class="tag">Kosong</span>
              <h5>Belum ada kumpulan berita terbaru</h5>
            </div>
          </div>
        @endforelse
      </div>
    </div>
    <div class="center-action">
      <a href="{{ route('umum.berita') }}" class="btn-dark-pill">Lihat Semua Berita</a>
    </div>
  </section>

  {{-- ══ GALERI MOSAIK SECTION ══ --}}
<section id="galeri">
  <div class="section-header">
    <span class="badge-pill" style="background:transparent; border:1px solid var(--maroon-dark); color:var(--maroon-dark);">DOKUMENTASI</span>
    <h2 class="section-title white">Galeri Sanggar</h2>
    <p class="section-subtitle white" style="color: var(--text-gray) !important;">Momen indah perjalanan seni dan budaya Jiwa Etnik Blambangan</p>
  </div>
  <div class="galeri-mosaik">
    {{-- Langsung loop dari variabel $galeri asli --}}
    @forelse($galeri as $item)
      <a href="{{ route('umum.galeri-detail', $item->id) }}" class="g-box" style="background-image: url('{{ asset('storage/' . $item->path) }}'); background-size: cover; background-position: center; display: block;"></a>
    @empty
      {{-- Fallback jika database benar-benar kosong --}}
      <div class="g-box"></div>
      <div class="g-box"></div>
      <div class="g-box"></div>
      <div class="g-box"></div>
      <div class="g-box"></div>
      <div class="g-box"></div>
    @endforelse
  </div>
  <div class="center-action">
    <a href="{{ route('umum.galeri') }}" class="btn-gray-pill">Lihat selengkapnya</a>
  </div>
</section>

  {{-- ══ KALENDER EVENT SECTION ══ --}}
  <section id="kalender">
    <div class="section-header">
        <span class="badge-pill">KEGIATAN</span>
        <h2 class="section-title">Kalender Event</h2>
        <p class="section-subtitle">Jadwal pentas dan kegiatan Sanggar JEB</p>
    </div>

    <div class="kalender-container">
        <div class="kalender-grid">
            <div class="cal-widget">
                <div class="cal-header">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</div>
                <div class="cal-body">
                    <div class="cal-grid">
                        <span class="day-name">Su</span><span class="day-name">Mo</span><span class="day-name">Tu</span><span class="day-name">We</span><span class="day-name">Th</span><span class="day-name">Fr</span><span class="day-name">Sa</span>
                        
                        @for($x = 0; $x < ($blankDays ?? 0); $x++)
                            <span></span>
                        @endfor

                        @for($i = 1; $i <= ($daysInMonth ?? 30); $i++)
                            @php 
                                $isEvent = in_array($i, $eventDays ?? []); 
                                $isToday = ($i == date('d') && \Carbon\Carbon::now()->month == date('m'));
                            @endphp
                            <span class="{{ $isEvent ? 'event-day' : '' }} {{ $isToday ? 'active-today' : '' }}">
                                {{ $i }}
                            </span>
                        @endfor
                    </div>
                </div>
            </div> 
            
            <div class="event-list">
                @forelse($events as $event)
                <a href="{{ route('umum.event-detail', $event->id) }}" class="event-link-card">
                    <div class="event-card">
                        <div class="event-date">
                            <h2>{{ \Carbon\Carbon::parse($event->tanggal)->format('d') }}</h2>
                            <span>{{ strtoupper(\Carbon\Carbon::parse($event->tanggal)->translatedFormat('M')) }}</span>
                        </div>
                        <div class="event-info">
                            <h4>{{ $event->nama }}</h4>
                            <p>{{ Str::limit(strip_tags($event->deskripsi), 160) ?? 'Kegiatan pertunjukan seni tari Sanggar Tari JEB.' }}</p>
                            <div class="meta">
                                <span>📍 {{ Str::limit($event->lokasi, 40) }}</span>
                                <span style="margin-left: 15px;">🕒 {{ \Carbon\Carbon::parse($event->jam)->format('H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="event-card" style="justify-content: center; padding: 40px; color:#aaa; width:100%;">
                    <p style="margin:0; font-weight:600;">Belum ada event terjadwal bulan ini.</p>
                </div>
                @endforelse
            </div> 
            
            <div class="center-action" style="text-align: center; margin-top: 4rem; width: 100%; grid-column: 1 / -1;">
                <a href="{{ route('umum.event') }}" class="btn-dark-pill" style="background: #5a1a1a; color: #fff; padding: 0.8rem 3rem; border-radius: 30px; text-decoration: none; font-weight: 600; display: inline-block; transition: 0.3s;">
                    Lihat Semua Event
                </a>
            </div>
        </div> 
    </div> 
  </section>

  {{-- ══ CTA JOIN SECTION ══ --}}
  <section id="cta-join">
    <div class="cta-container">
        <div class="cta-content">
            <span class="cta-badge">BERGABUNG</span>
            <h2>Kembangkan Bakat Seni <br> Anda Bersama Kami</h2>
            <p>
                Sanggar Seni Jiwa Etnik Blambangan adalah ruang kreatif bagi pecinta seni budaya Banyuwangi 
                untuk belajar, berkarya, dan berprestasi. Kami menghadirkan pelatihan tari tradisional, 
                pertunjukan seni, serta kegiatan budaya yang menginspirasi dan mempererat kebersamaan.
            </p>
        </div>

        {{-- Mengambil data sistem dinamis dari database untuk kebutuhan pendaftaran --}}
        @php
            $sistemCta = \App\Models\PengaturanSistem::first();
            
            if ($sistemCta && $sistemCta->link_wa) {
                $nomorPendaftaran = preg_replace('/[^0-9]/', '', $sistemCta->link_wa);
            } else {
                // Cadangan jika nomor di database belum diisi
                $nomorPendaftaran = '6282232505355';
            }
        @endphp

        <div class="cta-action">
            <a href="https://wa.me/{{ $nomorPendaftaran }}" target="_blank" class="btn-daftar-cta">DAFTAR SEKARANG</a>
        </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // AUTOMATIC SLIDESHOW MULTI-FOTO CARD TIM PENGURUS & PELATIH
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
@endpush