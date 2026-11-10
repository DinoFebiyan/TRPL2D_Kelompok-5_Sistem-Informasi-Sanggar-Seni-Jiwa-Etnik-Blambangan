<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JEB Admin')</title>
    {{-- 🟢 KODE FAVICON DINAMIS (DB & ASSET) --}}
    @php
        $globalProfil = \App\Models\ProfilSanggar::with('logo')->first();
        $faviconUrl = ($globalProfil && $globalProfil->logo && $globalProfil->logo->path) 
            ? asset('storage/' . $globalProfil->logo->path) 
            : asset('img/images-landing-page/galeri/logo-jeb.jpg');
    @endphp
    
<link rel="icon" type="image/jpeg" href="{{ $faviconUrl }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #e5e5e5;
        }
    </style>

    @yield('styles')
    @stack('styles')
</head>

<body>
    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>