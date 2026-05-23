@extends('layouts.umum')

@section('content')
<style>
    /* Styling Dasar Halaman */
    .detail-container {
        background: #E7DCDA;
        padding: 50px 8%;
        min-height: 100vh;
    }

    /* Kartu Detail Utama */
    .main-detail-card {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 60px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .sewa-btn {
        background: #800000;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 12px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .sewa-btn:hover { background: #5a0000; }

    /* Grid Katalog Lain */
    .katalog-lain-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .lain-card {
        background: white;
        padding: 10px;
        border-radius: 12px;
        transition: 0.3s;
    }

    .lain-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>

<div class="detail-container">
    <div class="main-detail-card">
        <div class="detail-header">
            <h1 style="margin: 0;">{{ $katalog->nama_tari }}</h1>
            <button class="sewa-btn">Sewa Kostum</button>
        </div>
        
        <img src="{{ asset($katalog->galeri->file_blob ?? 'default.jpg') }}" style="width:100%; height: 500px; object-fit: cover; border-radius: 15px;">
        
        <div style="margin-top: 30px;">
            <h3>{{ $katalog->nama_tari }}</h3>
            <p style="color: #555; line-height: 1.6;">{{ $katalog->deskripsi }}</p>
            <div style="display: flex; gap: 30px; font-weight: bold; margin-top: 20px;">
                <span style="color: green;">{{ ucfirst($katalog->status) }}</span>
                <span>Stok: {{ $katalog->stok }}</span>
            </div>
        </div>
    </div>

    <h2 style="margin-bottom: 25px;">Katalog Lain</h2>
    <div class="katalog-lain-grid">
        @foreach($katalogLain as $lain)
        <a href="{{ route('umum.katalog-detail', $lain->id) }}" style="text-decoration: none; color: inherit;">
            <div class="lain-card">
                <img src="{{ asset($lain->galeri->file_blob ?? 'default.jpg') }}" style="width:100%; height: 160px; object-fit: cover; border-radius: 8px;">
                <h4 style="margin: 10px 0 5px 0;">{{ $lain->nama_tari }}</h4>
                <p style="font-size: 11px; color: #888;">{{ Str::limit($lain->deskripsi, 50) }}</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection