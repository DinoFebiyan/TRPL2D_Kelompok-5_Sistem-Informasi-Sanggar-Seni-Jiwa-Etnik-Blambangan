<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — JEB</title>
{{-- 🟢 KODE FAVICON DINAMIS (DB & ASSET) --}}
    @php
        $globalProfil = \App\Models\ProfilSanggar::with('logo')->first();
        $faviconUrl = ($globalProfil && $globalProfil->logo && $globalProfil->logo->path) 
            ? asset('storage/' . $globalProfil->logo->path) 
            : asset('img/images-landing-page/galeri/logo-jeb.jpg');
    @endphp
    
<link rel="icon" type="image/jpeg" href="{{ $faviconUrl }}">
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

       
        html, body {
            max-width: 100vw;
            overflow-x: hidden;
        }

        body {
            font-family: 'Lato', sans-serif;
            background: var(--abu-bg);
            color: var(--teks);
            display: flex;
            min-height: 100vh;
        }

        @stack('styles')
        
        .sidebar {
            width: var(--sidebar-w);
            background: var(--merah-gelap);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 100;
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
            overflow-y: auto; 
        }
        
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

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

        .nav-badge {
            margin-left: auto;
            background: var(--merah-muda);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 10px;
        }

       
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin-top: auto; 
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;             
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

        
        .user-info {
            min-width: 0;             
            overflow: hidden;
            flex: 1;
        }

        .user-info p,
        .user-info span {
            white-space: nowrap;       
            overflow: hidden;
            text-overflow: ellipsis;  
            display: block;
            margin: 0;
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
            flex-shrink: 0;            
            display: inline-flex;     
            align-items: center;
            justify-content: center;
            font-size: 1rem;          
            line-height: 1;
            min-width: 28px;           
            min-height: 28px;
        }
        .logout-btn:hover { color: #fff; }

        
        .logout-btn i,
        .logout-btn svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            display: block;
        }

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

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .notif-btn {
            width: 38px; height: 38px;
            background: var(--abu-bg);
            border: 1px solid var(--abu-border);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            position: relative;
            transition: background 0.2s;
        }
        .notif-btn:hover { background: #eedede; }

        .notif-dot {
            position: absolute;
            top: 7px; right: 7px;
            width: 7px; height: 7px;
            background: var(--merah);
            border-radius: 50%;
            border: 1.5px solid #fff;
        }

        .topbar-avatar {
            width: 38px; height: 38px;
            background: var(--merah);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .content {
            padding: 28px 32px;
            flex: 1;
        }

        
        .settings-layout { display:flex; gap:20px; }
        .settings-sidebar { width:220px; flex-shrink:0; }
        .settings-main { flex:1; }
        .tab-menu { background:#fff; border-radius:14px; border:1px solid var(--abu-border); overflow:hidden; }
        .tab-item { display:flex; align-items:center; gap:12px; padding:13px 18px; cursor:pointer; border-left:3px solid transparent; transition:all 0.2s; color:var(--teks-abu); font-size:0.85rem; font-weight:600; }
        .tab-item:hover { background:#fdf7f7; color:var(--teks); }
        .tab-item.active { background:#fff5f5; color:var(--merah); border-left-color:var(--merah); font-weight:700; }
        .tab-item svg { flex-shrink:0; }
        .tab-divider { height:1px; background:var(--abu-border); }
        .panel { display:none; }
        .panel.active { display:block; }
        .card { background:#fff; border-radius:14px; border:1px solid var(--abu-border); overflow:hidden; margin-bottom:16px; }
        .card-header { padding:16px 22px; border-bottom:1px solid var(--abu-border); display:flex; align-items:center; gap:12px; }
        .card-header-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .chi-red   { background:rgba(139,0,0,0.08); }
        .chi-gold  { background:rgba(201,153,26,0.1); }
        .chi-green { background:rgba(22,163,74,0.08); }
        .chi-blue  { background:rgba(37,99,235,0.08); }
        .chi-red2  { background:rgba(220,38,38,0.08); }
        .card-header-text h3 { font-size:0.92rem; font-weight:700; color:var(--teks); }
        .card-header-text p { font-size:0.75rem; color:var(--teks-abu); margin-top:1px; }
        .card-body { padding:22px; }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .form-group { display:flex; flex-direction:column; gap:6px; }
        .form-group.full { grid-column:1/-1; }
        .form-group label { font-size:0.78rem; font-weight:700; color:var(--teks); }
        .form-group input, .form-group select, .form-group textarea { padding:9px 12px; border:1px solid var(--abu-border); border-radius:8px; font-size:0.82rem; font-family:'Lato',sans-serif; color:var(--teks); outline:none; transition:border 0.2s; }
        .form-group input:focus, .form-group select:focus { border-color:var(--merah); }
        .form-hint { font-size:0.7rem; color:var(--teks-abu); margin-top:2px; }
        .avatar-upload { display:flex; align-items:center; gap:20px; margin-bottom:22px; }
        .avatar-preview { width:72px; height:72px; background:var(--merah); border-radius:50%; display:flex; align-items:center; justify-content:center; font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700; color:var(--emas-muda); flex-shrink:0; position:relative; }
        .avatar-preview .change-overlay { position:absolute; inset:0; background:rgba(0,0,0,0.45); border-radius:50%; display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity 0.2s; cursor:pointer; }
        .avatar-preview:hover .change-overlay { opacity:1; }
        .avatar-preview .change-overlay span { color:#fff; font-size:0.65rem; font-weight:700; letter-spacing:0.5px; }
        .avatar-upload-info h4 { font-size:0.88rem; font-weight:700; margin-bottom:4px; }
        .avatar-upload-info p { font-size:0.75rem; color:var(--teks-abu); margin-bottom:10px; }
        .btn-red { background:var(--merah); color:#fff; border:none; padding:9px 22px; border-radius:8px; font-size:0.82rem; font-weight:700; cursor:pointer; font-family:'Lato',sans-serif; transition:background 0.2s; display:inline-flex; align-items:center; gap:7px; }
        .btn-red:hover { background:var(--merah-gelap); }
        .btn-ghost { background:none; border:1px solid var(--abu-border); color:var(--teks-abu); padding:9px 18px; border-radius:8px; font-size:0.82rem; font-weight:700; cursor:pointer; font-family:'Lato',sans-serif; transition:all 0.2s; }
        .btn-ghost:hover { border-color:var(--merah); color:var(--merah); }
        .btn-danger { background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; padding:9px 18px; border-radius:8px; font-size:0.82rem; font-weight:700; cursor:pointer; font-family:'Lato',sans-serif; transition:all 0.2s; display:inline-flex; align-items:center; gap:7px; }
        .btn-danger:hover { background:#dc2626; color:#fff; border-color:#dc2626; }
        .btn-success { background:#dcfce7; color:#16a34a; border:1px solid #86efac; padding:9px 18px; border-radius:8px; font-size:0.82rem; font-weight:700; cursor:pointer; font-family:'Lato',sans-serif; transition:all 0.2s; display:inline-flex; align-items:center; gap:7px; }
        .btn-success:hover { background:#16a34a; color:#fff; border-color:#16a34a; }
        .form-actions { display:flex; gap:10px; margin-top:20px; justify-content:flex-end; }
        .password-wrap { position:relative; }
        .password-wrap input { width:100%; padding-right:40px; }
        .toggle-pw { position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--teks-abu); }
        .strength-bar { display:flex; gap:4px; margin-top:6px; }
        .strength-seg { height:4px; flex:1; border-radius:2px; background:#e8dede; transition:background 0.3s; }
        .strength-seg.weak   { background:#dc2626; }
        .strength-seg.medium { background:#d97706; }
        .strength-seg.strong { background:#16a34a; }
        .strength-label { font-size:0.7rem; color:var(--teks-abu); margin-top:4px; }
        .backup-list { display:flex; flex-direction:column; gap:10px; }
        .backup-item { display:flex; align-items:center; justify-content:space-between; padding:14px 16px; background:#fdf8f8; border-radius:10px; border:1px solid var(--abu-border); }
        .backup-item-left { display:flex; align-items:center; gap:12px; }
        .backup-icon { width:38px; height:38px; background:rgba(139,0,0,0.08); border-radius:9px; display:flex; align-items:center; justify-content:center; }
        .backup-info h4 { font-size:0.83rem; font-weight:700; }
        .backup-info span { font-size:0.72rem; color:var(--teks-abu); }
        .backup-item-right { display:flex; gap:8px; align-items:center; }
        .backup-size { font-size:0.75rem; color:var(--teks-abu); font-weight:700; }
        .alert { border-radius:10px; padding:14px 16px; font-size:0.82rem; margin-bottom:16px; display:flex; align-items:flex-start; gap:10px; }
        .alert-warn { background:#fef3c7; border:1px solid #fde68a; color:#92400e; }
        .alert-danger { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; }
        .alert-info { background:#dbeafe; border:1px solid #93c5fd; color:#1e40af; }
        .alert-success { background:#dcfce7; border:1px solid #86efac; color:#166534; }
        .alert svg { flex-shrink:0; margin-top:1px; }
        .section-divider { height:1px; background:var(--abu-border); margin:20px 0; }
        .last-login { background:#f7f2f2; border-radius:10px; padding:14px 16px; display:flex; align-items:center; gap:12px; margin-bottom:16px; }
        .last-login svg { flex-shrink:0; color:var(--teks-abu); }
        .last-login p { font-size:0.82rem; color:var(--teks); }
        .last-login span { font-size:0.72rem; color:var(--teks-abu); display:block; margin-top:2px; }
        .restore-zone { border:2px dashed var(--abu-border); border-radius:12px; padding:32px; text-align:center; cursor:pointer; transition:all 0.2s; }
        .restore-zone:hover { border-color:var(--merah); background:#fdf7f7; }
        .restore-zone svg { margin:0 auto 10px; display:block; color:#ccc; }
        .restore-zone p { font-size:0.85rem; font-weight:700; color:var(--teks-abu); margin-bottom:4px; }
        .restore-zone span { font-size:0.75rem; color:#ccc; }
        .toggle-row { display:flex; align-items:center; justify-content:space-between; padding:12px 0; border-bottom:1px solid #f5eeee; }
        .toggle-row:last-child { border-bottom:none; }
        .toggle-info h4 { font-size:0.85rem; font-weight:700; }
        .toggle-info p { font-size:0.75rem; color:var(--teks-abu); margin-top:2px; }
        .toggle-switch { position:relative; width:44px; height:24px; }
        .toggle-switch input { opacity:0; width:0; height:0; }
        .toggle-slider { position:absolute; inset:0; background:#ddd; border-radius:24px; cursor:pointer; transition:0.3s; }
        .toggle-slider:before { content:''; position:absolute; width:18px; height:18px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:0.3s; }
        .toggle-switch input:checked + .toggle-slider { background:var(--merah); }
        .toggle-switch input:checked + .toggle-slider:before { transform:translateX(20px); }

        /* MODAL STYLES */
        .modal-overlay {
            position: fixed; 
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); 
            display: none; 
            align-items: center; 
            justify-content: center; 
            z-index: 9999; 
            backdrop-filter: blur(3px);
        }

        /* Navigasi Slideshow */
        .slideshow-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .slideshow-nav-btn:hover { background: rgba(0, 0, 0, 0.75); }
        .slideshow-prev { left: 10px; }
        .slideshow-next { right: 10px; }
        .modal-box {
            background: #fff; width: 95%; max-width: 500px; border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3); overflow: hidden;
            animation: modalFadeIn 0.3s ease-out;
        }
        @keyframes modalFadeIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            background: var(--merah); padding: 16px 20px; display: flex;
            justify-content: space-between; align-items: center; color: #fff;
        }
        .modal-header h3 { font-family: 'Playfair Display'; font-size: 1.2rem; margin: 0; }
        .close-btn { background: none; border: none; color: #fff; font-size: 1.8rem; cursor: pointer; line-height: 1; }
        .modal-body { padding: 25px; }
        .modal-footer {
            padding: 15px 25px; background: #fdf5f5; display: flex; justify-content: flex-end; gap: 12px;
        }
        .btn-batal { background: #fff; border: 1px solid #ddd; padding: 10px 20px; border-radius: 10px; cursor: pointer; font-weight: 600; }
        .btn-simpan { background: var(--merah); color: #fff; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; font-weight: 700; }
        
       
        .sidebar-overlay-mobile {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 95;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay-mobile.active {
            display: block;
        }

       
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 14px; 
            left: 16px;
            background: var(--merah);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 1.2rem;
            box-shadow: 0 2px 8px rgba(139,0,0,0.3);
            z-index: 101; 
            cursor: pointer;
            transition: 0.3s;
        }
        .mobile-menu-btn:hover {
            background: var(--merah-gelap);
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                box-shadow: 5px 0 15px rgba(0,0,0,0.1);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main {
                margin-left: 0;
                width: 100%;
            }
            .mobile-menu-btn {
                display: block; 
            }
            .topbar {
                padding: 15px 20px 15px 75px; 
            }
            .content {
                padding: 20px 15px;
            }
        }
        
        @media (max-width: 576px) {
            .topbar-left h1 { font-size: 1.15rem; }
            .topbar-left p { font-size: 0.7rem; }
            .topbar {
                padding: 15px 15px 15px 65px; 
            }
        }
    </style>
</head>
<body>

    
    <div class="sidebar-overlay-mobile" id="sidebarOverlayMobile"></div>
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>

    @include('partials.sidebarAdmin')

    <main class="main">
        @include('partials.topbar')

        <div class="content">
            @yield('content')
        </div>
    </main>

    
    @include('popup.logout')

    {{-- Popup Batal Form (untuk halaman tambah/edit) --}}
    @include('popup.batal')

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlayMobile');

            if(menuBtn && sidebar && overlay) {
                menuBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                    
                   
                    if(sidebar.classList.contains('active')) {
                        menuBtn.innerHTML = '<i class="fas fa-times"></i>';
                    } else {
                        menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                    }
                });

               
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                });
            }
        });

        
        function openLogoutModal() { showPopupLogout(); }
        function closeLogoutModal() { hidePopupLogout(); }
        function executeLogout() {
            document.getElementById('logout-form').submit();
        }

        window.onclick = function(event) {
            
        }
    </script>
    <script>
       
        window.addEventListener('load', function () {
            var isFormPage = document.body.classList.contains('page-form');
            setTimeout(function () {
                history.pushState({ jeb: 'blocked' }, '', window.location.href);
            }, 200);
            window.addEventListener('popstate', function () {
                history.pushState({ jeb: 'blocked' }, '', window.location.href);
                if (isFormPage) {
                    var el = document.getElementById('popup-batal');
                    if (el) { el.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
                } else {
                    if (typeof showPopupLogout === 'function') showPopupLogout();
                }
            });
        });
    </script>
    <script>
        function enhanceSlideshows() {
            document.querySelectorAll('.slideshow-container, .header-slideshow-container, .slideshow-container-main, .slideshow-container-mini').forEach(container => {
                if (container.querySelector('.slideshow-nav-btn')) return;
                
                const images = container.querySelectorAll('img');
                if (images.length <= 1) return;

                container.style.position = 'relative';

                const prevBtn = document.createElement('button');
                prevBtn.innerHTML = '‹';
                prevBtn.className = 'slideshow-nav-btn slideshow-prev';
                prevBtn.setAttribute('aria-label', 'Foto sebelumnya');

                const nextBtn = document.createElement('button');
                nextBtn.innerHTML = '›';
                nextBtn.className = 'slideshow-nav-btn slideshow-next';
                nextBtn.setAttribute('aria-label', 'Foto berikutnya');

                container.appendChild(prevBtn);
                container.appendChild(nextBtn);

                const showImage = (dir) => {
                    const images = container.querySelectorAll('img');
                    let curr = Array.from(images).findIndex(img => img.classList.contains('active'));
                    if (curr === -1) curr = 0; 
                    images[curr].classList.remove('active');
                    curr = (curr + dir + images.length) % images.length;
                    images[curr].classList.add('active');
                };

                prevBtn.addEventListener('click', (e) => { e.preventDefault(); showImage(-1); });
                nextBtn.addEventListener('click', (e) => { e.preventDefault(); showImage(1); });
                
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') showImage(-1);
                    if (e.key === 'ArrowRight') showImage(1);
                });
                
                let touchStartX = 0;
                container.addEventListener('touchstart', e => touchStartX = e.touches[0].clientX);
                container.addEventListener('touchend', e => {
                    const diff = e.changedTouches[0].clientX - touchStartX;
                    if (Math.abs(diff) > 50) showImage(diff > 0 ? -1 : 1);
                });
            });
        }
        setInterval(enhanceSlideshows, 1000);
    </script>
    @stack('scripts')
</body>
</html>