@extends('layouts.umum')

@section('title', 'Profil Sanggar - Sanggar JEB')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { background-color: #f4eae6; font-family: 'Poppins', sans-serif; }
    .container { width: 88%; max-width: 1200px; margin: 0 auto; }
    .section-padding { padding: 4rem 0; }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    
    .title-maroon {
        font-family: 'Playfair Display', serif; font-size: 2rem; color: #6b1414; font-weight: 700; margin-bottom: 1.5rem;
    }
    .section-title-center {
        font-family: 'Playfair Display', serif; color: #6b1414; font-size: 1.8rem; font-weight: 700; margin-top: 0.5rem; margin-bottom: 0.5rem;
    }

    /* ══ 1. HERO BANNER ATAS DINAMIS (SLIDESHOW) ══ */
    .berita-banner-container {
        width: 100%; height: 380px; position: relative; overflow: hidden; background: #333;
    }

    .banner-slideshow-wrapper {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;
    }

    .banner-slideshow-wrapper img, 
    .banner-slideshow-wrapper .banner-error-fallback {
        width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0; transition: opacity 0.8s ease-in-out;
    }

    .banner-slideshow-wrapper img.active,
    .banner-slideshow-wrapper .banner-error-fallback.active { opacity: 1; }

    .banner-overlay-dark {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)); z-index: 2;
    }

    .berita-banner-content {
        position: relative; z-index: 3; height: 100%; display: flex; flex-direction: column; justify-content: center; padding: 0 8%; color: white; font-family: 'Poppins', sans-serif;
    }

    .banner-error-fallback {
        display: none; flex-direction: column; align-items: center; justify-content: center; background: #2c3e50;
    }
    .banner-error-fallback.active { display: flex; }

    .badge-red {
        background-color: #C0392B; color: white; padding: 6px 24px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; letter-spacing: 1px; display: inline-block; text-transform: uppercase;
    }
    .hero-title { font-family: 'Playfair Display', serif; font-size: 3rem; margin: 1rem 0 0.5rem; font-weight: 700; }
    .hero-subtitle-top { font-size: 0.85rem; color: rgba(255,255,255,0.8); }

    .deskripsi-grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 3rem; align-items: start; }
    .deskripsi-text p { color: #333; line-height: 1.8; font-size: 0.95rem; margin-bottom: 1.2rem; }
    .vm-container { display: flex; flex-direction: column; gap: 1.5rem; }
    .vm-card { background: #ffffff; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .vm-card h3 { color: #6b1414; font-family: 'Playfair Display', serif; font-size: 1.3rem; margin-bottom: 0.8rem; font-weight: 700; }
    .vm-card p, .vm-card .misi-text { color: #555; font-size: 0.85rem; line-height: 1.6; margin: 0; }
    
    .vm-card .misi-text ul, .vm-card .misi-text ol { padding-left: 1.2rem; margin: 0; }

    .sejarah-section { background-color: #e8ded7; padding: 4rem 0; }
    .sejarah-row { display: flex; gap: 3rem; align-items: center; margin-bottom: 4rem; }
    .sejarah-logo {
        width: 180px; height: 180px; flex-shrink: 0; background: #fff; border-radius: 50%; padding: 5px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); position: relative; overflow: hidden; 
    }
    .sejarah-logo img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .sejarah-text h2 { font-family: 'Playfair Display', serif; color: #6b1414; font-size: 1.8rem; margin-bottom: 1rem; }
    .sejarah-text p { color: #444; line-height: 1.7; font-size: 0.9rem; }

    .sambutan-row { display: grid; grid-template-columns: 1.3fr 0.7fr; gap: 4rem; align-items: center; }
    .sambutan-text { text-align: center; }
    .sambutan-text h3 { font-family: 'Playfair Display', serif; color: #6b1414; font-size: 1.6rem; margin-bottom: 1rem; }
    .sambutan-text p { color: #444; font-size: 0.95rem; line-height: 1.8; font-style: italic; }
    .pembina-wrapper { text-align: center; }
    .pembina-image { width: 100%; height: 320px; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.1); position: relative; background-color: #eee; }
    .pembina-image img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pembina-name { margin-top: 0.8rem; font-weight: 700; color: #6b1414; font-size: 1.1rem; }

    .cards-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.2rem; margin-top: 1.5rem; margin-bottom: 3rem; }
    .profile-card { background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.04); text-align: center; padding-bottom: 1rem; cursor: pointer; transition: transform 0.2s; }
    .profile-card:hover { transform: translateY(-3px); }
    
    .avatar-wrapper { width: 100%; height: 180px; overflow: hidden; background-color: #eee; position: relative; }
    .avatar-wrapper img { 
        width: 100%; height: 100%; object-fit: cover; 
        position: absolute; top: 0; left: 0; opacity: 0; 
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
        width: 28px; height: 28px; border-radius: 50%; background-color: #6b1414; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; text-decoration: none; transition: background-color 0.2s;
    }
    .social-btn:hover { background-color: #b22222; }

    @media (max-width: 1024px) { .cards-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) {
        .deskripsi-grid, .sambutan-row, .sejarah-row { grid-template-columns: 1fr; flex-direction: column; }
        .cards-grid { grid-template-columns: repeat(2, 1fr); }
        .sejarah-logo { margin: 0 auto; }
    }
</style>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { background-color: #f4eae6; font-family: 'Poppins', sans-serif; }
    .container { width: 88%; max-width: 1200px; margin: 0 auto; }
    .section-padding { padding: 4rem 0; }
    .text-center { text-align: center; }
    
    .title-maroon {
        font-family: 'Playfair Display', serif; font-size: 2rem; color: #6b1414; font-weight: 700; margin-bottom: 1.5rem;
    }
    .section-title-center {
        font-family: 'Playfair Display', serif; color: #6b1414; font-size: 1.8rem; font-weight: 700; margin-top: 0.5rem; margin-bottom: 0.5rem;
    }

    /* ══ HERO BANNER ══ */
    .berita-banner-container { width: 100%; height: 380px; position: relative; overflow: hidden; background: #333; }
    .banner-slideshow-wrapper { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; }
    .banner-slideshow-wrapper img, .banner-slideshow-wrapper .banner-error-fallback {
        width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0; transition: opacity 0.8s ease-in-out;
    }
    .banner-slideshow-wrapper img.active, .banner-slideshow-wrapper .banner-error-fallback.active { opacity: 1; }
    .banner-overlay-dark { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)); z-index: 2; }
    .berita-banner-content { position: relative; z-index: 3; height: 100%; display: flex; flex-direction: column; justify-content: center; padding: 0 8%; color: white; }
    .badge-red { background-color: #C0392B; color: white; padding: 6px 24px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; letter-spacing: 1px; display: inline-block; text-transform: uppercase; }
    .hero-title { font-family: 'Playfair Display', serif; font-size: 3rem; margin: 1rem 0 0.5rem; font-weight: 700; }
    .hero-subtitle-top { font-size: 0.85rem; color: rgba(255,255,255,0.8); }

    .deskripsi-grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 3rem; align-items: start; }
    .deskripsi-text p { color: #333; line-height: 1.8; font-size: 0.95rem; margin-bottom: 1.2rem; }
    .vm-container { display: flex; flex-direction: column; gap: 1.5rem; }
    .vm-card { background: #ffffff; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .vm-card h3 { color: #6b1414; font-family: 'Playfair Display', serif; font-size: 1.3rem; margin-bottom: 0.8rem; font-weight: 700; }
    .vm-card p, .vm-card .misi-text { color: #555; font-size: 0.85rem; line-height: 1.6; margin: 0; }

    .sejarah-section { background-color: #e8ded7; padding: 4rem 0; }
    .sejarah-row { display: flex; gap: 3rem; align-items: center; margin-bottom: 4rem; }
    .sejarah-logo { width: 180px; height: 180px; flex-shrink: 0; background: #fff; border-radius: 50%; padding: 5px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); position: relative; overflow: hidden; }
    .sejarah-logo img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .sejarah-text h2 { font-family: 'Playfair Display', serif; color: #6b1414; font-size: 1.8rem; margin-bottom: 1rem; }
    .sejarah-text p { color: #444; line-height: 1.7; font-size: 0.9rem; }

    .sambutan-row { display: grid; grid-template-columns: 1.3fr 0.7fr; gap: 4rem; align-items: center; }
    .sambutan-text { text-align: center; }
    .sambutan-text h3 { font-family: 'Playfair Display', serif; color: #6b1414; font-size: 1.6rem; margin-bottom: 1rem; }
    .sambutan-text p { color: #444; font-size: 0.95rem; line-height: 1.8; font-style: italic; }
    .pembina-wrapper { text-align: center; }
    .pembina-image { width: 100%; height: 320px; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.1); position: relative; background-color: #eee; }
    .pembina-image img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pembina-name { margin-top: 0.8rem; font-weight: 700; color: #6b1414; font-size: 1.1rem; }

    /* ══ SEARCH & FILTER BAR ══ */
    .search-filter-wrapper { background: transparent; padding: 10px 0 30px 0; display: flex; gap: 15px; align-items: stretch; width: 100%; }
    .search-box-container { flex: 1; position: relative; min-width: 0; }
    .search-box-container .fa-search { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #C0392B; cursor: pointer; font-size: 1.1rem; z-index: 2; }
    .search-input, .btn-trigger-filter { height: 52px; box-sizing: border-box; font-family: 'Poppins', sans-serif; font-size: 0.95rem; border-radius: 10px; }
    .search-input { width: 100%; padding: 0 20px 0 50px; border: 1px solid #ddd; background: white; outline: none; box-shadow: 0 2px 10px rgba(0,0,0,0.02); transition: border-color 0.2s; }
    .search-input:focus { border-color: #C0392B; box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.1); }
    .btn-trigger-filter { padding: 0 25px; background: #C0392B; color: white; border: none; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 10px; transition: background 0.2s; white-space: nowrap; flex-shrink: 0; box-shadow: 0 2px 10px rgba(192, 57, 43, 0.2); }
    .btn-trigger-filter:hover { background: #A93226; }

    /* MODAL FILTER STYLE */
    .custom-modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: none; justify-content: center; align-items: center; z-index: 9999; padding: 16px; }
    .custom-modal-content { background: #fff; width: 100%; max-width: 480px; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
    .custom-modal-header h3 { margin: 0; font-size: 1.3rem; font-weight: 700; color: #2D1A1A; }
    .custom-modal-header p { margin: 4px 0 0 0; font-size: 0.85rem; color: #888; }
    .modal-divider { border: 0; border-top: 1px solid #E5E7EB; margin: 18px 0; }
    .section-title { font-size: 0.95rem; font-weight: 600; color: #333; display: block; margin-bottom: 12px; }
    .checkbox-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .filter-checkbox-card { border: 1px solid #D1D5DB; border-radius: 12px; padding: 12px 15px; display: flex; align-items: center; gap: 10px; cursor: pointer; transition: all 0.2s; background: #fff; }
    .filter-checkbox-card:hover { border-color: #b07d7d; background: #fffbfb; }
    .filter-checkbox-card input[type="checkbox"] { accent-color: #C0392B; width: 17px; height: 17px; cursor: pointer; }
    .filter-checkbox-card.selected-box { border-color: #C0392B; background-color: #FFF5F5; }
    .filter-checkbox-card.selected-box .checkbox-label { font-weight: 600; color: #C0392B; }
    .checkbox-label { font-size: 0.85rem; color: #4B5563; cursor: pointer; }
    .custom-modal-footer { display: flex; gap: 15px; margin-top: 25px; }
    .btn-modal-batal, .btn-modal-terapkan { flex: 1; padding: 12px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
    .btn-modal-batal { background: #E5E7EB; color: #374151; }
    .btn-modal-terapkan { background: #C0392B; color: white; }

    /* CARDS GRID */
    .cards-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.2rem; margin-top: 1.5rem; margin-bottom: 3rem; }
    .profile-card { background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.04); text-align: center; padding-bottom: 1rem; cursor: pointer; transition: transform 0.2s; }
    .profile-card:hover { transform: translateY(-3px); }
    .avatar-wrapper { width: 100%; height: 180px; overflow: hidden; background-color: #eee; position: relative; }
    .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0; transition: opacity 0.5s ease-in-out; }
    .avatar-wrapper img.active { opacity: 1; }
    .inline-error-fallback { width: 100%; height: 100%; display: none; flex-direction: column; align-items: center; justify-content: center; background-color: #e5e7eb; color: #9ca3af; position: absolute; top: 0; left: 0; z-index: 10; }
    .inline-error-fallback.active { display: flex; }
    .profile-info { padding: 0.8rem 0.5rem 0.4rem; position: relative; z-index: 15; background: #fff; }
    .profile-info h4 { margin: 0; font-size: 0.95rem; color: #333; font-weight: 700; }
    .profile-info p { margin: 2px 0 8px; font-size: 0.75rem; color: #888; font-weight: 500; }
    .profile-socials { display: flex; justify-content: center; gap: 8px; }
    .social-btn { width: 28px; height: 28px; border-radius: 50%; background-color: #6b1414; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; text-decoration: none; }

    /* PAGINATION */
    .news-pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 35px; flex-wrap: wrap; gap: 15px; }
    .news-pagination p { font-size: 0.85rem; color: #555; margin: 0; font-weight: 500; }
    .news-page-controls { display: flex; gap: 8px; }
    .news-page-controls a, .news-page-controls span { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; border: 1px solid #c8bcba; background: #fff; color: #333; font-size: 0.85rem; text-decoration: none; }
    .news-page-controls span.active { background: #C0392B; color: #fff; border-color: #C0392B; font-weight: 700; }
    .news-page-controls span.disabled { color: #ccc; cursor: not-allowed; }

    @media (max-width: 1024px) { .cards-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) {
        .cards-grid { grid-template-columns: repeat(2, 1fr); }
        .search-filter-wrapper { flex-direction: column; }
    }
</style>
@endpush
@endpush

@section('content')
<div class="berita-banner-container">
    <div class="banner-slideshow-wrapper header-slideshow-container">
        @if(isset($profil) && $profil->headers && $profil->headers->isNotEmpty())
            @foreach($profil->headers as $index => $headerFoto)
                <img src="{{ asset('storage/' . $headerFoto->path) }}" alt="Header Sanggar JEB" class="{{ $index === 0 ? 'active' : '' }}" onerror="this.style.display='none'; document.getElementById('header-fallback').classList.add('active');">
            @endforeach
            <div id="header-fallback" class="banner-error-fallback">
                <i class="fas fa-image" style="font-size: 3.5rem; color: rgba(255,255,255,0.3); margin-bottom: 8px;"></i>
                <span style="font-size: 0.85rem; color: rgba(255,255,255,0.5); font-weight: 500;">Gagal Memuat Gambar Banner</span>
            </div>
        @else
            <div class="banner-error-fallback active">
                <i class="fas fa-image" style="font-size: 3.5rem; color: rgba(255,255,255,0.3); margin-bottom: 8px;"></i>
                <span style="font-size: 0.85rem; color: rgba(255,255,255,0.5); font-weight: 500;">Gagal Memuat Gambar Banner</span>
            </div>
        @endif
    </div>
    <div class="banner-overlay-dark"></div>
    <div class="berita-banner-content">
        <div style="width: fit-content;"><span class="badge-red">Profil</span></div>
        <h1 class="hero-title">Profil Sanggar</h1>
        <p class="hero-subtitle-top">Profil sanggar tari JEB, sejarah JEB, dan daftar Kepengurusan JEB</p>
    </div>
</div>
<section class="sejarah-section">
    <div class="container">
        <div class="sambutan-row">
            <div class="sambutan-text">
                <h3>Sambutan Pembina</h3>
                <p>"{{ $profil->sambutan_pembina ?? 'Data sambutan belum diatur oleh admin.' }}"</p>
            </div>
            <div class="pembina-wrapper">
                <div class="pembina-image">
                    @if(isset($profil->fotoPembina) && $profil->fotoPembina->path)
                        <img src="{{ asset('storage/' . $profil->fotoPembina->path) }}" alt="Foto Pembina Sanggar" onerror="this.style.display='none'; document.getElementById('pembina-fallback').classList.add('active');">
                    @else
                        <img src="{{ asset('img/images-landing-page/galeri/foto_pembina.jpeg') }}" alt="Foto Pembina Default" onerror="this.style.display='none'; document.getElementById('pembina-fallback').classList.add('active');">
                    @endif
                    <div id="pembina-fallback" class="inline-error-fallback"><i class="fas fa-user" style="font-size: 3rem;"></i></div>
                </div>
                <div class="pembina-name">Adlin Mustika Alam S.,Sn.</div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="deskripsi-grid">
            <div class="deskripsi-text">
                <h2 class="title-maroon" style="margin-top: 0;">Sanggar Seni JEB</h2>
                <p>{!! nl2br(e($profil->deskripsi ?? 'Data deskripsi sanggar belum diatur oleh admin.')) !!}</p>
            </div>
            <div class="vm-container">
                <div class="vm-card">
                    <h3>Visi</h3>
                    <p>{{ $profil->visi ?? 'Data visi belum diatur oleh admin.' }}</p>
                </div>
                <div class="vm-card">
                    <h3>Misi</h3>
                    <div class="misi-text">
                        {!! $profil->misi ?? 'Data misi belum diatur oleh admin.' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="sejarah-section">
    <div class="container">
        <div class="sejarah-row">
            <div class="sejarah-logo">
                @if(isset($profil->logo) && $profil->logo->path)
                    <img src="{{ asset('storage/' . $profil->logo->path) }}" alt="Logo JEB" onerror="this.style.display='none'; document.getElementById('logo-fallback').classList.add('active');">
                @else
                    <img src="{{ asset('img/images-landing-page/galeri/logo-jeb.jpg') }}" alt="Logo JEB Default" onerror="this.style.display='none'; document.getElementById('logo-fallback').classList.add('active');">
                @endif
                <div id="logo-fallback" class="inline-error-fallback"><i class="fas fa-image" style="font-size: 2rem;"></i></div>
            </div>
            <div class="sejarah-text">
                <h2>Sejarah Sanggar</h2>
                <p>{!! nl2br(e($profil->sejarah ?? 'Data sejarah sanggar belum diatur oleh admin.')) !!}</p>
            </div>
        </div>
    </div>
</section>

{{-- BAGIAN STAF PENGURUS, PELATIH, & ANGGOTA --}}
<section class="section-padding">
    <div class="container">
        <!-- FORM PENCARIAN & FILTER KATEGORI -->
        <form action="{{ route('umum.profil') }}" method="GET" id="formPublicProfil">
            <div class="search-filter-wrapper">
                <div class="search-box-container">
                    <i class="fas fa-search" onclick="submitPublicForm()"></i>
                    <input type="text" name="search" id="searchPublicProfil" class="search-input" placeholder="Cari nama personel, NIA, atau jabatan..." value="{{ request('search') }}">
                </div>
                <button type="button" class="btn-trigger-filter" onclick="openFilterModal()">
                    <i class="fas fa-filter"></i> Filter Kategori
                </button>
            </div>

            <!-- MODAL FILTER -->
            <div id="filterModal" class="custom-modal-overlay">
                <div class="custom-modal-content">
                    <div class="custom-modal-header">
                        <h3>Filter Kategori Personel</h3>
                        <p>Saring berdasarkan peran di sanggar</p>
                    </div>
                    <hr class="modal-divider">
                    <div class="custom-modal-body">
                        <label class="section-title">Peran Personel</label>
                        <div class="checkbox-grid">
                            @php $selectedPeran = request('peran', []); @endphp
                            <label class="filter-checkbox-card {{ in_array('pengurus', $selectedPeran) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="peran[]" value="pengurus" onchange="syncCardVisual(this)" {{ in_array('pengurus', $selectedPeran) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Pengurus</span>
                            </label>
                            <label class="filter-checkbox-card {{ in_array('pelatih', $selectedPeran) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="peran[]" value="pelatih" onchange="syncCardVisual(this)" {{ in_array('pelatih', $selectedPeran) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Pelatih</span>
                            </label>
                            <label class="filter-checkbox-card {{ in_array('anggota', $selectedPeran) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="peran[]" value="anggota" onchange="syncCardVisual(this)" {{ in_array('anggota', $selectedPeran) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Anggota</span>
                            </label>
                        </div>
                    </div>
                    <div class="custom-modal-footer">
                        <button type="button" class="btn-modal-batal" onclick="closeFilterModal()">Batal</button>
                        <button type="submit" class="btn-modal-terapkan">Terapkan Filter</button>
                    </div>
                </div>
            </div>
        </form>

        @if($isFiltered)
            <!-- HASIL PENCARIAN & FILTER -->
            <div class="text-center" style="margin-top: 2rem; margin-bottom: 2rem;">
                <h2 class="section-title-center">Hasil Pencarian Anggota</h2>
            </div>
            <div class="cards-grid">
                @forelse($personels as $p)
                    <div class="profile-card" onclick='openPersonnelModal(@json($p))'>
                        <div class="avatar-wrapper slideshow-container">
                            @if($p->galeri && $p->galeri->isNotEmpty())
                                @foreach($p->galeri as $index => $foto)
                                    <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $p->nama }}" class="{{ $index === 0 ? 'active' : '' }}">
                                @endforeach
                            @else
                                <img src="{{ asset('img/images-landing-page/personel/dummy-pengurus.jpg') }}" alt="{{ $p->nama }}" class="active">
                            @endif
                        </div>
                        <div class="profile-info">
                            <h4>{{ $p->nama }}</h4><p>{{ $p->jabatan ?? ucfirst($p->peran) }}</p>
                            <div class="profile-socials" onclick="event.stopPropagation()">
                                @if($p->no_handphone)<a href="https://wa.me/{{ $p->no_handphone }}" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>@endif
                                @if($p->instagram)<a href="https://instagram.com/{{ $p->instagram }}" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>@endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; color: #7f8c8d; padding: 60px 0;">
                        <i class="fas fa-users-slash" style="font-size: 3rem; color: #bdc3c7; margin-bottom: 15px; display: block;"></i>
                        Tidak ada personel yang ditemukan sesuai dengan kriteria pencarian Anda.
                    </div>
                @endforelse
            </div>

            @if($personels->hasPages())
                <div class="news-pagination">
                    <p>Menampilkan {{ $personels->firstItem() }} - {{ $personels->lastItem() }} dari {{ $personels->total() }} personel</p>
                    <div class="news-page-controls">
                        @if ($personels->onFirstPage()) <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                        @else <a href="{{ $personels->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a> @endif
                        @foreach ($personels->getUrlRange(1, $personels->lastPage()) as $page => $url)
                            @if ($page == $personels->currentPage()) <span class="active">{{ $page }}</span>
                            @else <a href="{{ $url }}">{{ $page }}</a> @endif
                        @endforeach
                        @if ($personels->hasMorePages()) <a href="{{ $personels->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                        @else <span class="disabled"><i class="fas fa-chevron-right"></i></span> @endif
                    </div>
                </div>
            @endif

        @else
            <!-- TAMPILAN NORMAL (PENGURUS, PELATIH, ANGGOTA) -->
            <!-- PENGURUS -->
            <div class="text-center"><h2 class="section-title-center">Pengurus Sanggar</h2></div>
            <div class="cards-grid">
                @foreach($pengurus as $p)
                    <div class="profile-card" onclick='openPersonnelModal(@json($p))'>
                        <div class="avatar-wrapper slideshow-container">
                            @forelse($p->galeri as $index => $foto)
                                <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $p->nama }}" class="{{ $index === 0 ? 'active' : '' }}">
                            @empty
                                <img src="{{ asset('img/images-landing-page/personel/dummy-pengurus.jpg') }}" alt="{{ $p->nama }}" class="active">
                            @endforelse
                        </div>
                        <div class="profile-info">
                            <h4>{{ $p->nama }}</h4><p>{{ $p->jabatan ?? 'Staf Pengurus' }}</p>
                            <div class="profile-socials" onclick="event.stopPropagation()">
                                @if($p->no_handphone)<a href="https://wa.me/{{ $p->no_handphone }}" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>@endif
                                @if($p->instagram)<a href="https://instagram.com/{{ $p->instagram }}" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>@endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- PELATIH -->
            <div class="text-center" style="margin-top: 2rem;"><h2 class="section-title-center">Pelatih Sanggar</h2></div>
            <div class="cards-grid">
                @foreach($pelatih as $pt)
                    <div class="profile-card" onclick='openPersonnelModal(@json($pt))'>
                        <div class="avatar-wrapper slideshow-container">
                            @forelse($pt->galeri as $index => $foto)
                                <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $pt->nama }}" class="{{ $index === 0 ? 'active' : '' }}">
                            @empty
                                <img src="{{ asset('img/images-landing-page/personel/dummy-pelatih.jpg') }}" alt="{{ $pt->nama }}" class="active">
                            @endforelse
                        </div>
                        <div class="profile-info">
                            <h4>{{ $pt->nama }}</h4><p>{{ $pt->jabatan ?? 'Pelatih Tari' }}</p>
                            <div class="profile-socials" onclick="event.stopPropagation()">
                                @if($pt->no_handphone)<a href="https://wa.me/{{ $pt->no_handphone }}" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>@endif
                                @if($pt->instagram)<a href="https://instagram.com/{{ $pt->instagram }}" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>@endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- ANGGOTA -->
            <div class="text-center" style="margin-top: 2rem;"><h2 class="section-title-center">Anggota Sanggar</h2></div>
            <div class="cards-grid">
                @foreach($anggota as $ag)
                    <div class="profile-card" onclick='openPersonnelModal(@json($ag))'>
                        <div class="avatar-wrapper slideshow-container">
                            @forelse($ag->galeri as $index => $foto)
                                <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $ag->nama }}" class="{{ $index === 0 ? 'active' : '' }}">
                            @empty
                                <img src="{{ asset('img/images-landing-page/personel/dummy-pelatih.jpg') }}" alt="{{ $ag->nama }}" class="active">
                            @endforelse
                        </div>
                        <div class="profile-info">
                            <h4>{{ $ag->nama }}</h4><p>{{ $ag->jabatan ?? 'Anggota' }}</p>
                            <div class="profile-socials" onclick="event.stopPropagation()">
                                @if($ag->no_handphone)<a href="https://wa.me/{{ $ag->no_handphone }}" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>@endif
                                @if($ag->instagram)<a href="https://instagram.com/{{ $ag->instagram }}" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>@endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- ========================================================= -->
<!-- MODAL / POPUP DETAIL PERSONEL -->
<!-- ========================================================= -->
<div id="personelModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 9999; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);">
    <div style="background: #fff; border-radius: 16px; max-width: 900px; width: 100%; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.25); position: relative; display: flex; flex-direction: column; max-height: 90vh; animation: modalPop 0.3s ease-out;">
        
        <!-- Tombol Close -->
        <button onclick="closePersonnelModal()" style="position: absolute; top: 15px; right: 15px; background: #6b1414; color: #fff; border: none; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; font-size: 16px; z-index: 20; display: flex; align-items: center; justify-content: center; transition: background 0.2s;"><i class="fas fa-times"></i></button>
        
        <div class="modal-grid-container" style="display: grid; grid-template-columns: 340px 1fr; overflow-y: auto;">
            
            <!-- Sisi Kiri: Foto / Slideshow -->
            <div style="background: #f4eae6; padding: 30px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-right: 1px solid #e8ded7;">
                <div id="modalFotoContainer" style="width: 100%; height: 340px; border-radius: 12px; overflow: hidden; position: relative; background: #ddd; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <!-- Foto akan di-render dinamis via JS -->
                </div>
                <!-- Tombol Media Sosial -->
                <div style="margin-top: 18px; display: flex; gap: 12px;" id="modalSocialContainer">
                    <a id="modalWaBtn" href="#" target="_blank" class="social-btn" style="width: 38px; height: 38px; font-size: 1rem; display: none;"><i class="fa-brands fa-whatsapp"></i></a>
                    <a id="modalIgBtn" href="#" target="_blank" class="social-btn" style="width: 38px; height: 38px; font-size: 1rem; display: none;"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>

            <!-- Sisi Kanan: Informasi Lengkap -->
            <div style="padding: 35px; display: flex; flex-direction: column; gap: 18px; text-align: left;">
                <div>
                    <h2 id="modalNama" style="font-family: 'Playfair Display', serif; color: #6b1414; font-size: 2rem; margin: 0 0 6px 0; font-weight: 700;"></h2>
                    <p style="margin: 0; font-size: 0.85rem; color: #666; font-weight: 500;">NIA: <span id="modalNia" style="font-weight: 600; color: #333;"></span></p>
                </div>

                <!-- Jabatan & Waktu (Masa Bakti / Periode / Bergabung Sejak) -->
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; background: #fdfaf8; padding: 12px 18px; border-radius: 10px; border-left: 4px solid #6b1414;">
                    <div>
                        <span style="font-size: 0.75rem; color: #888; display: block; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Jabatan / Status</span>
                        <span id="modalJabatan" style="font-weight: 700; color: #6b1414; font-size: 1.05rem;"></span>
                    </div>
                    <div id="modalWaktuWrapper" style="border-left: 1px solid #ddd; padding-left: 15px; display: none;">
                        <span id="modalWaktuLabel" style="font-size: 0.75rem; color: #888; display: block; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;"></span>
                        <span id="modalWaktuValue" style="font-weight: 600; color: #333; font-size: 0.95rem;"></span>
                    </div>
                </div>

                <!-- Bagian Deskripsi (Conditional / Hilang jika kosong) -->
                <div id="modalDeskripsiSection" style="display: none;">
                    <h4 style="font-family: 'Playfair Display', serif; color: #6b1414; font-size: 1.15rem; margin: 0 0 6px 0; font-weight: 700;">Deskripsi</h4>
                    <p id="modalDeskripsi" style="color: #444; font-size: 0.92rem; line-height: 1.7; margin: 0;"></p>
                </div>

                <!-- Bagian Prestasi (Conditional / Hilang jika kosong) -->
                <div id="modalPrestasiSection" style="display: none;">
                    <h4 style="font-family: 'Playfair Display', serif; color: #6b1414; font-size: 1.15rem; margin: 0 0 6px 0; font-weight: 700;">Prestasi</h4>
                    <p id="modalPrestasi" style="color: #444; font-size: 0.92rem; line-height: 1.7; margin: 0;"></p>
                </div>

                <!-- Footer: Tanggal Edit Terakhir -->
                <div style="margin-top: auto; padding-top: 15px; border-top: 1px solid #eee; font-size: 0.78rem; color: #888; text-align: right;">
                    Diedit pada tanggal: <span id="modalUpdatedAt" style="font-weight: 500; color: #555;"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes modalPop {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    @media (max-width: 768px) {
        .modal-grid-container {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. BANNER SLIDESHOW UTAMA
        const headerContainer = document.querySelector('.header-slideshow-container');
        if (headerContainer) {
            setInterval(() => {
                const slides = headerContainer.querySelectorAll('img:not([style*="display: none"]), .banner-error-fallback.active');
                if (slides.length > 1) {
                    let currentIndex = Array.from(slides).findIndex(el => el.classList.contains('active'));
                    if (currentIndex !== -1) {
                        slides[currentIndex].classList.remove('active');
                        slides[(currentIndex + 1) % slides.length].classList.add('active');
                    }
                }
            }, 7000);
        }

        // 2. AUTOMATIC SLIDESHOW MULTI-FOTO CARD PERSONEL (5 DETIK)
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

    // 3. FUNGSI MODAL POPUP PERSONEL
    function openPersonnelModal(personel) {
        document.getElementById('modalNama').textContent = personel.nama || '-';
        document.getElementById('modalNia').textContent = personel.nia || '-';
        document.getElementById('modalJabatan').textContent = personel.jabatan || personel.peran || '-';

        const waktuWrapper = document.getElementById('modalWaktuWrapper');
        const waktuLabel = document.getElementById('modalWaktuLabel');
        const waktuValue = document.getElementById('modalWaktuValue');

        if (personel.masa_bakti && personel.masa_bakti.trim() !== '') {
            waktuWrapper.style.display = 'block';
            waktuValue.textContent = personel.masa_bakti;

            if (personel.peran === 'anggota') {
                waktuLabel.textContent = 'Bergabung Sejak';
            } else if (personel.peran === 'pengurus') {
                waktuLabel.textContent = 'Periode';
            } else if (personel.peran === 'pelatih') {
                waktuLabel.textContent = 'Masa Bakti';
            } else {
                waktuLabel.textContent = 'Keterangan Waktu';
            }
        } else {
            waktuWrapper.style.display = 'none';
        }

        const deskripsiSection = document.getElementById('modalDeskripsiSection');
        const deskripsiText = document.getElementById('modalDeskripsi');
        if (personel.deskripsi && personel.deskripsi.trim() !== '') {
            deskripsiText.textContent = personel.deskripsi;
            deskripsiSection.style.display = 'block';
        } else {
            deskripsiSection.style.display = 'none';
        }

        const prestasiSection = document.getElementById('modalPrestasiSection');
        const prestasiText = document.getElementById('modalPrestasi');
        if (personel.prestasi && personel.prestasi.trim() !== '') {
            prestasiText.textContent = personel.prestasi;
            prestasiSection.style.display = 'block';
        } else {
            prestasiSection.style.display = 'none';
        }

        const waBtn = document.getElementById('modalWaBtn');
        if (personel.no_handphone && personel.no_handphone.trim() !== '') {
            waBtn.href = `https://wa.me/${personel.no_handphone}`;
            waBtn.style.display = 'flex';
        } else {
            waBtn.style.display = 'none';
        }

        const igBtn = document.getElementById('modalIgBtn');
        if (personel.instagram && personel.instagram.trim() !== '') {
            let igHandle = personel.instagram.startsWith('@') ? personel.instagram.substring(1) : personel.instagram;
            igBtn.href = `https://instagram.com/${igHandle}`;
            igBtn.style.display = 'flex';
        } else {
            igBtn.style.display = 'none';
        }

        const fotoContainer = document.getElementById('modalFotoContainer');
        fotoContainer.innerHTML = '';

        if (personel.galeri && personel.galeri.length > 0) {
            personel.galeri.forEach((foto, idx) => {
                const img = document.createElement('img');
                img.src = `{{ asset('storage') }}/${foto.path}`;
                img.alt = personel.nama;
                img.style.cssText = `width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: ${idx === 0 ? '1' : '0'}; transition: opacity 0.5s ease-in-out;`;
                img.className = idx === 0 ? 'modal-img active' : 'modal-img';
                fotoContainer.appendChild(img);
            });
        } else {
            const img = document.createElement('img');
            let defaultDummy = personel.peran === 'pengurus' ? 'dummy-pengurus.jpg' : 'dummy-pelatih.jpg';
            img.src = `{{ asset('img/images-landing-page/personel/') }}/${defaultDummy}`;
            img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;';
            fotoContainer.appendChild(img);
        }

        if (personel.updated_at) {
            const dateObj = new Date(personel.updated_at);
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById('modalUpdatedAt').textContent = dateObj.toLocaleDateString('id-ID', options);
        } else {
            document.getElementById('modalUpdatedAt').textContent = '-';
        }

        document.getElementById('personelModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closePersonnelModal() {
        document.getElementById('personelModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('personelModal');
        if (event.target === modal) {
            closePersonnelModal();
        }
    });
</script>
<!-- Script Filter Modal & Submit -->
<script>
    function openFilterModal() { document.getElementById('filterModal').style.display = 'flex'; document.body.style.overflow = 'hidden'; }
    function closeFilterModal() { document.getElementById('filterModal').style.display = 'none'; document.body.style.overflow = ''; }
    window.onclick = function(event) { if (event.target == document.getElementById('filterModal')) closeFilterModal(); }
    function syncCardVisual(c) { let p = c.closest('.filter-checkbox-card'); if (c.checked) p.classList.add('selected-box'); else p.classList.remove('selected-box'); }
    function submitPublicForm() { document.getElementById('formPublicProfil').submit(); }

    const searchProfilInput = document.getElementById('searchPublicProfil');
    if (searchProfilInput) {
        searchProfilInput.addEventListener('keypress', function(e) { 
            if (e.key === 'Enter') { e.preventDefault(); submitPublicForm(); } 
        });
    }
</script>
@endsection