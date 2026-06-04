<nav>
  <div class="nav-logo">
    <img src="{{ asset('img/images-landing-page/galeri/logo-jeb.jpg') }}" alt="Logo JEB" class="logo-img-nav" />
    <span>JEB</span>
  </div>
  <div class="nav-links">
    {{-- Beranda --}}
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
    
    {{-- Profile Sanggar --}}
    <a href="{{ route('umum.profil') }}" class="{{ request()->routeIs('umum.profil') ? 'active' : '' }}">Profile Sanggar</a>
    
    {{-- Artikel/Berita --}}
    <a href="{{ route('umum.berita') }}" class="{{ request()->routeIs('umum.berita*') ? 'active' : '' }}">Artikel/Berita</a>
    
    {{-- Kalender Event --}}
    <a href="{{ route('umum.event') }}" class="{{ request()->routeIs('umum.event*') ? 'active' : '' }}">Kalender Event</a>
    
    {{-- Katalog Kostum --}}
    <a href="{{ route('umum.katalog') }}" class="{{ request()->routeIs('umum.katalog*') ? 'active' : '' }}">Katalog Kostum</a>
    
    {{-- Galeri --}}
    <a href="{{ route('umum.galeri') }}" class="{{ request()->routeIs('umum.galeri*') ? 'active' : '' }}">Galeri</a>
    
    {{-- Tombol Login --}}
    <a href="{{ route('login') }}" class="btn-login">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
      </svg>
      Login Admin
    </a>
  </div>
</nav>