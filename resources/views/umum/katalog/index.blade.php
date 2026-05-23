@extends('layouts.umum')

@section('content')
<style>
    /* Styling Banner */
    .katalog-banner {
        width: 100%;
        height: 350px;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('img/images-landing-page/banner_katalog.jpeg') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 8%;
        color: white;
    }

    /* Container agar tidak terlalu menempel ke pinggir */
    .katalog-wrapper {
        width: 95%; /* Mengambil hampir seluruh lebar layar */
        max-width: 1400px; /* Batas maksimal agar tidak terlalu lebar di layar besar */
        margin: 40px auto;
    }

    /* Grid 4 Kolom */
    .katalog-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); 
        gap: 20px;
    }

    .katalog-card {
        background: #fff;
        border-radius: 12px;
        padding: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: 0.3s;
    }

    .katalog-card:hover {
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

<div class="katalog-banner">
    <div style="background: #C0392B; padding: 5px 15px; border-radius: 5px; width: fit-content; margin-bottom: 15px;">
        Dokumentasi
    </div>
    <h1 style="font-size: 3rem; margin: 0;">Katalog Sanggar</h1>
    <p style="font-size: 1.2rem;">Momen indah perjalanan seni dan Budaya Sanggar Tari Jiwa Etnik Blambangan</p>
</div>

<div class="katalog-wrapper">
    <div class="section-header" style="margin-bottom: 30px;">
        <h2>Katalog Kostum</h2>
        <p>Koleksi Kostum tari tradisional dan kreasi Sanggar JEB</p>
    </div>

<div class="search-filter-wrapper">
    <input type="text" class="search-input" placeholder="Cari nama tari....">
    <button style="padding: 15px 30px; background: #C0392B; color: white; border: none; border-radius: 10px; cursor: pointer;">
        Filter
    </button>
</div>

    <div class="katalog-grid">
        @foreach($katalogs as $item)

    <a href="{{ route('umum.katalog-detail', $item->id) }}" style="text-decoration: none; color: inherit;">
        <div class="katalog-card">
            <img src="{{ asset($item->galeri->file_blob ?? 'default.jpg') }}" style="width:100%; height: 180px; object-fit: cover; border-radius:8px;">
            <div style="padding: 10px 0;">
                <span style="font-size: 10px; background: #f1c40f; padding: 2px 6px; border-radius: 4px;">{{ $item->kategori }}</span>
                <h4 style="margin: 8px 0;">{{ $item->nama_tari }}</h4>
                <p style="font-size: 12px; color: #666;">{{ $item->deskripsi }}</p>
                <div style="display: flex; justify-content: space-between; margin-top: 10px; font-size: 12px; font-weight: bold;">
                    <span style="color: green;">{{ ucfirst($item->status) }}</span>
                    <span>Stok: {{ $item->stok }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection