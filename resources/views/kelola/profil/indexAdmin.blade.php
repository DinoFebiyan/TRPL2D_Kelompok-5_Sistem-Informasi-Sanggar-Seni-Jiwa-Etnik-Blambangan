@extends('layouts.admin')

@section('title', 'Kelola Profil Sanggar — JEB')
@section('header_title', 'Kelola Profil Sanggar')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    html, body { max-width: 100vw; overflow-x: hidden; }
    .prof-wrapper { width: 100%; font-family: 'Poppins', sans-serif; color: #333; }
    .prof-card-card { background: #fff; border-radius: 12px; padding: 25px; border: 1px solid #f0f0f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02); margin-bottom: 28px; }
    .prof-card-card h3 { margin: 0 0 20px 0; font-size: 1.1rem; color: #333; display: flex; justify-content: space-between; align-items: center; }
    .btn-edit-profil { background: #e2c0c0; color: #5a1a1a; border-radius: 12px; padding: 8px 20px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .info-item { display: flex; flex-direction: column; gap: 6px; }
    .info-item label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #b07d7d; }
    .info-item .value { font-size: 0.85rem; line-height: 1.6; color: #333; background: #fffbfb; padding: 12px 16px; border-radius: 8px; border: 1px solid #e8dede; }
    .full-width { grid-column: span 2; }
    .photo-row { display: flex; gap: 20px; grid-column: span 2; }
    .photo-box { flex: 1; background: #fffbfb; border-radius: 12px; padding: 15px; border: 1px solid #e8dede; display: flex; align-items: center; gap: 15px; }
    .photo-box img { width: 60px; height: 60px; border-radius: 8px; object-fit: cover; }
    .prof-table-card { background: #fff; border-radius: 12px; padding: 24px; border: 1px solid #f0f0f0; margin-bottom: 28px; }
    .table-responsive-wrapper { display: block; width: 100%; overflow-x: auto; }
    .prof-table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .prof-table th { text-align: left; padding: 14px 25px; color: #b07d7d; font-size: 0.85rem; }
    .prof-table td { padding: 12px 25px; border-bottom: 1px solid #E0E0E0; font-size: 0.85rem; }
    .prof-foto-frame img { width: 55px; height: 55px; border-radius: 50%; object-fit: cover; }
    .badge-aktif { background: #dcfce7; color: #16a34a; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; }
    .badge-nonaktif { background: #fee2e2; color: #ef4444; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; }
    @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr !important; } .full-width, .photo-row { grid-column: span 1 !important; } .photo-row { flex-direction: column; } }
</style>

<div class="prof-wrapper">
    {{-- HANYA EDIT TEXT & DOKUMEN TUNGGAL --}}
    <div class="prof-card-card">
        <h3>
            <span>Informasi Umum Sanggar</span>
            <a href="{{ route('admin.edit-profil') }}" class="btn-edit-profil"><i class="fas fa-edit"></i> Edit Profil</a>
        </h3>
        <div class="info-grid">
            <div class="info-item full-width"><label>Deskripsi Sanggar</label><div class="value">{{ $profil->deskripsi ?? 'Belum diisi' }}</div></div>
            <div class="info-item"><label>Visi</label><div class="value">{{ $profil->visi ?? 'Belum diisi' }}</div></div>
            <div class="info-item"><label>Misi</label><div class="value">{{ $profil->misi ?? 'Belum diisi' }}</div></div>
            <div class="info-item full-width"><label>Sejarah Singkat</label><div class="value">{{ $profil->sejarah ?? 'Belum diisi' }}</div></div>
            <div class="info-item full-width"><label>Sambutan Pembina</label><div class="value">{{ $profil->sambutan_pembina ?? 'Belum diisi' }}</div></div>
            
            <div class="photo-row">
                <div class="photo-box">
                    <img src="{{ ($profil && $profil->logo) ? asset('storage/' . $profil->logo->path) : asset('img/default-logo.png') }}">
                    <div><strong>Logo Sanggar</strong></div>
                </div>
                <div class="photo-box">
                    <img src="{{ ($profil && $profil->fotoPembina) ? asset('storage/' . $profil->fotoPembina->path) : asset('img/default-avatar.png') }}">
                    <div><strong>Foto Pembina</strong></div>
                </div>
            </div>
        </div>
    </div>

    {{-- DAFTAR PENGURUS --}}
    <div class="prof-table-card">
        <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
            <h3>Daftar Pengurus</h3>
            <a href="{{ route('admin.tambah-pengurus') }}" style="background:#e2c0c0; color:#5a1a1a; padding:8px 16px; border-radius:12px; text-decoration:none; font-weight:700;">+ Tambah Pengurus</a>
        </div>
        <div class="table-responsive-wrapper">
            <table class="prof-table">
                <thead>
                    <tr><th>Foto</th><th>Nama Lengkap</th><th>Jabatan</th><th>Instagram</th><th>No HP</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($pengurus as $item)
                    <tr>
                        <td><div class="prof-foto-frame"><img src="{{ $item->galeri->isNotEmpty() ? asset('storage/' . $item->galeri->first()->path) : 'https://ui-avatars.com/api/?name='.urlencode($item->nama) }}"></div></td>
                        <td style="font-weight:600">{{ $item->nama }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->instagram ?? '-' }}</td>
                        <td>{{ $item->no_handphone }}</td>
                        <td><span class="{{ $item->status == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">{{ ucfirst($item->status) }}</span></td>
                        <td>
                            <a href="{{ route('admin.edit-pengurus', $item->id) }}" style="color:#3b82f6; text-decoration:none; margin-right:10px;">Edit</a>
                            <button onclick="confirmDelete('pengurus', {{ $item->id }})" style="color:#ef4444; border:none; background:none; cursor:pointer;">Hapus</button>
                            <form id="delete-pengurus-{{ $item->id }}" action="{{ route('admin.hapus-pengurus', $item->id) }}" method="POST">@csrf @method('DELETE')</form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;">Daftar pengurus kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:15px;">{{ $pengurus->links() }}</div>
    </div>

    {{-- DAFTAR PELATIH --}}
    <div class="prof-table-card">
        <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
            <h3>Daftar Pelatih</h3>
            <a href="{{ route('admin.tambah-pelatih') }}" style="background:#e2c0c0; color:#5a1a1a; padding:8px 16px; border-radius:12px; text-decoration:none; font-weight:700;">+ Tambah Pelatih</a>
        </div>
        <div class="table-responsive-wrapper">
            <table class="prof-table">
                <thead>
                    <tr><th>Foto</th><th>Nama Lengkap</th><th>Jabatan</th><th>Instagram</th><th>No HP</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($pelatih as $item)
                    <tr>
                        <td><div class="prof-foto-frame"><img src="{{ $item->galeri->isNotEmpty() ? asset('storage/' . $item->galeri->first()->path) : 'https://ui-avatars.com/api/?name='.urlencode($item->nama) }}"></div></td>
                        <td style="font-weight:600">{{ $item->nama }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->instagram ?? '-' }}</td>
                        <td>{{ $item->no_handphone }}</td>
                        <td><span class="{{ $item->status == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">{{ ucfirst($item->status) }}</span></td>
                        <td>
                            <a href="{{ route('admin.edit-pelatih', $item->id) }}" style="color:#3b82f6; text-decoration:none; margin-right:10px;">Edit</a>
                            <button onclick="confirmDelete('pelatih', {{ $item->id }})" style="color:#ef4444; border:none; background:none; cursor:pointer;">Hapus</button>
                            <form id="delete-pelatih-{{ $item->id }}" action="{{ route('admin.hapus-pelatih', $item->id) }}" method="POST">@csrf @method('DELETE')</form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;">Daftar pelatih kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:15px;">{{ $pelatih->links() }}</div>
    </div>
</div>

<script>
    function confirmDelete(jenis, id) {
        if(confirm('Hapus personel ini?')) { document.getElementById('delete-' + jenis + '-' + id).submit(); }
    }
</script>
@endsection