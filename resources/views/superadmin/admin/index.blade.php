@extends('layouts.superadmin')

@section('title', 'Kelola Admin — JEB')
@section('header_title', 'Kelola Admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .adm-wrapper {
        width: 100%;
        font-family: 'Poppins', sans-serif;
        color: #333;
    }
    
    .adm-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 24px;
    }
    .adm-stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid #f0f0f0;
    }
    .adm-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .adm-icon-blue { background: #eff6ff; color: #3b82f6; }
    .adm-icon-green { background: #f0fdf4; color: #16a34a; }
    .adm-icon-red { background: #fef2f2; color: #ef4444; }
    .adm-icon-dark-red { background-color: #7a2a214a; color: #411f1f; }
    .adm-stat-text h4 {
        margin: 0;
        font-size: 0.7rem;
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .adm-stat-text h2 {
        margin: 0;
        font-size: 1.5rem;
        color: #333;
        font-weight: 700;
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
    .adm-search-row {
        display: flex;
        gap: 15px;
        margin-bottom: 24px;
    }
    .adm-search-box {
        flex: 1;
        position: relative;
    }
    .adm-search-box input {
        width: 100%;
        padding: 14px 20px;
        border-radius: 12px;
        border: 1px solid #e8dede;
        outline: none;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        background: #fff;
    }
    .adm-search-box .fa-search {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #eab308;
        font-size: 1.2rem;
        cursor: pointer;
    }
    .adm-btn-filter {
        background: #b91c1c;
        color: #ffffff;
        border: none;
        padding: 0 35px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    
    .adm-table-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 25px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid #f0f0f0;
        overflow-x: auto;
    }
    .adm-table-card h3 {
        margin: 0 0 20px 0;
        padding: 0 25px;
        font-size: 1.1rem;
        color: #333;
    }
    .adm-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    .adm-table thead {
        background-color: #F5EBEB;
    }
    .adm-table th {
        text-align: left;
        padding: 14px 25px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: none;
        text-transform: uppercase;
    }
    .adm-table td {
        padding: 15px 25px;
        border-bottom: 1px solid #E0E0E0;
        vertical-align: middle;
        font-size: 0.85rem;
    }
    .adm-foto-frame {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e8dede;
        background: #f3f4f6;
    }
    .adm-foto-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .adm-badge-aktif {
        background: #dcfce7;
        color: #16a34a;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .adm-badge-nonaktif {
        background: #fee2e2;
        color: #ef4444;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .adm-action-btns a, .adm-action-btns button {
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 5px;
        text-decoration: none;
        display: inline-block;
    }
    .adm-btn-edit { background: #eff6ff; color: #3b82f6; }
    .adm-btn-hapus { background: #fef2f2; color: #ef4444; }
    
    .adm-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding: 0 25px;
    }
    .adm-pagination p {
        font-size: 0.85rem;
        color: #b07d7d;
        margin: 0;
        font-weight: 500;
    }
    .adm-page-controls {
        display: flex;
        gap: 8px;
    }
    .adm-page-controls a, .adm-page-controls span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #e8dede;
        background: #fff;
        color: #333;
        font-size: 0.85rem;
        text-decoration: none;
        cursor: pointer;
        transition: 0.2s;
    }
    .adm-page-controls a:hover {
        background: #f5ebeb;
        border-color: #d4aeae;
    }
    .adm-page-controls span.active {
        background: #5B1A1A;
        color: #fff;
        border-color: #5B1A1A;
        font-weight: 700;
    }
    .adm-page-controls span.disabled {
        color: #ccc;
        cursor: not-allowed;
        background: #fdfdfd;
    }

    /* POPUP MODAL COMPONENT STYLE */
    .custom-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        justify-content: center; align-items: center;
        z-index: 9999;
    }
    .custom-modal-content {
        background: #fff; width: 100%; max-width: 480px;
        border-radius: 24px; padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        animation: popupSlightIn 0.3s ease-out;
    }
    .custom-modal-header h3 { margin: 0; font-size: 1.3rem; font-weight: 700; color: #2D1A1A; }
    .custom-modal-header p { margin: 4px 0 0 0; font-size: 0.85rem; color: #888; }
    .modal-divider { border: 0; border-top: 1px solid #E5E7EB; margin: 18px 0; }
    .section-title { font-size: 0.95rem; font-weight: 600; color: #333; display: block; margin-bottom: 12px; }
    .checkbox-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .filter-checkbox-card {
        border: 1px solid #D1D5DB; border-radius: 12px; padding: 12px 15px;
        display: flex; align-items: center; gap: 10px; cursor: pointer;
        transition: all 0.2s; background: #fff; user-select: none;
    }
    .filter-checkbox-card:hover { border-color: #b07d7d; background: #fffbfb; }
    .filter-checkbox-card input[type="checkbox"] { accent-color: #EF4444; width: 17px; height: 17px; cursor: pointer; }
    .filter-checkbox-card.selected-box { border-color: #EF4444; background-color: #FFF5F5; }
    .filter-checkbox-card.selected-box .checkbox-label { font-weight: 600; color: #EF4444; }
    .checkbox-label { font-size: 0.85rem; color: #4B5563; cursor: pointer; }
    .custom-modal-footer { display: flex; gap: 15px; margin-top: 25px; }
    .btn-modal-batal, .btn-modal-terapkan {
        flex: 1; padding: 12px; border-radius: 10px; font-family: 'Poppins', sans-serif;
        font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: 0.2s;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-modal-batal { background: #E5E7EB; color: #374151; }
    .btn-modal-batal:hover { background: #D1D5DB; }
    .btn-modal-terapkan { background: #EF4444; color: white; }
    .btn-modal-terapkan:hover { background: #DC2626; }

    @keyframes popupSlightIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>

<div class="adm-wrapper">
    <div class="adm-stats-grid">
        <div class="adm-stat-card">
            <div class="adm-icon-box adm-icon-blue"><i class="fas fa-user"></i></div>
            <div class="adm-stat-text">
                <h4>Total Admin</h4>
                <h2>{{ $totalAdmin ?? 0 }}</h2>
            </div>
        </div>
        <div class="adm-stat-card">
            <div class="adm-icon-box adm-icon-green"><i class="fas fa-check-circle"></i></div>
            <div class="adm-stat-text">
                <h4>Aktif</h4>
                <h2>{{ $activeAdmins ?? 0 }}</h2>
            </div>
        </div>
        <div class="adm-stat-card">
            <div class="adm-icon-box adm-icon-red"><i class="fas fa-ban"></i></div>
            <div class="adm-stat-text">
                <h4>Nonaktif</h4>
                <h2>{{ $inactiveAdmins ?? 0 }}</h2>
            </div>
        </div>
        <a href="{{ route('superadmin.tambah-admin') }}" class="adm-btn-tambah">
            <div class="adm-icon-box adm-icon-dark-red">
                <i class="fas fa-plus"></i>
            </div>
            <span>Tambah Admin</span>
        </a>
    </div>

    {{-- INTEGRATED FORM ACTION GET --}}
    <form method="GET" action="{{ route('superadmin.kelola-admin') }}" id="formFilterAdmin">
        <div class="adm-search-row">
            <div class="adm-search-box">
                <input type="text" name="search" id="searchAdmin" placeholder="Cari berdasarkan nama, email, no handphone, dll..." value="{{ request('search') }}">
                <i class="fas fa-search" onclick="submitFormFilter()"></i>
            </div>
            <button type="button" class="adm-btn-filter" onclick="openFilterModal()">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>

        {{-- POPUP FILTER DI DALAM FORM --}}
        <div id="filterModal" class="custom-modal-overlay">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h3>Filter Pencarian anda</h3>
                    <p>Sesuaikan apa yang ingin anda tampilkan</p>
                </div>
                <hr class="modal-divider">

                <div class="custom-modal-body">
                    <div class="filter-group-section">
                        <label class="section-title">Status Akun</label>
                        <div class="checkbox-grid">
                            <label class="filter-checkbox-card {{ in_array('aktif', request('status', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="status[]" value="aktif" onchange="syncCardVisual(this)" {{ in_array('aktif', request('status', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Aktif</span>
                            </label>
                            <label class="filter-checkbox-card {{ in_array('nonaktif', request('status', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="status[]" value="nonaktif" onchange="syncCardVisual(this)" {{ in_array('nonaktif', request('status', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Nonaktif</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="custom-modal-footer">
                    <button type="button" class="btn-modal-batal" onclick="closeFilterModal()">Batal</button>
                    <button type="submit" class="btn-modal-terapkan">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </form>

    <div class="adm-table-card">
        <h3>Daftar Admin</h3>
        <table class="adm-table">
            <thead>
                <tr>
                    <th>FOTO</th>
                    <th>NAMA LENGKAP</th>
                    <th>EMAIL</th>
                    <th>NO. HANDPHONE</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr>
                    <td>
                        <div class="adm-foto-frame">
                            @if($admin->galeri && $admin->galeri->path && !Str::contains($admin->galeri->path, 'dummy'))
                                <img src="{{ asset('storage/' . $admin->galeri->path) }}" alt="Foto Admin">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&background=5B1A1A&color=fff&rounded=false" alt="Avatar">
                            @endif
                        </div>
                    </td>
                    <td style="font-weight:600">{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->no_handphone ?? '-' }}</td>
                    <td>
                        @if($admin->status == 'aktif')
                            <span class="adm-badge-aktif">Aktif</span>
                        @else
                            <span class="adm-badge-nonaktif">Nonaktif</span>
                        @endif
                    </td>
                    <td class="adm-action-btns">
                        <a href="{{ route('superadmin.edit-admin', $admin->id) }}" class="adm-btn-edit">Edit</a>
                        <button class="adm-btn-hapus" onclick="confirmDelete({{ $admin->id }})">Hapus</button>

                        <form id="delete-form-{{ $admin->id }}" action="{{ route('superadmin.hapus-admin', $admin->id) }}" method="POST" style="display: none;">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center">Belum ada data admin yang sesuai kriteria.</td></tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="adm-pagination">
            @if($admins->total() > 0)
                <p>Menampilkan {{ $admins->firstItem() }} - {{ $admins->lastItem() }} dari {{ $admins->total() }} admin</p>
            @else
                <p>Menampilkan 0 admin</p>
            @endif
            
            <div class="adm-page-controls">
                @if ($admins->onFirstPage())
                    <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $admins->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                @endif

                <span class="active">{{ $admins->currentPage() }}</span>

                @if ($admins->hasMorePages())
                    <a href="{{ $admins->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if(confirm('Yakin ingin menghapus admin ini?')) {
            document.getElementById('delete-form-'+id).submit();
        }
    }

    function openFilterModal() {
        document.getElementById('filterModal').style.display = 'flex';
    }

    function closeFilterModal() {
        document.getElementById('filterModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('filterModal');
        if (event.target == modal) {
            closeFilterModal();
        }
    }

    function syncCardVisual(checkboxElement) {
        let parentCard = checkboxElement.closest('.filter-checkbox-card');
        if (checkboxElement.checked) {
            parentCard.classList.add('selected-box');
        } else {
            parentCard.classList.remove('selected-box');
        }
    }

    function submitFormFilter() {
        document.getElementById('formFilterAdmin').submit();
    }

    document.getElementById('searchAdmin').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitFormFilter();
        }
    });
</script>
@endsection