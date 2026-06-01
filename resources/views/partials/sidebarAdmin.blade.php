<aside class="sidebar">
    <div class="sidebar-logo">
        {{-- Mengambil data profil pertama langsung dari model untuk menjamin sifat kedinamisan di file layout --}}
        @php
            $profilSanggarGlobal = \App\Models\ProfilSanggar::with('logo')->first();
        @endphp

        @if($profilSanggarGlobal && $profilSanggarGlobal->logo && $profilSanggarGlobal->logo->path)
            <img src="{{ asset('storage/' . $profilSanggarGlobal->logo->path) }}" alt="Logo JEB" class="logo-img-sidebar" style="object-fit: cover;">
        @else
            {{-- Fallback ke file lokal jika admin belum mengunggah logo di sistem --}}
            <img src="{{ asset('img/logo-jeb.jpg') }}" alt="Logo JEB" class="logo-img-sidebar">
        @endif
        
        <div class="logo-text">
            <h3>Jiwa Etnik<br>Blambangan</h3>
            <span>Sanggar Tari</span>
        </div>
    </div>

    <div class="sidebar-role">
        <div class="role-dot"></div>
        <span>Admin</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a class="nav-item {{ request()->is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/>
                <rect x="14" y="3" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>
        
        <div class="nav-label">Konten</div>

        <a class="nav-item {{ request()->is('admin/kelola-event*') ? 'active' : '' }}" href="{{ route('admin.kelola-event') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Kelola Event
        </a>

        <a class="nav-item {{ request()->is('admin/kelola-berita*') ? 'active' : '' }}" href="{{ route('admin.kelola-berita') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Kelola Berita
        </a>

        <a class="nav-item {{ request()->is('admin/kelola-profil*') ? 'active' : '' }}" href="{{ route('admin.kelola-profil') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="16" rx="2"/>
                <line x1="7" y1="8" x2="17" y2="8"/>
                <line x1="7" y1="12" x2="17" y2="12"/>
                <line x1="7" y1="16" x2="11" y2="16"/>
            </svg>
            Kelola Profil
        </a>

        <div class="nav-label">Sistem</div>

        <a class="nav-item {{ request()->is('admin/pengaturan*') ? 'active' : '' }}" href="{{ route('admin.pengaturan') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            Pengaturan
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            {{-- Avatar Dinamis --}}
            <div class="avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: #5B1A1A;">
                @if(Auth::user()->galeri && Auth::user()->galeri->path)
                    <img src="{{ asset('storage/' . Auth::user()->galeri->path) }}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{-- Mengambil 2 huruf pertama dari nama user sebagai fallback inisial --}}
                    <span style="color: #fff; font-weight: 600; font-size: 0.85rem;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'Admin', 0, 2)) }}
                    </span>
                @endif
            </div>

            {{-- Info User Dinamis --}}
            <div class="user-info">
                <p>{{ Auth::user()->name ?? 'Admin' }}</p>
                <span>{{ Auth::user()->email ?? 'admin@jeb.id' }}</span>
            </div>

            {{-- Tombol Logout --}}
            <button class="logout-btn" title="Logout" onclick="openLogoutModal()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </button>
        </div>
    </div>
</aside>

<div id="modalLogout" class="modal-overlay" style="display: none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Konfirmasi Keluar</h3>
            <button class="close-btn" onclick="closeLogoutModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Yakin ingin keluar dari sesi Admin?</p>
        </div>
        <div class="modal-footer">
            <button class="btn-batal" onclick="closeLogoutModal()">Batal</button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-simpan">Ya, Keluar</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openLogoutModal() {
        document.getElementById('modalLogout').style.display = 'flex';
    }
    function closeLogoutModal() {
        document.getElementById('modalLogout').style.display = 'none';
    }
</script>