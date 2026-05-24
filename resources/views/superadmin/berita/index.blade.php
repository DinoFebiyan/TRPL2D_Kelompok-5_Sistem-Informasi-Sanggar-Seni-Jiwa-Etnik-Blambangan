@extends('layouts.superadmin')

@section('title', 'Kelola Berita — JEB')
@section('header_title', 'Kelola Berita')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* wrapper */
    .news-wrapper {
        width: 100%;
        font-family: 'Poppins', sans-serif;
        color: #333;
    }
    .news-header {
        margin-bottom: 24px;
    }
    .news-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        margin: 0 0 4px 0;
    }
    .news-header p {
        color: #999;
        font-size: 0.85rem;
        margin: 0;
    }
    /* stats grid */
    .news-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 24px;
    }
    .news-stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid #f0f0f0;
    }
    .news-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .news-icon-blue { background: #eff6ff; color: #3b82f6; }
    .news-icon-green { background: #dcfce7; color: #16a34a; }
    .news-icon-red { background: #fef2f2; color: #ef4444; }
    .news-stat-text h4 {
        margin: 0;
        font-size: 0.7rem;
        color: #999;
        text-transform: uppercase;
    }
    .news-stat-text h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }
    .news-btn-tambah {
        background: #e2c0c0;
        color: #5a1a1a;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid #e8dede;
        transition: 0.2s;
    }
    .news-btn-tambah:hover { background: #d4aeae; }
    /* search */
    .news-search-row {
        display: flex;
        gap: 15px;
        margin-bottom: 24px;
    }
    .news-search-box {
        flex: 1;
        position: relative;
    }
    .news-search-box input {
        width: 100%;
        padding: 14px 20px;
        border-radius: 12px;
        border: 1px solid #e8dede;
        outline: none;
        font-size: 0.95rem;
        background: #fff;
    }
    .news-search-box .fa-search {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #eab308;
    }
    .news-btn-filter {
        background: #b91c1c;
        color: #fff;
        border: none;
        padding: 0 35px;
        border-radius: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    /* table */
    .news-table-card {
        background: #fff;
        border-radius: 12px;
        padding: 25px 0; 
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        overflow-x: auto;
    }
    .news-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }
    .news-table thead {
        background-color: #F5EBEB; 
    }
    .news-table-card h3 {
        margin: 0 0 20px 0;
        padding: 0 25px; 
        font-size: 1.1rem;
        color: #333;
    }
    .news-table th {
        text-align: left;
        padding: 14px 25px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: none;
        text-transform: uppercase; 
    }
    .news-table td {
        padding: 15px 25px;
        border-bottom: 1px solid #E0E0E0; 
        vertical-align: middle;
        font-size: 0.85rem;
    }
    .news-foto {
        width: 60px;
        height: 50px;
        background: #f0f0f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: #999;
        overflow: hidden;
        position: relative;
    }
    .news-foto img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        transition: opacity 0.5s ease-in-out; /* Efek transisi halus saat ganti foto */
    }
    .news-foto img.active {
        opacity: 1;
    }
    .badge-tayang {
        background: #dcfce7;
        color: #16a34a;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-tidak-tayang {
        background: #fee2e2;
        color: #ef4444;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .news-actions a, .news-actions button {
        border: none;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 5px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }
    .btn-edit-news { background: #eff6ff; color: #3b82f6; }
    .btn-hapus-news { background: #fef2f2; color: #ef4444; }
    .pagination-links {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
    }
    .adm-btn-tambah {
        background: #e2c0c0;
        color: #5a1a1a;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid #e8dede;
    }
    .adm-btn-tambah:hover {
        background: #d4aeae;
    }
    .adm-icon-dark-red { background-color: #7a2a214a; color: #411f1f; }
    .adm-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
</style>

<div class="news-wrapper">
    <div class="news-stats-grid">
        <div class="news-stat-card">
            <div class="news-icon-box news-icon-blue"><i class="fas fa-newspaper"></i></div>
            <div class="news-stat-text">
                <h4>Total Berita</h4>
                <h2>{{ $totalBerita ?? 0 }}</h2>
            </div>
        </div>
        <div class="news-stat-card">
            <div class="news-icon-box news-icon-green"><i class="fas fa-check-circle"></i></div>
            <div class="news-stat-text">
                <h4>Ditayangkan</h4>
                <h2>{{ $ditayangkan ?? 0 }}</h2>
            </div>
        </div>
        <div class="news-stat-card">
            <div class="news-icon-box news-icon-red"><i class="fas fa-ban"></i></div>
            <div class="news-stat-text">
                <h4>Tidak Ditayangkan</h4>
                <h2>{{ $tidakDitayangkan ?? 0 }}</h2>
            </div>
        </div>
        <a href="{{ route('superadmin.tambah-berita') }}" class="adm-btn-tambah">
            <div class="adm-icon-box adm-icon-dark-red">
                <i class="fas fa-plus"></i>
            </div>
            <span>Tambah Berita</span>
        </a>
    </div>

    <div class="news-search-row">
        <div class="news-search-box">
            <input type="text" id="searchBerita" placeholder="Cari berita..." value="{{ request('search') }}">
            <i class="fas fa-search"></i>
        </div>
        <button class="news-btn-filter" id="filterBtn"><i class="fas fa-filter"></i> Filter</button>
    </div>

    <div class="news-table-card">
        <h3>Daftar Berita</h3>
        <table class="news-table" id="newsTable">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Judul Berita</th>
                    <th>Penulis</th>
                    <th>Tanggal Terbit</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beritas as $berita)
                <tr>
                    <td>
                        <div class="news-foto slideshow-container">
                            @if($berita->galeri && $berita->galeri->isNotEmpty())
                                @foreach($berita->galeri as $index => $foto)
                                    <img src="{{ asset('storage/' . $foto->path) }}" 
                                         alt="Foto Berita" 
                                         class="{{ $index === 0 ? 'active' : '' }}">
                                @endforeach
                            @else
                                <span>No Img</span>
                            @endif
                        </div>
                    </td>
                    <td><strong>{{ $berita->judul }}</strong><br>
                        <span style="font-size:0.7rem; color:#888;">{{ \Illuminate\Support\Str::limit($berita->isi_berita, 60) }}</span>
                    </td>
                    <td>{{ $berita->user->name ?? 'Admin' }}</td>
                    <td>{{ $berita->created_at ? $berita->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        @if($berita->status == 'tayang')
                            <span class="badge-tayang">Tayang</span>
                        @else
                            <span class="badge-tidak-tayang">Tidak Ditayangkan</span>
                        @endif
                    </td>
                    <td class="news-actions">
                        <a href="{{ route('superadmin.edit-berita', $berita->id) }}" class="btn-edit-news">Edit</a>
                        <button class="btn-hapus-news" onclick="confirmDelete({{ $berita->id }})">Hapus</button>
                        <form id="delete-form-{{ $berita->id }}" action="{{ route('superadmin.hapus-berita', $berita->id) }}" method="POST" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center">Belum ada data berita.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination-links">
            {{ $beritas->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if(confirm('Yakin ingin menghapus berita ini?')) {
            document.getElementById('delete-form-'+id).submit();
        }
    }

    document.getElementById('filterBtn')?.addEventListener('click', function() {
        let search = document.getElementById('searchBerita').value;
        window.location.href = "{{ route('superadmin.kelola-berita') }}?search=" + encodeURIComponent(search);
    });

    // =========================================================================
    // JAVASCRIPT: AUTOMATIC SLIDESHOW 5 DETIK PER KOTAK GAMBAR
    // =========================================================================
    document.addEventListener("DOMContentLoaded", function() {
        const containers = document.querySelectorAll('.slideshow-container');

        containers.forEach(container => {
            const images = container.querySelectorAll('img');
            // Jalankan rotasi hanya jika jumlah gambarnya lebih dari 1
            if (images.length > 1) {
                let currentIndex = 0;

                setInterval(() => {
                    // Hilangkan class active dari gambar saat ini
                    images[currentIndex].classList.remove('active');

                    // Pindah ke index berikutnya (reset ke 0 jika sudah gambar terakhir)
                    currentIndex = (currentIndex + 1) % images.length;

                    // Tambahkan class active ke gambar yang baru
                    images[currentIndex].classList.add('active');
                }, 5000); // 5000 milidetik = 5 detik
            }
        });
    });
</script>
@endsection