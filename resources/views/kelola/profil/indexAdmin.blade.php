@extends('layouts.admin')

@section('title', 'Kelola Profil Sanggar — JEB')
@section('header_title', 'Kelola Profil Sanggar')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* ==========================================
       1. GEMBOK UTAMA (ANTI-BLOWOUT HORIZONTAL)
       ========================================== */
    html, body {
        max-width: 100vw;
        overflow-x: hidden;
    }

    .main, .content {
        min-width: 0 !important;
        max-width: 100vw !important;
        overflow-x: hidden !important;
    }

    .prof-wrapper {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
        font-family: 'Poppins', sans-serif;
        color: #333;
    }

    /* --- KARTU UTAMA STYLING (SERASI MODUL LAIN) --- */
    .prof-card-card {
        background: #fff;
        border-radius: 12px;
        padding: 25px; 
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        margin-bottom: 28px;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }
    .prof-card-card h3 {
        margin: 0 0 20px 0;
        font-size: 1.1rem;
        color: #333;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    .btn-edit-profil {
        background: #e2c0c0;
        color: #5a1a1a;
        border: none;
        border-radius: 12px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-edit-profil:hover {
        background: #d4aeae;
    }

    /* --- SLIDESHOW & GRID HEADER SANGGAR --- */
    .header-preview-display {
        width: 100%;
        height: 280px;
        background: #f4ecec;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        border: 1px solid #e8dede;
        margin-bottom: 20px;
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
    .header-grid-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 15px;
    }
    .header-grid-item {
        background: #fffbfb;
        border: 1px solid #e8dede;
        padding: 8px;
        border-radius: 8px;
        text-align: center;
    }
    .header-grid-item img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #f0f0f0;
    }
    .header-grid-item span {
        font-size: 0.75rem;
        color: #777;
        margin-top: 6px;
        display: block;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }

    /* --- DETAIL INFORMASI GRID --- */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .info-item label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #b07d7d;
        letter-spacing: 0.5px;
    }
    .info-item .value {
        font-size: 0.85rem;
        line-height: 1.6;
        color: #333;
        background: #fffbfb;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #e8dede;
        word-wrap: break-word;
    }
    .full-width {
        grid-column: span 2;
    }
    .photo-row {
        display: flex;
        gap: 20px;
        grid-column: span 2;
        margin-top: 5px;
    }
    .photo-box {
        flex: 1;
        background: #fffbfb;
        border-radius: 12px;
        padding: 15px;
        border: 1px solid #e8dede;
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
    }
    .photo-box img {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e8dede;
        background: #f3f4f6;
        flex-shrink: 0;
    }
    .photo-box .photo-text hef {
        margin: 0;
        font-size: 0.85rem;
        font-weight: 600;
        color: #333;
    }

    /* --- TABEL STRUKTUR DESIGN (ANTI BOCOR) --- */
    .prof-table-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px; 
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        margin-bottom: 28px;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow: hidden; /* Kunci luapan dari tabel */
    }
    .prof-table-card .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .prof-table-card h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #333;
    }
    .btn-tambah-personel {
        background: #e2c0c0;
        color: #5a1a1a;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        gap: 6px;
        font-weight: 700;
        font-size: 0.85rem;
        text-decoration: none;
        border: 1px solid #e8dede;
        transition: 0.2s;
    }
    .btn-tambah-personel:hover {
        background: #d4aeae;
    }
    
    /* WADAH SCROLL MENDATAR KHUSUS TABEL */
    .table-responsive-wrapper {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 10px;
        padding-bottom: 15px; /* Ruang lega agar scrollbar tidak numpuk */
    }

    .prof-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px; /* Batas minimum lebar tabel */
    }
    .prof-table thead {
        background-color: #F5EBEB; 
    }
    .prof-table th {
        text-align: left;
        padding: 14px 25px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase; 
        white-space: nowrap;
    }
    .prof-table td {
        padding: 12px 25px;
        border-bottom: 1px solid #E0E0E0; 
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .prof-foto-frame {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e8dede;
        background: #f3f4f6;
        position: relative;
        flex-shrink: 0;
    }
    .prof-foto-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    .prof-foto-frame img.active {
        opacity: 1;
    }
    
    .badge-aktif {
        background: #dcfce7;
        color: #16a34a;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-nonaktif {
        background: #fee2e2;
        color: #ef4444;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .prof-actions {
        white-space: nowrap;
    }
    .prof-actions a, .prof-actions button {
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 5px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }
    .btn-edit-prof { background: #eff6ff; color: #3b82f6; }
    .btn-hapus-prof { background: #fef2f2; color: #ef4444; }

    .prof-pagination {
        margin-top: 25px;
        display: flex;
        justify-content: flex-end;
    }

    /* ==========================================
       MEDIA QUERIES UNTUK HP (MOBILE)
       ========================================== */
    @media (max-width: 768px) {
        .prof-card-card {
            padding: 15px !important;
        }
        .header-preview-display {
            height: 200px;
        }
        .info-grid {
            grid-template-columns: 1fr !important;
        }
        .full-width {
            grid-column: span 1 !important;
        }
        .photo-row {
            flex-direction: column !important;
            grid-column: span 1 !important;
        }
        .prof-table-card {
            padding: 15px !important; 
        }
        .btn-edit-profil, .btn-tambah-personel {
            width: 100%;
            justify-content: center;
        }
        .header-grid-list {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 480px) {
        .header-grid-list {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="prof-wrapper">

    {{-- CARD BARU: MANAGED SLIDESHOW & GRID IMAGES HEADER --}}
    <div class="prof-card-card">
        <h3>
            <span><i class="fas fa-images" style="color:#5B1A1A; margin-right:8px;"></i> Header</span>
            <a href="{{ route('superadmin.edit-profil') }}" class="btn-edit-profil">
                <i class="fas fa-images"></i> Pengaturan Header
            </a>
        </h3>

        {{-- Tampilan Frame Karosel Atas --}}
        <div class="header-preview-display slideshow-container">
            @if($profil->headers && $profil->headers->isNotEmpty())
                @foreach($profil->headers as $index => $header)
                    <img src="{{ asset('storage/' . $header->path) }}" alt="Header Sanggar" class="{{ $index === 0 ? 'active' : '' }}">
                @endforeach
            @else
                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; width:100%; height:100%; color:#9ca3af; gap:10px;">
                    <i class="fas fa-photo-video" style="font-size:2.5rem;"></i>
                    <span style="font-size:0.9rem; text-align:center; padding: 0 10px;">Belum ada gambar banner slideshow yang diunggah.</span>
                </div>
            @endif
        </div>

        {{-- Daftar Koleksi Baris Bawah --}}
        <div class="header-grid-list">
            @forelse($profil->headers as $key => $header)
                <div class="header-grid-item">
                    <img src="{{ asset('storage/' . $header->path) }}" alt="Thumb">
                    <span>Header {{ $key + 1 }}</span>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align:center; padding:15px; font-size:0.85rem; color:#888; background:#fffbfb; border:1px dashed #e8dede; border-radius:8px;">
                    Daftar header kosong.
                </div>
            @endforelse
        </div>
    </div>

    {{-- KARTU DATA INFORMASI UMUM --}}
    <div class="prof-card-card">
        <h3>
            <span>Informasi Umum Sanggar</span>
            <a href="{{ route('superadmin.edit-profil') }}" class="btn-edit-profil">
                <i class="fas fa-edit"></i> Edit Profil
            </a>
        </h3>
        
        <div class="info-grid">
            <div class="info-item full-width">
                <label>Deskripsi Sanggar</label>
                <div class="value">{{ $profil->deskripsi ?? 'Belum diisi' }}</div>
            </div>
            <div class="info-item">
                <label>Visi</label>
                <div class="value">{{ $profil->visi ?? 'Belum diisi' }}</div>
            </div>
            <div class="info-item">
                <label>Misi</label>
                <div class="value">{{ $profil->misi ?? 'Belum diisi' }}</div>
            </div>
            <div class="info-item full-width">
                <label>Sejarah Singkat</label>
                <div class="value">{{ $profil->sejarah ?? 'Belum diisi' }}</div>
            </div>
            <div class="info-item full-width">
                <label>Sambutan Pembina</label>
                <div class="value">{{ $profil->sambutan_pembina ?? 'Belum diisi' }}</div>
            </div>
            
            <div class="photo-row">
                <div class="photo-box">
                    @if($profil && $profil->logo && $profil->logo->path)
                        <img src="{{ asset('storage/' . $profil->logo->path) }}" alt="Logo Sanggar">
                    @else
                        <img src="{{ asset('img/default-logo.png') }}" alt="Logo Default">
                    @endif
                    <div class="photo-text">
                        <hef>Logo Sanggar</hef>
                    </div>
                </div>
                <div class="photo-box">
                    @if($profil && $profil->fotoPembina && $profil->fotoPembina->path)
                        <img src="{{ asset('storage/' . $profil->fotoPembina->path) }}" alt="Foto Pembina">
                    @else
                        <img src="{{ asset('img/default-avatar.png') }}" alt="Foto default" style="opacity:0.5">
                    @endif
                    <div class="photo-text">
                        <hef>Foto Pembina</hef>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KARTU TABEL DAFTAR PENGURUS --}}
    <div class="prof-table-card">
        <div class="table-header">
            <h3>Daftar Pengurus</h3>
            <a href="{{ route('superadmin.tambah-pengurus') }}" class="btn-tambah-personel">
                <span>+</span> Tambah Pengurus
            </a>
        </div>
        
        {{-- BUNGKUSAN TABEL SCROLL HORIZONTAL (ANTI BOCOR) --}}
        <div class="table-responsive-wrapper">
            <table class="prof-table">
                <thead>
                    <tr>
                        <th>FOTO</th>
                        <th>NAMA LENGKAP</th>
                        <th>JABATAN</th>
                        <th>INSTAGRAM</th>
                        <th>NO. HANDPHONE</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengurus as $item)
                    <tr>
                        <td>
                            <div class="prof-foto-frame slideshow-container">
                                @if($item->galeri && $item->galeri->isNotEmpty())
                                    @foreach($item->galeri as $index => $foto)
                                        <img src="{{ asset('storage/' . $foto->path) }}" alt="Foto Pengurus" class="{{ $index === 0 ? 'active' : '' }}">
                                    @endforeach
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=5B1A1A&color=fff" alt="Avatar" class="active">
                                @endif
                            </div>
                        </td>
                        <td style="font-weight:600">{{ $item->nama }}</td>
                        <td><strong style="color: #555;">{{ $item->jabatan }}</strong></td>
                        <td>{{ $item->instagram ?? '-' }}</td>
                        <td>{{ $item->no_handphone }}</td>
                        <td>
                            <span class="{{ $item->status == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                                {{ $item->status == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="prof-actions">
                            <a href="{{ route('superadmin.edit-pengurus', $item->id) }}" class="btn-edit-prof">Edit</a>
                            <button class="btn-hapus-prof" onclick="confirmDelete('pengurus', {{ $item->id }})">Hapus</button>
                            <form id="delete-pengurus-{{ $item->id }}" action="{{ route('superadmin.hapus-pengurus', $item->id) }}" method="POST" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;">Belum ada data pengurus sanggar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="prof-pagination">{{ $pengurus->links() }}</div>
    </div>

    {{-- KARTU TABEL DAFTAR PELATIH --}}
    <div class="prof-table-card">
        <div class="table-header">
            <h3>Daftar Pelatih</h3>
            <a href="{{ route('superadmin.tambah-pelatih') }}" class="btn-tambah-personel">
                <span>+</span> Tambah Pelatih
            </a>
        </div>
        
        {{-- BUNGKUSAN TABEL SCROLL HORIZONTAL (ANTI BOCOR) --}}
        <div class="table-responsive-wrapper">
            <table class="prof-table">
                <thead>
                    <tr>
                        <th>FOTO</th>
                        <th>NAMA LENGKAP</th>
                        <th>JABATAN</th>
                        <th>INSTAGRAM</th>
                        <th>NO. HANDPHONE</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelatih as $item)
                    <tr>
                        <td>
                            <div class="prof-foto-frame slideshow-container">
                                @if($item->galeri && $item->galeri->isNotEmpty())
                                    @foreach($item->galeri as $index => $foto)
                                        <img src="{{ asset('storage/' . $foto->path) }}" alt="Foto Pelatih" class="{{ $index === 0 ? 'active' : '' }}">
                                    @endforeach
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=16a34a&color=fff" alt="Avatar" class="active">
                                @endif
                            </div>
                        </td>
                        <td style="font-weight:600">{{ $item->nama }}</td>
                        <td><strong style="color: #555;">{{ $item->jabatan }}</strong></td>
                        <td>{{ $item->instagram ?? '-' }}</td>
                        <td>{{ $item->no_handphone }}</td>
                        <td>
                            <span class="{{ $item->status == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                                {{ $item->status == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="prof-actions">
                            <a href="{{ route('superadmin.edit-pelatih', $item->id) }}" class="btn-edit-prof">Edit</a>
                            <button class="btn-hapus-prof" onclick="confirmDelete('pelatih', {{ $item->id }})">Hapus</button>
                            <form id="delete-pelatih-{{ $item->id }}" action="{{ route('superadmin.hapus-pelatih', $item->id) }}" method="POST" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;">Belum ada data pelatih sanggar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="prof-pagination">{{ $pelatih->links() }}</div>
    </div>
</div>

<script>
    function confirmDelete(jenis, id) {
        if(confirm('Yakin ingin menghapus data ' + jenis + ' ini?')) {
            document.getElementById('delete-' + jenis + '-' + id).submit();
        }
    }

    // JAVASCRIPT ENGINE AUTOMATIC SLIDESHOW (5 DETIK)
    document.addEventListener("DOMContentLoaded", function() {
        const containers = document.querySelectorAll('.slideshow-container');

        containers.forEach(container => {
            const images = container.querySelectorAll('img');
            if (images.length > 1) {
                let currentIndex = 0;

                setInterval(() => {
                    images[currentIndex].classList.remove('active');
                    currentIndex = (currentIndex + 1) % images.length;
                    images[currentIndex].classList.add('active');
                }, 5000);
            }
        });
    });
</script>
@endsection