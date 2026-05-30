@extends('layouts.umum')

@section('title', 'Profil Sanggar - Sanggar JEB')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Global & Utilities */
    body {
        background-color: #f4eae6;
        font-family: 'Poppins', sans-serif;
    }
    .container { width: 88%; max-width: 1200px; margin: 0 auto; }
    .section-padding { padding: 4rem 0; }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    
    /* Typography */
    .title-maroon {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #6b1414;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    .section-title-center {
        font-family: 'Playfair Display', serif;
        color: #6b1414;
        font-size: 1.8rem;
        font-weight: 700;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }

    
    .about-hero {
        position: relative;
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("{{ asset('img/images-landing-page/banner_katalog.jpeg') }}") no-repeat center center;
        background-size: cover;
        color: white;
        padding: 8rem 0 6rem;
        text-align: left; /* <--- UBAH INI dari center menjadi left */
    }
    .badge-red {
        background-color: #b22222;
        color: white;
        padding: 6px 24px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: bold;
        letter-spacing: 1px;
        display: inline-block;
    }
    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        margin: 1rem 0 0.5rem;
        font-weight: 700;
    }
    .hero-subtitle-top {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.8);
    }

    /* Grid Deskripsi & Visi Misi */
    .deskripsi-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 3rem;
        align-items: start;
    }
    .deskripsi-text p {
        color: #333;
        line-height: 1.8;
        font-size: 0.95rem;
        margin-bottom: 1.2rem;
    }
    .vm-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .vm-card {
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .vm-card h3 {
        color: #6b1414;
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        margin-bottom: 0.8rem;
        font-weight: 700;
    }
    .vm-card p, .vm-card ol {
        color: #555;
        font-size: 0.85rem;
        line-height: 1.6;
        padding-left: 1rem;
        margin: 0;
    }
    .vm-card ol li { margin-bottom: 0.4rem; }

    /* Sejarah Section */
    .sejarah-section {
        background-color: #e8ded7;
        padding: 4rem 0;
    }
    .sejarah-row {
        display: flex;
        gap: 3rem;
        align-items: center;
        margin-bottom: 4rem;
    }
    .sejarah-logo {
    width: 180px;
    height: 180px;
    flex-shrink: 0;
    background: #fff;
    border-radius: 50%;
    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    overflow: hidden; 
}

    .sejarah-logo img {
        width: 100%; 
        height: 100%;
        object-fit: cover; 
        border-radius: 50%; 
    }
    .sejarah-text h2 {
        font-family: 'Playfair Display', serif;
        color: #6b1414;
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }
    .sejarah-text p {
        color: #444;
        line-height: 1.7;
        font-size: 0.9rem;
    }

    /* Sambutan Ketua Row */
    .sambutan-row {
        display: grid;
        grid-template-columns: 1.3fr 0.7fr;
        gap: 4rem;
        align-items: center;
    }
    .sambutan-text {
        text-align: center;
    }
    .sambutan-text h3 {
        font-family: 'Playfair Display', serif;
        color: #6b1414;
        font-size: 1.6rem;
        margin-bottom: 1rem;
    }
    .sambutan-text p {
        color: #444;
        font-size: 0.95rem;
        line-height: 1.8;
        font-style: italic;
    }
    .pembina-wrapper {
        text-align: center;
    }
    .pembina-image {
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .pembina-image img {
        width: 100%;
        height: auto;
        display: block;
    }
    .pembina-name {
        margin-top: 0.8rem;
        font-weight: 700;
        color: #6b1414;
        font-size: 1.1rem;
    }

    /* Grid Anggota / Pengurus & Pelatih */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.2rem;
        margin-top: 1.5rem;
        margin-bottom: 3rem;
    }
    .profile-card {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        text-align: center;
        padding-bottom: 1rem;
    }
    .avatar-wrapper {
        width: 100%;
        height: 180px;
        overflow: hidden;
        background-color: #eee;
    }
    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-info {
        padding: 0.8rem 0.5rem 0.4rem;
    }
    .profile-info h4 {
        margin: 0;
        font-size: 0.95rem;
        color: #333;
        font-weight: 700;
    }
    .profile-info p {
        margin: 2px 0 8px;
        font-size: 0.75rem;
        color: #888;
        font-weight: 500;
    }
    .profile-socials {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .social-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: #6b1414;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .social-btn:hover {
        background-color: #b22222;
    }

    @media (max-width: 1024px) {
        .cards-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .deskripsi-grid, .sambutan-row, .sejarah-row { grid-template-columns: 1fr; flex-direction: column; }
        .cards-grid { grid-template-columns: repeat(2, 1fr); }
        .sejarah-logo { margin: 0 auto; }
    }
</style>
@endpush

@section('content')
<section class="about-hero">
    <div class="container">
        <span class="badge-red">Profil</span>
        <h1 class="hero-title">Profil Sanggar</h1>
        <p class="hero-subtitle-top">Profil sanggar tari JEB, sejarah JEB, dan daftar Kepengurusan JEB</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="deskripsi-grid">
            <div class="deskripsi-text">
                <h2 class="title-maroon" style="margin-top: 0;">Deskripsi Sanggar</h2>
                <p><strong>Sanggar Tari Jawi Etnik Blambangan (JEB)</strong> didirikan sebagai wadah pelestarian seni dan budaya tradisional asli Banyuwangi. Kami berfokus pada pengembangan bakat generasi muda melalui pelatihan tari, musik tradisional, serta pertunjukan seni.</p>
                <p>Dengan pendekatan yang menggabungkan nilai-nilai luhur tradisi dan kreativitas kontemporer, Sanggar JEB terus berkomitmen untuk mengharumkan nama Banyuwangi di berbagai festival.</p>
            </div>

            <div class="vm-container">
                <div class="vm-card">
                    <h3>Visi</h3>
                    <p>Menjadi sanggar seni yang unggul, berkarakter (khas) dan memiliki keunikan tersendiri (berbeda) serta mampu menghasilkan karya nyata dalam pelestarian dan pengembangan seni budaya Blambangan di Banyuwangi.</p>
                </div>
                <div class="vm-card">
                    <h3>Misi</h3>
                    <ol>
                        <li>Menyelenggarakan pelatihan rutin.</li>
                        <li>Mendokumentasikan tarian klasik.</li>
                        <li>Aktif dalam pementasan/festival.</li>
                        <li>Membangun karakter disiplin anggota.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="sejarah-section">
    <div class="container">
        <div class="sejarah-row">
            <div class="sejarah-logo">
                <img src="{{ asset('img/images-landing-page/galeri/logo-jeb.jpg') }}" alt="Logo JEB">
            </div>
            <div class="sejarah-text">
                <h2>Sejarah Sanggar</h2>
                <p>Jiwa Etnik Blambangan (JEB) didirikan pada akhir tahun 2017 oleh Adlin Mustika Alam sebagai wadah pelestarian seni dan budaya etnik, khususnya di wilayah Banyuwangi. JEB hadir dengan konsep pembelajaran yang terstruktur melalui kurikulum berbasis usia dan kemampuan, mulai dari tingkat TK hingga SMA. Pada tahun 2021, JEB resmi terdaftar di Kementerian Hukum dan HAM sebagai lembaga yang legal dan profesional. Saat ini, JEB dipimpin oleh Cinta dan terus berkembang dengan berbagai kegiatan seperti latihan rutin, ujian, pementasan, serta event budaya.</p>
                <p>JEB bertujuan menjadi wadah kreatif bagi generasi muda sekaligus memperkenalkan budaya etnik agar lebih dikenal luas oleh masyarakat.</p>
            </div>
        </div>

        <div class="sambutan-row">
            <div class="sambutan-text">
                <h3>Sejarah Sanggar</h3>
                <p>"Selamat datang di website resmi Jiwa Etnik Blambangan (JEB). Saya sebagai Ketua JEB, dengan bangga menyambut Anda dalam perjalanan melestarikan seni dan budaya etnik bersama kami. Mari bergabung, berkarya, dan menjaga warisan budaya melalui JEB. Terima kasih atas dukungan Anda dalam menjadikan JEB sebagai wadah kreativitas dan pelestarian budaya."</p>
            </div>
            <div class="pembina-wrapper">
                <div class="pembina-image">
                    <img src="{{ asset('img/images-landing-page/galeri/foto_pembina.jpeg') }}" alt="Adlin Mustika Alam">
                </div>
                <div class="pembina-name">Adlin mustika alam</div>
            </div>
        </div>
    </div>
</section>

<!-- Pengurus Sanggar Section -->
<section class="section-padding">
    <div class="container">
        <div class="text-center">
            <span style="color: #b22222; font-size: 0.8rem; font-weight: 700; letter-spacing: 1px;">PENGURUS SANGGAR</span>
            <h2 class="section-title-center">Pengurus Sanggar</h2>
        </div>

        <div class="cards-grid">
            @forelse($pengurus as $p)
                <div class="profile-card">
                    <div class="avatar-wrapper">
                        <img src="{{ asset('img/images-landing-page/personel/dummy-pengurus.jpg') }}" alt="{{ $p->nama }}">
                    </div>
                    <div class="profile-info">
                        <h4>{{ $p->nama }}</h4>
                        <p>{{ $p->jabatan ?? 'Founder' }}</p>
                        <div class="profile-socials">
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            @empty
                @for ($i = 0; $i < 5; $i++)
                <div class="profile-card">
                    <div class="avatar-wrapper">
                        <img src="{{ asset('img/images-landing-page/personel/dummy-pengurus.jpg') }}" alt="Bpk. Adlin">
                    </div>
                    <div class="profile-info">
                        <h4>Bpk. Adlin</h4>
                        <p>Founder</p>
                        <div class="profile-socials">
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                @endfor
            @endforelse
        </div>

        <!-- Pelatih Sanggar Section -->
        <div class="text-center" style="margin-top: 2rem;">
            <span style="color: #b22222; font-size: 0.8rem; font-weight: 700; letter-spacing: 1px;">PELATIH SANGGAR</span>
            <h2 class="section-title-center">Pelatih Sanggar</h2>
        </div>

        <div class="cards-grid">
            @forelse($pelatih as $pt)
                <div class="profile-card">
                    <div class="avatar-wrapper">
                        <img src="{{ asset('img/images-landing-page/personel/dummy-pelatih.jpg') }}" alt="{{ $pt->nama }}">
                    </div>
                    <div class="profile-info">
                        <h4>{{ $pt->nama }}</h4>
                        <p>{{ $pt->jabatan ?? 'Pelatih Tari' }}</p>
                        <div class="profile-socials">
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="#" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            @empty
                @for ($i = 0; $i < 5; $i++)
                <div class="profile-card">
                    <div class="avatar-wrapper">
                        <img src="{{ asset('img/images-landing-page/personel/dummy-pelatih.jpg') }}" alt="Bpk. Adlin">
                    </div>
                    <div class="profile-info">
                        <h4>Bpk. Adlin</h4>
                        <p>Founder</p>
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
@endsection