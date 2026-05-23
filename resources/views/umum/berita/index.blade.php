@extends('layouts.umum')

@section('content')
<style>
    /* Styling Banner - Menggunakan desain yang sama dengan katalog */
    .berita-banner {
        width: 100%;
        height: 350px;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('img/images-landing-page/banner_berita.jpeg') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 8%;
        color: white;
    }

    .berita-wrapper {
        width: 95%;
        max-width: 1400px;
        margin: 40px auto;
    }

    /* Grid 3 atau 4 Kolom agar terlihat seperti layout majalah */
    .berita-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .berita-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: 0.3s;
    }

    .berita-card:hover {
        transform: translateY(-5px);
    }

    .search-filter-wrapper {
        background: transparent; /* Atau ganti dengan #E7DCDA jika ingin sama persis */
        padding: 20px 8%;
        display: flex;
        gap: 15px;
        align-items: center;
        margin-top: -30px; /* Opsional: sedikit naik ke atas banner jika mau */
    }

    .search-input {
        flex: 1;
        padding: 15px 20px;
        border-radius: 10px; /* Disesuaikan dengan desain kotak */
        border: 1px solid #ccc;
        background: white; /* Search bar tetap putih agar kontras */
    }

</style>

<div class="berita-banner">
    <div style="background: #C0392B; padding: 5px 15px; border-radius: 5px; width: fit-content; margin-bottom: 15px;">
        Berita Terbaru
    </div>
    <h1 style="font-size: 3rem; margin: 0;">Artikel & Berita</h1>
    <p style="font-size: 1.2rem;">Ikuti kabar terbaru seputar kegiatan dan perkembangan Sanggar JEB</p>
</div>

<div class="berita-wrapper">
    <div class="section-header" style="margin-bottom: 30px;">
        <h2>Daftar Berita</h2>
    </div>

    <div class="search-filter-wrapper">
    <input type="text" class="search-input" placeholder="Cari nama tari....">
    <button style="padding: 15px 30px; background: #C0392B; color: white; border: none; border-radius: 10px; cursor: pointer;">
        Filter
    </button>
</div>

    <div class="berita-grid">
        @foreach($beritas as $item)
        <a href="{{ route('umum.berita-detail', $item->id) }}" style="text-decoration: none; color: inherit;">
            <div class="berita-card">
                <img src="{{ asset($item->galeri->file_blob ?? 'default.jpg') }}" style="width:100%; height: 200px; object-fit: cover;">
                <div style="padding: 20px;">
                    <span style="font-size: 12px; color: #888;">{{ \Carbon\Carbon::parse($item->tgl_terbit)->format('d M Y') }}</span>
                    <h4 style="margin: 10px 0;">{{ $item->judul }}</h4>
                    <p style="font-size: 14px; color: #666;">{{ Str::limit(strip_tags($item->isi_berita), 100) }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection