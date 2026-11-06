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
    .profile-card { background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.04); text-align: center; padding-bottom: 1rem; }
    
    /* MODIFIKASI: Memaksa gambar bertumpuk dengan posisi absolute agar animasi transition opacity berjalan sempurna */
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

        <div class="sambutan-row">
            <div class="sambutan-text">
                <h3>Sambutan Ketua</h3>
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

{{-- BAGIAN STAF PENGURUS & PELATIH --}}
<section class="section-padding">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title-center">Pengurus Sanggar</h2>
        </div>
        <div class="cards-grid">
            @forelse($pengurus as $p)
                <div class="profile-card">
                    {{-- MODIFIKASI: Container dirubah menjadi class avatar-wrapper (slideshow-target) --}}
                    <div class="avatar-wrapper slideshow-container">
                        @if($p->galeri && $p->galeri->isNotEmpty())
                            {{-- MODIFIKASI: Looping mengeluarkan seluruh gambar dari database relasi galeri --}}
                            @foreach($p->galeri as $index => $foto)
                                <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $p->nama }}" class="{{ $index === 0 ? 'active' : '' }}" onerror="this.style.display='none'; document.getElementById('pengurus-fallback-{{ $p->id }}').classList.add('active');">
                            @endforeach
                        @else
                            <img src="{{ asset('img/images-landing-page/personel/dummy-pengurus.jpg') }}" alt="{{ $p->nama }}" class="active" onerror="this.style.display='none'; document.getElementById('pengurus-fallback-{{ $p->id }}').classList.add('active');">
                        @endif
                        <div id="pengurus-fallback-{{ $p->id }}" class="inline-error-fallback"><i class="fas fa-user" style="font-size: 2rem;"></i></div>
                    </div>
                    <div class="profile-info">
                        <h4>{{ $p->nama }}</h4><p>{{ $p->jabatan ?? 'Staf Pengurus' }}</p>
                        <div class="profile-socials">
                            <a href="https://wa.me/{{ $p->no_handphone }}" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                            @if($p->instagram)
                                <a href="https://instagram.com/{{ $p->instagram }}" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                @for ($i = 0; $i < 5; $i++)
                <div class="profile-card">
                    <div class="avatar-wrapper">
                        <img src="{{ asset('img/images-landing-page/personel/dummy-pengurus.jpg') }}" alt="Bpk. Adlin" class="active" onerror="this.style.display='none'; document.getElementById('pengurus-empty-{{ $i }}').classList.add('active');">
                        <div id="pengurus-empty-{{ $i }}" class="inline-error-fallback"><i class="fas fa-user" style="font-size: 2rem;"></i></div>
                    </div>
                    <div class="profile-info">
                        <h4>Bpk. Adlin</h4><p>Founder</p>
                        <div class="profile-socials">
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                @endfor
            @endforelse
        </div>

        <div class="text-center" style="margin-top: 2rem;">
            <h2 class="section-title-center">Pelatih Sanggar</h2>
        </div>
        <div class="cards-grid">
            @forelse($pelatih as $pt)
                <div class="profile-card">
                    {{-- MODIFIKASI: Container dirubah menjadi class avatar-wrapper (slideshow-target) --}}
                    <div class="avatar-wrapper slideshow-container">
                        @if($pt->galeri && $pt->galeri->isNotEmpty())
                            {{-- MODIFIKASI: Looping mengeluarkan seluruh gambar dari database relasi galeri --}}
                            @foreach($pt->galeri as $index => $foto)
                                <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $pt->nama }}" class="{{ $index === 0 ? 'active' : '' }}" onerror="this.style.display='none'; document.getElementById('pelatih-fallback-{{ $pt->id }}').classList.add('active');">
                            @endforeach
                        @else
                            <img src="{{ asset('img/images-landing-page/personel/dummy-pelatih.jpg') }}" alt="{{ $pt->nama }}" class="active" onerror="this.style.display='none'; document.getElementById('pelatih-fallback-{{ $pt->id }}').classList.add('active');">
                        @endif
                        <div id="pelatih-fallback-{{ $pt->id }}" class="inline-error-fallback"><i class="fas fa-user" style="font-size: 2rem;"></i></div>
                    </div>
                    <div class="profile-info">
                        <h4>{{ $pt->nama }}</h4><p>{{ $pt->jabatan ?? 'Pelatih Tari' }}</p>
                        <div class="profile-socials">
                            <a href="https://wa.me/{{ $pt->no_handphone }}" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                            @if($pt->instagram)
                                <a href="https://instagram.com/{{ $pt->instagram }}" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                @for ($i = 0; $i < 5; $i++)
                <div class="profile-card">
                    <div class="avatar-wrapper">
                        <img src="{{ asset('img/images-landing-page/personel/dummy-pelatih.jpg') }}" alt="Bpk. Adlin" class="active" onerror="this.style.display='none'; document.getElementById('pelatih-empty-{{ $i }}').classList.add('active');">
                        <div id="pelatih-empty-{{ $i }}" class="inline-error-fallback"><i class="fas fa-user" style="font-size: 2rem;"></i></div>
                    </div>
                    <div class="profile-info">
                        <h4>Bpk. Adlin</h4><p>Founder</p>
                        <div class="profile-socials">
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                @endfor
            @endforelse
        </div>
        <div class="text-center" style="margin-top: 2rem;">
            <h2 class="section-title-center">Anggota Sanggar</h2>
        </div>
        <div class="cards-grid">
            @forelse($anggota as $ag)
                <div class="profile-card">
                    {{-- MODIFIKASI: Container dirubah menjadi class avatar-wrapper (slideshow-target) --}}
                    <div class="avatar-wrapper slideshow-container">
                        @if($ag->galeri && $ag->galeri->isNotEmpty())
                            {{-- MODIFIKASI: Looping mengeluarkan seluruh gambar dari database relasi galeri --}}
                            @foreach($ag->galeri as $index => $foto)
                                <img src="{{ asset('storage/' . $foto->path) }}" alt="{{ $ag->nama }}" class="{{ $index === 0 ? 'active' : '' }}" onerror="this.style.display='none'; document.getElementById('anggota-fallback-{{ $ag->id }}').classList.add('active');">
                            @endforeach
                        @else
                            <img src="{{ asset('img/images-landing-page/personel/dummy-pelatih.jpg') }}" alt="{{ $ag->nama }}" class="active" onerror="this.style.display='none'; document.getElementById('anggota-fallback-{{ $ag->id }}').classList.add('active');">
                        @endif
                        <div id="anggota-fallback-{{ $ag->id }}" class="inline-error-fallback"><i class="fas fa-user" style="font-size: 2rem;"></i></div>
                    </div>
                    <div class="profile-info">
                        <h4>{{ $ag->nama }}</h4><p>{{ $ag->jabatan ?? 'Anggota' }}</p>
                        <div class="profile-socials">
                            <a href="https://wa.me/{{ $ag->no_handphone }}" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                            @if($ag->instagram)
                                <a href="https://instagram.com/{{ $ag->instagram }}" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                @for ($i = 0; $i < 5; $i++)
                <div class="profile-card">
                    <div class="avatar-wrapper">
                        <img src="{{ asset('img/images-landing-page/personel/dummy-pelatih.jpg') }}" alt="Bpk. Adlin" class="active" onerror="this.style.display='none'; document.getElementById('anggota-empty-{{ $i }}').classList.add('active');">
                        <div id="anggota-empty-{{ $i }}" class="inline-error-fallback"><i class="fas fa-user" style="font-size: 2rem;"></i></div>
                    </div>
                    <div class="profile-info">
                        <h4>Bpk. Adlin</h4><p>Founder</p>
                        <div class="profile-socials">
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                @endfor
            @endforelse
        </div>
    </div>
</section>

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
        // Berjalan dinamis menyapu semua container profile-card yang mempunyai class .slideshow-container
        const containers = document.querySelectorAll('.slideshow-container');
        containers.forEach(container => {
            const images = container.querySelectorAll('img');
            if (images.length > 1) {
                let currentIndex = 0;
                setInterval(() => {
                    images[currentIndex].classList.remove('active');
                    currentIndex = (currentIndex + 1) % images.length;
                    images[currentIndex].classList.add('active');
                }, 5000); // Berganti otomatis setiap 5 detik
            }
        });
    });
</script>
@endsection