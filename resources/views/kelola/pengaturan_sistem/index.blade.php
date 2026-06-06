@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem — JEB')
@section('header_title', 'Pengaturan Sistem')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .settings-wrapper { 
        width: 100%; 
        font-family: 'Poppins', sans-serif; 
        color: #333; 
    }
    
    .settings-card { 
        background: #fff; 
        border-radius: 12px; 
        padding: 25px; 
        border: 1px solid #f0f0f0; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.02); 
        margin-bottom: 28px; 
    }
    
    .settings-card .card-header-flex { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 25px; 
    }
    
    .settings-card h3 { 
        margin: 0; 
        font-size: 1.1rem; 
        color: #333; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        font-weight: 600; 
    }
    
    .settings-card h3 i { 
        color: #5B1A1A; 
    }
    
    .form-grid { 
        display: grid; 
        grid-template-columns: repeat(2, 1fr); 
        gap: 20px; 
    }
    
    .form-group { 
        display: flex; 
        flex-direction: column; 
        gap: 6px; 
    }
    
    .form-group label { 
        font-size: 0.75rem; 
        font-weight: 600; 
        text-transform: uppercase; 
        color: #b07d7d; 
        letter-spacing: 0.5px; 
    }
    
    .value-box { 
        font-size: 0.85rem; 
        color: #333; 
        background: #fffbfb; 
        padding: 12px 16px; 
        border-radius: 8px; 
        border: 1px solid #e8dede; 
        word-break: break-all; 
    }
    
    .full-width { 
        grid-column: span 2; 
    }
    
    .btn-edit-link { 
        background: #e2c0c0; 
        color: #5a1a1a; 
        border-radius: 12px; 
        padding: 8px 20px; 
        font-weight: 700; 
        font-size: 0.85rem; 
        text-decoration: none; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
        transition: background-color 0.2s;
    }
    
    .btn-edit-link:hover {
        background: #d4aeae;
    }

    /* ══ FRAMING PREVIEW SLIDESHOW ATAS ══ */
    .header-preview-display { 
        width: 100%; 
        height: 240px; 
        background: #f8f9fa; 
        border-radius: 10px; 
        overflow: hidden; 
        position: relative; 
        border: 1px solid #e8dede; 
        margin-bottom: 25px; 
    }
    
    .header-preview-display img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        position: absolute; 
        top: 0; 
        left: 0; 
        opacity: 0; 
        transition: opacity 0.8s ease-in-out; 
    }
    
    .header-preview-display img.active { 
        opacity: 1; 
    }

    /* ══ WADAH MINI CARD BERJEJER KE SAMPING ══ */
    .header-flex-row-list {
        display: flex;
        flex-direction: row;
        gap: 16px;
        width: 100%;
        overflow-x: auto;
        padding-bottom: 12px;
        -webkit-overflow-scrolling: touch;
    }

    /* Kapsul Mini Card */
    .header-mini-card {
        background: #ffffff;
        border: 1px solid #e8dede;
        border-radius: 8px;
        padding: 6px;
        width: 140px;
        min-width: 140px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.01);
        box-sizing: border-box;
        text-align: center;
    }

    /* Frame Gambar di Dalam Card */
    .header-mini-card .img-thumb-frame {
        width: 100%;
        height: 75px;
        border-radius: 6px;
        overflow: hidden;
        background: #f3f4f6;
        border: 1px solid #f0f0f0;
    }

    .header-mini-card .img-thumb-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Label Teks di Bawah Gambar Mini */
    .header-mini-card .card-meta {
        font-size: 0.75rem;
        font-weight: 500;
        color: #666;
        margin-top: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (max-width: 768px) { 
        .form-grid { grid-template-columns: 1fr; } 
        .full-width { grid-column: span 1; } 
        .settings-card .card-header-flex { flex-direction: column; align-items: flex-start; gap: 12px; } 
    }
</style>

<div class="settings-wrapper">
    @if(session('success'))
        <div style="background:#dcfce7; color:#16a34a; padding:12px 16px; border-radius:12px; margin-bottom:24px; font-size:0.85rem; border:1px solid #bbf7d0;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- INTERFACE SLIDESHOW HEADER --}}
    <div class="settings-card">
        <div class="card-header-flex">
            <h3><i class="fas fa-images"></i> Gambar Banner Slideshow (Header)</h3>
            <a href="{{ route('superadmin.pengaturan-sistem.edit') }}" class="btn-edit-link"><i class="fas fa-cog"></i> Kelola Sistem</a>
        </div>
        
        {{-- Frame Karosel Slideshow --}}
        <div class="header-preview-display slideshow-container">
            @if($sistem->headers && $sistem->headers->isNotEmpty())
                @foreach($sistem->headers as $index => $header)
                    <img src="{{ asset('storage/' . $header->path) }}" alt="Header" class="{{ $index === 0 ? 'active' : '' }}">
                @endforeach
            @else
                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; width:100%; height:100%; color:#9ca3af; gap:8px;">
                    <i class="fas fa-image" style="font-size:2rem;"></i><span>Belum ada banner header.</span>
                </div>
            @endif
        </div>

        {{-- Label Pemisah Subjudul --}}
        <div style="font-size: 0.8rem; font-weight: 600; color: #b07d7d; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">
            Koleksi Gambar Aktif
        </div>

        {{-- Koleksi Gambar Mini: Berjejer Menyamping --}}
        <div class="header-flex-row-list">
            @forelse($sistem->headers as $key => $header)
                <div class="header-mini-card">
                    <div class="img-thumb-frame">
                        <img src="{{ asset('storage/' . $header->path) }}" alt="Thumb">
                    </div>
                    <div class="card-meta">Header {{ $key + 1 }}</div>
                </div>
            @empty
                <div style="font-size:0.85rem; color:#888; font-style:italic; padding: 10px 0;">Daftar banner kosong.</div>
            @endforelse
        </div>
    </div>

    {{-- INTERFACE LINK SOSMED --}}
    <div class="settings-card">
        <div class="card-header-flex">
            <h3><i class="fas fa-share-alt"></i> Link Media Sosial & Kontak</h3>
        </div>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Google Maps Iframe Embed/Link</label>
                <div class="value-box">{{ $sistem->link_maps ?? 'Belum terkonfigurasi' }}</div>
            </div>
            <div class="form-group">
                <label>WhatsApp</label>
                <div class="value-box"><i class="fab fa-whatsapp"></i> {{ $sistem->link_wa ?? '-' }}</div>
            </div>
            <div class="form-group">
                <label>Email (Mailto)</label>
                <div class="value-box"><i class="far fa-envelope"></i> {{ $sistem->link_email ?? '-' }}</div>
            </div>
            <div class="form-group">
                <label>Instagram</label>
                <div class="value-box"><i class="fab fa-instagram"></i> {{ $sistem->link_instagram ?? '-' }}</div>
            </div>
            <div class="form-group">
                <label>TikTok</label>
                <div class="value-box"><i class="fab fa-tiktok"></i> {{ $sistem->link_tiktok ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const containers = document.querySelectorAll('.slideshow-container');
        containers.forEach(container => {
            const images = container.querySelectorAll('img');
            if (images.length > 1) {
                let curr = 0;
                setInterval(() => {
                    images[curr].classList.remove('active');
                    curr = (curr + 1) % images.length;
                    images[curr].classList.add('active');
                }, 5000);
            }
        });
    });
</script>
@endsection