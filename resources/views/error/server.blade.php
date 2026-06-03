<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error — JEB</title>
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
    <!-- Pop Up: Server Error -->
    <div style="
        background: #FFF5F5;
        border-radius: 16px;
        padding: 36px 28px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        width: 100%;
        max-width: 420px;
        text-align: center;
        animation: popupIn 0.25s ease;
    ">
        <!-- Icon -->
        <div style="
            width: 72px; height: 72px;
            border-radius: 50%;
            background: #FECACA;
            color: #E53935;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px; font-weight: 700;
        ">✕</div>

        <!-- Label -->
        <p style="
            font-size: 11px; font-weight: 800;
            letter-spacing: .14em; text-transform: uppercase;
            color: #475569; margin-bottom: 8px;
        ">Server Error</p>

        <!-- Judul -->
        <h2 style="
            font-size: 18px; font-weight: 800;
            color: #E53935; margin-bottom: 10px;
        ">Terjadi Kesalahan Server</h2>

        <!-- Pesan -->
        <p style="
            font-size: 13.5px; color: #475569;
            line-height: 1.65; margin-bottom: 24px;
        ">Format data tidak sesuai atau terjadi kendala saat memproses permintaan. Periksa kembali data yang Anda masukkan.</p>

        <!-- Tombol Coba Lagi (kembali ke halaman sebelumnya) -->
        <button onclick="history.back()" style="
            display: inline-block;
            padding: 12px 32px;
            border: none; border-radius: 10px;
            background: #E53935; color: #fff;
            font-size: 14.5px; font-weight: 700;
            font-family: inherit; cursor: pointer;
            transition: filter .2s, transform .1s;
            margin-bottom: 12px;
            width: 100%;
        "
        onmouseover="this.style.filter='brightness(1.08)'"
        onmouseout="this.style.filter='brightness(1)'"
        onmousedown="this.style.transform='scale(.97)'"
        onmouseup="this.style.transform='scale(1)'">
            Coba Lagi
        </button>

        @auth
            @if(auth()->user()->peran === 'superadmin')
                <a href="{{ route('superadmin.dashboard') }}" style="
                    display: block;
                    color: #E53935; font-size: 13.5px; font-weight: 600;
                    text-decoration: none;
                "
                onmouseover="this.style.textDecoration='underline'"
                onmouseout="this.style.textDecoration='none'">
                    Kembali ke Dashboard
                </a>
            @endif
        @endauth
    </div>
</body>
</html>
