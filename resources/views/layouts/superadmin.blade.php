<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>@yield('title', 'Super Admin JEB')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --merah: #8B0000;
            --merah-gelap: #5a0000;
            --merah-muda: #b01010;
            --emas: #c9991a;
            --emas-muda: #f5c518;
            --putih: #fff;
            --abu-bg: #f5f0f0;
            --abu-border: #e8dede;
            --teks: #1a0000;
            --teks-abu: #8a7070;
            --sidebar-w: 240px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Lato', sans-serif;
            background: var(--abu-bg);
            color: var(--teks);
            display: flex;
            min-height: 100vh;
        }

        @stack('styles')

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--merah-gelap);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 1050; /* Z-index utama sidebar */
            transition: transform 0.3s ease;
        }

        .sidebar-logo {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img-sidebar {
            width: 42px;           
            height: 42px;          
            border-radius: 10px;   
            object-fit: cover;     
            flex-shrink: 0;        
            border: 1px solid rgba(255,255,255,0.1);
        }

        .logo-text h3 {
            font-family: 'Playfair Display', serif;
            color: #fff;
            font-size: 0.95rem;
            line-height: 1.2;
        }

        .logo-text span {
            color: var(--emas-muda);
            font-size: 0.7rem;
            letter-spacing: 1.5px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .sidebar-role {
            margin: 16px 16px 8px;
            background: rgba(245,197,24,0.12);
            border: 1px solid rgba(245,197,24,0.25);
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .role-dot {
            width: 8px; height: 8px;
            background: var(--emas-muda);
            border-radius: 50%;
            flex-shrink: 0;
        }

        .sidebar-role span {
            color: var(--emas-muda);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 12px 0;
            flex: 1;
        }

        .nav-label {
            color: rgba(255,255,255,0.3);
            font-size: 0.65rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 12px 20px 6px;
            font-weight: 700;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.65);
            font-size: 0.85rem;
            font-weight: 400;
            cursor: pointer;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            text-decoration: none;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }

        .nav-item.active {
            background: rgba(245,197,24,0.1);
            color: var(--emas-muda);
            border-left-color: var(--emas-muda);
            font-weight: 700;
        }

        .nav-item svg { flex-shrink: 0; opacity: 0.8; }
        .nav-item.active svg { opacity: 1; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 36px; height: 36px;
            background: var(--emas);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--merah-gelap);
            flex-shrink: 0;
        }

        .user-info p {
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .user-info span {
            color: rgba(255,255,255,0.45);
            font-size: 0.7rem;
        }

        .logout-btn {
            margin-left: auto;
            background: none;
            border: none;
            color: rgba(255,255,255,0.4);
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s;
        }
        .logout-btn:hover { color: #fff; }

        /* ========== MAIN & TOPBAR ========== */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--abu-border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--teks);
        }

        .topbar-left p {
            color: var(--teks-abu);
            font-size: 0.78rem;
            margin-top: 1px;
        }

        .content {
            padding: 28px 32px;
            flex: 1;
        }

        /* ========== OVERLAY & TOGGLE (RESPONSIF) ========== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1045; /* Di atas topbar, di bawah sidebar */
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active {
            display: block;
        }

        .menu-toggle {
            display: none;
            position: fixed;
            top: 16px; left: 16px;
            z-index: 1040; /* Di bawah sidebar agar tertutup saat menu terbuka */
            background: var(--merah);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        /* ========== MEDIA QUERIES ========== */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main {
                margin-left: 0;
            }
            .menu-toggle {
                display: block;
            }
            .topbar {
                padding-left: 75px; /* Menghindar dari tombol toggle */
            }
        }

        @media (max-width: 768px) {
            .content {
                padding: 16px;
            }
            .topbar {
                padding: 16px 16px 16px 70px;
            }
            .topbar-left h1 {
                font-size: 1.15rem;
                margin-bottom: 2px;
            }
            .topbar-left p {
                font-size: 0.75rem;
            }
            .menu-toggle {
                top: 14px;
                left: 14px;
                padding: 6px 10px;
            }
        }

        @media (max-width: 640px) {
            html {
                font-size: 14px;
            }
            .stat-info h2 {
                font-size: 1.5rem;
            }
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .user-card {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>
    <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>

    @include('partials.sidebarSuperadmin')

    <main class="main">
        @include('partials.topbar')

        <div class="content">
            @yield('content')
        </div>
    </main>

    {{-- Popup Logout --}}
    @include('popup.logout')

    <script>
        // Sambungkan fungsi lama ke popup baru
        function openLogoutModal() { showPopupLogout(); }
        function closeLogoutModal() { hidePopupLogout(); }

        // Script Sidebar Mobile
        document.addEventListener("DOMContentLoaded", function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (menuToggle && sidebar && overlay) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.add('open');
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
