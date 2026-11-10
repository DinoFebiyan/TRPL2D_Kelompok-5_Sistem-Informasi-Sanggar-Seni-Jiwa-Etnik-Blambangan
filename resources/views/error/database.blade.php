{{-- PERBAIKAN (TC-BRT-008): view BARU, halaman error database yang berdiri sendiri (tanpa layout/query DB agar tidak ikut error). --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Sementara Tidak Tersedia — JEB</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            background: rgba(0,0,0,0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .kotak {
            background: #FFF5F5;
            border-radius: 16px;
            padding: 36px 28px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        .kotak h1 { font-size: 1.2rem; color: #C0392B; margin-bottom: 10px; }
        .kotak p { color: #555; line-height: 1.5; margin-bottom: 20px; }
        .kotak a {
            display: inline-block; padding: 10px 22px; border-radius: 8px;
            background: #C0392B; color: #fff; text-decoration: none; font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="kotak">
        <h1>Terjadi kesalahan sistem, silakan coba lagi nanti</h1>
        <p>Data sementara tidak dapat diakses. Silakan muat ulang halaman beberapa saat lagi.</p>
        <a href="{{ url('/') }}">Kembali ke Beranda</a>
    </div>
</body>
</html>
