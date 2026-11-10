<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan — JEB</title>
    {{-- 🟢 KODE FAVICON DINAMIS (DB & ASSET) --}}
    @php
        $globalProfil = \App\Models\ProfilSanggar::with('logo')->first();
        $faviconUrl = ($globalProfil && $globalProfil->logo && $globalProfil->logo->path) 
            ? asset('storage/' . $globalProfil->logo->path) 
            : asset('img/images-landing-page/galeri/logo-jeb.jpg');
    @endphp
    
<link rel="icon" type="image/jpeg" href="{{ $faviconUrl }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: rgba(0,0,0,0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        @keyframes popupIn {
            from { opacity: 0; transform: scale(0.92) translateY(16px); }
            to   { opacity: 1; transform: scale(1)    translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Pop Up: Halaman Tidak Ditemukan (404) -->
    <div style="
        background: #F0EBFF;
        border-radius: 16px;
        padding: 36px 28px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        width: 100%;
        max-width: 420px;
        text-align: center;
        animation: popupIn 0.25s ease;
    ">
        <!-- Icon dengan spark -->
        <div style="position: relative; display: inline-block; margin-bottom: 20px;">
            <div style="
                width: 80px; height: 80px;
                border-radius: 50%;
                background: #DDD6FE;
                display: flex; align-items: center; justify-content: center;
                font-size: 36px;
            ">📄</div>
            <span style="position:absolute; top:-4px;  left:-14px;  font-size:16px; color:#A78BFA; opacity:.6; font-weight:300;">+</span>
            <span style="position:absolute; top:-4px;  right:-14px; font-size:16px; color:#A78BFA; opacity:.6; font-weight:300;">+</span>
            <span style="position:absolute; bottom:-4px; left:-14px;  font-size:16px; color:#A78BFA; opacity:.6; font-weight:300;">+</span>
            <span style="position:absolute; bottom:-4px; right:-14px; font-size:16px; color:#A78BFA; opacity:.6; font-weight:300;">+</span>
        </div>

        <!-- Label -->
        <p style="
            font-size: 11px; font-weight: 800;
            letter-spacing: .14em; text-transform: uppercase;
            color: #475569; margin-bottom: 8px;
        ">Halaman Tidak Ditemukan (404)</p>

        <!-- Judul -->
        <h2 style="
            font-size: 18px; font-weight: 800;
            color: #6C3AE0; margin-bottom: 10px;
        ">Halaman Tidak Ditemukan</h2>

        <!-- Pesan -->
        <p style="
            font-size: 13.5px; color: #475569;
            line-height: 1.65; margin-bottom: 24px;
        ">Halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>

        <!-- Tombol -->
        @auth
            @if(auth()->user()->peran === 'superadmin')
                <a href="{{ route('superadmin.dashboard') }}" style="
                    display: inline-block;
                    padding: 12px 28px;
                    border: 2px solid #6C3AE0;
                    border-radius: 10px;
                    background: transparent; color: #6C3AE0;
                    font-size: 14.5px; font-weight: 700;
                    font-family: inherit; cursor: pointer; text-decoration: none;
                    transition: background .2s, transform .1s;
                "
                onmouseover="this.style.background='#EDE9FE'"
                onmouseout="this.style.background='transparent'"
                onmousedown="this.style.transform='scale(.97)'"
                onmouseup="this.style.transform='scale(1)'">
                    Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('home') }}" style="
                    display: inline-block;
                    padding: 12px 28px;
                    border: 2px solid #6C3AE0;
                    border-radius: 10px;
                    background: transparent; color: #6C3AE0;
                    font-size: 14.5px; font-weight: 700;
                    font-family: inherit; cursor: pointer; text-decoration: none;
                    transition: background .2s, transform .1s;
                "
                onmouseover="this.style.background='#EDE9FE'"
                onmouseout="this.style.background='transparent'"
                onmousedown="this.style.transform='scale(.97)'"
                onmouseup="this.style.transform='scale(1)'">
                    Kembali ke Beranda
                </a>
            @endif
        @else
            <a href="{{ route('home') }}" style="
                display: inline-block;
                padding: 12px 28px;
                border: 2px solid #6C3AE0;
                border-radius: 10px;
                background: transparent; color: #6C3AE0;
                font-size: 14.5px; font-weight: 700;
                font-family: inherit; cursor: pointer; text-decoration: none;
                transition: background .2s, transform .1s;
            "
            onmouseover="this.style.background='#EDE9FE'"
            onmouseout="this.style.background='transparent'"
            onmousedown="this.style.transform='scale(.97)'"
            onmouseup="this.style.transform='scale(1)'">
                Kembali ke Beranda
            </a>
        @endauth
    </div>
</body>
</html>
