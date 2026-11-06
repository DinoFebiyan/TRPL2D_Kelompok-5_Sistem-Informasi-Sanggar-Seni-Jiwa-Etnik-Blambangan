@extends('layouts.superadmin')

@section('title', 'Kelola Profil Sanggar — JEB')
@section('header_title', 'Kelola Profil Sanggar')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* =========================================================
       OVERRIDE LAYOUT — buka gembok scroll horizontal
       Layout superadmin: .content punya padding, tidak ada overflow
       tapi kita paksa auto agar anak boleh scroll
       ========================================================= */
    @media (max-width: 992px) {
        .content {
            overflow-x: auto !important;
            overflow-y: visible !important;
            min-width: 0 !important;
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        .main {
            overflow-x: hidden !important;
            max-width: 100vw !important;
        }
    }

    html, body { max-width: 100vw; }
    .prof-wrapper { width: 100%; font-family: 'Poppins', sans-serif; color: #333; }
    .prof-card-card { background: #fff; border-radius: 12px; padding: 25px; border: 1px solid #f0f0f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02); margin-bottom: 28px; }
    .prof-card-card h3 { margin: 0 0 20px 0; font-size: 1.1rem; color: #333; display: flex; justify-content: space-between; align-items: center; }
    .btn-edit-profil { background: #e2c0c0; color: #5a1a1a; border-radius: 12px; padding: 8px 20px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; }
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

    .prof-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-tambah-personel {
        background: #e2c0c0;
        color: #5a1a1a;
        padding: 8px 16px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-aksi-edit { color: #3b82f6; text-decoration: none; margin-right: 10px; font-weight: 600; }
    .btn-aksi-hapus { color: #ef4444; border: none; background: none; cursor: pointer; font-weight: 600; padding: 0; font-family: inherit; }

    /* ==========================================
       RESPONSIVE
       ========================================== */
    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr !important; }
        .full-width, .photo-row { grid-column: span 1 !important; }
        .photo-row { flex-direction: column !important; }
        .prof-card-card { padding: 18px 15px !important; }

        .prof-card-card h3 {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px !important;
        }
        .btn-edit-profil {
            width: auto !important;
            align-self: flex-start !important;
            padding: 10px 18px !important;
            min-height: 40px !important;
        }

        .prof-table-header {
            display: flex !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 12px !important;
            margin-bottom: 18px !important;
        }

        /* ✅ Tombol Tambah Personel — super defensive */
        .btn-tambah-personel {
            display: flex !important;
            width: 100% !important;
            min-width: 0 !important;
            min-height: 48px !important;
            padding: 12px 20px !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 8px !important;
            background-color: #e2c0c0 !important;
            color: #5a1a1a !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            box-sizing: border-box !important;
            line-height: 1.4 !important;
            white-space: nowrap !important;
            height: auto !important;
        }
        .btn-tambah-personel i,
        .btn-tambah-personel span {
            display: inline-block !important;
            color: inherit !important;
        }

        /* ✅ CARD: overflow VISIBLE (bukan hidden!) supaya anak bisa scroll */
        .prof-table-card {
            padding: 18px 12px !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow: visible !important;   /* ⬅️ INI KUNCI UTAMA */
        }

        /* ✅ Wrapper tabel — paksa scroll */
        .table-responsive-wrapper {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            -webkit-overflow-scrolling: touch !important;
            box-sizing: border-box !important;
            padding-bottom: 12px !important;
            margin: 0 !important;
        }

        /* Scrollbar kelihatan */
        .table-responsive-wrapper::-webkit-scrollbar { height: 6px !important; }
        .table-responsive-wrapper::-webkit-scrollbar-track { background: #f1f1f1 !important; border-radius: 3px !important; }
        .table-responsive-wrapper::-webkit-scrollbar-thumb { background: #c0a0a0 !important; border-radius: 3px !important; }

        .prof-table {
            display: table !important;
            min-width: 900px !important;
            width: 900px !important;
            table-layout: auto !important;
        }

        .photo-box { padding: 12px !important; }
    }

    @media (max-width: 480px) {
        .prof-card-card { padding: 15px 12px !important; }
        .info-item .value { font-size: 0.8rem !important; padding: 10px 12px !important; }
        .prof-table th, .prof-table td { padding: 10px 15px !important; }
    }
</style>

<div class="prof-wrapper">
    {{-- INFORMASI UMUM --}}
    <div class="prof-card-card">
        <h3>
            <span>Informasi Umum Sanggar</span>
            <a href="{{ route('superadmin.edit-profil') }}" class="btn-edit-profil"><i class="fas fa-edit"></i> Edit Profil</a>
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
        <div class="prof-table-header">
            <h3 style="margin: 0;">Daftar Pengurus</h3>
            <a href="{{ route('superadmin.tambah-pengurus') }}" class="btn-tambah-personel">
                <i class="fas fa-plus"></i> <span>Tambah Pengurus</span>
            </a>
        </div>
        <div class="table-responsive-wrapper">
            <table class="prof-table">
                <thead>
                    <tr><th>Foto</th><th>Nama Lengkap</th><th>Jabatan</th><th>Instagram</th><th>No HP</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($pengurus as $item)
                    <tr>
                        <td>
                            <div class="prof-foto-frame">
                                <img src="{{ $item->galeri->isNotEmpty() ? asset('storage/' . $item->galeri->first()->path) : 'https://ui-avatars.com/api/?name='.urlencode($item->nama) }}">
                            </div>
                        </td>
                        <td style="font-weight:600">{{ $item->nama }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->instagram ?? '-' }}</td>
                        <td>{{ $item->no_handphone }}</td>
                        <td><span class="{{ $item->status == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">{{ ucfirst($item->status) }}</span></td>
                        <td>
                            <a href="{{ route('superadmin.edit-pengurus', $item->id) }}" class="btn-aksi-edit">Edit</a>
                            <button class="btn-aksi-hapus" onclick="showPopupHapus('delete-pengurus-{{ $item->id }}')">Hapus</button>
                            <form id="delete-pengurus-{{ $item->id }}" action="{{ route('superadmin.hapus-pengurus', $item->id) }}" method="POST" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
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
        <div class="prof-table-header">
            <h3 style="margin: 0;">Daftar Pelatih</h3>
            <a href="{{ route('superadmin.tambah-pelatih') }}" class="btn-tambah-personel">
                <i class="fas fa-plus"></i> <span>Tambah Pelatih</span>
            </a>
        </div>
        <div class="table-responsive-wrapper">
            <table class="prof-table">
                <thead>
                    <tr><th>Foto</th><th>Nama Lengkap</th><th>Jabatan</th><th>Instagram</th><th>No HP</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($pelatih as $item)
                    <tr>
                        <td>
                            <div class="prof-foto-frame">
                                <img src="{{ $item->galeri->isNotEmpty() ? asset('storage/' . $item->galeri->first()->path) : 'https://ui-avatars.com/api/?name='.urlencode($item->nama) }}">
                            </div>
                        </td>
                        <td style="font-weight:600">{{ $item->nama }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->instagram ?? '-' }}</td>
                        <td>{{ $item->no_handphone }}</td>
                        <td><span class="{{ $item->status == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">{{ ucfirst($item->status) }}</span></td>
                        <td>
                            <a href="{{ route('superadmin.edit-pelatih', $item->id) }}" class="btn-aksi-edit">Edit</a>
                            <button class="btn-aksi-hapus" onclick="showPopupHapus('delete-pelatih-{{ $item->id }}')">Hapus</button>
                            <form id="delete-pelatih-{{ $item->id }}" action="{{ route('superadmin.hapus-pelatih', $item->id) }}" method="POST" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
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
    {{-- DAFTAR ANGGOTA --}}
    <div class="prof-table-card">
        <div class="prof-table-header">
            <h3 style="margin: 0;">Daftar Anggota</h3>
            <a href="{{ route('superadmin.tambah-anggota') }}" class="btn-tambah-personel">
                <i class="fas fa-plus"></i> <span>Tambah Anggota</span>
            </a>
        </div>
        <div class="table-responsive-wrapper">
            <table class="prof-table">
                <thead>
                    <tr><th>Foto</th><th>Nama Lengkap</th><th>Instagram</th><th>No HP</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($anggota as $item)
                    <tr>
                        <td>
                            <div class="prof-foto-frame">
                                <img src="{{ $item->galeri->isNotEmpty() ? asset('storage/' . $item->galeri->first()->path) : 'https://ui-avatars.com/api/?name='.urlencode($item->nama) }}">
                            </div>
                        </td>
                        <td style="font-weight:600">{{ $item->nama }}</td>
                        <td>{{ $item->instagram ?? '-' }}</td>
                        <td>{{ $item->no_handphone }}</td>
                        <td><span class="{{ $item->status == 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">{{ ucfirst($item->status) }}</span></td>
                        <td>
                            <a href="{{ route('superadmin.edit-anggota', $item->id) }}" class="btn-aksi-edit">Edit</a>
                            <button class="btn-aksi-hapus" onclick="showPopupHapus('delete-anggota-{{ $item->id }}')">Hapus</button>
                            <form id="delete-anggota-{{ $item->id }}" action="{{ route('superadmin.hapus-anggota', $item->id) }}" method="POST" style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;">Daftar anggota kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:15px;">{{ $anggota->links() }}</div>
    </div>
</div>

{{-- Popup Konfirmasi Hapus --}}
@include('popup.hapus')
@endsection