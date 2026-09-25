@extends('layouts.superadmin')

@section('title', 'Kelola Admin — JEB')
@section('header_title', 'Kelola Admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* ==========================================
       1. CSS RESET & ANTI-BLOWOUT (SANGAT PENTING)
       ========================================== */
    .main, .content {
        min-width: 0 !important;
        max-width: 100vw !important;
        overflow-x: hidden !important;
    }

    .adm-wrapper {
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
        color: #333;
    }
    
    /* ==========================================
       2. RESPONSIF STATS GRID
       ========================================== */
    .adm-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 24px;
        width: 100%;
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
        min-width: 0; 
    }
    .adm-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .adm-icon-blue { background: #eff6ff; color: #3b82f6; }
    .adm-icon-green { background: #f0fdf4; color: #16a34a; }
    .adm-icon-red { background: #fef2f2; color: #ef4444; }
    .adm-icon-dark-red { background-color: #7a2a214a; color: #411f1f; }
    
    .adm-stat-text {
        min-width: 0; 
        flex: 1;
    }
    .adm-stat-text h4 {
        margin: 0;
        font-size: 0.7rem;
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid #e8dede;
        padding: 0 15px;
        min-width: 0;
    }
    .adm-btn-tambah:hover {
        background: #d4aeae;
    }

    /* ==========================================
       3. SEARCH ROW RESPONSIF 
       ========================================== */
    .adm-search-row {
        display: flex;
        flex-direction: row; 
        flex-wrap: nowrap; 
        gap: 15px;
        margin-bottom: 24px;
        align-items: stretch;
        width: 100%;
    }
    .adm-search-box {
        flex: 1 1 0%;
        min-width: 0; 
        position: relative;
    }
    .adm-search-box input {
        width: 100%;
        min-width: 0;
        padding: 12px 35px 12px 15px;
        border-radius: 12px;
        border: 1px solid #e8dede;
        outline: none;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        background: #fff;
        text-overflow: ellipsis;
        box-sizing: border-box; 
    }
    .adm-search-box .fa-search {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #eab308;
        font-size: 1rem;
        cursor: pointer;
    }
    .adm-btn-filter {
        flex: 0 0 auto; 
        background: #b91c1c;
        color: #ffffff;
        border: none;
        padding: 0 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        white-space: nowrap;
    }

    /* ==========================================
       4. TABEL RESPONSIF (ANTI BOCOR)
       ========================================== */
    .adm-table-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 24px; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid #f0f0f0;
        width: 100%;
        min-width: 0;
        overflow: hidden;
        box-sizing: border-box;
    }
    .adm-table-card h3 {
        margin: 0 0 16px 0;
        font-size: 1rem;
        color: #333;
    }
    
    .table-responsive-wrapper {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 10px; 
        padding-bottom: 10px;
    }
    .adm-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }
    .adm-table thead {
        background-color: #F5EBEB;
    }
    .adm-table th {
        text-align: left;
        padding: 14px 16px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .adm-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #E0E0E0;
        vertical-align: middle;
        font-size: 0.8rem;
    }
    .adm-foto-frame {
        width: 36px;
        height: 36px;
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
    .adm-badge-aktif, .adm-badge-nonaktif {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }
    .adm-badge-aktif { background: #dcfce7; color: #16a34a; }
    .adm-badge-nonaktif { background: #fee2e2; color: #ef4444; }
    
    .adm-action-btns {
        white-space: nowrap;
    }
    .adm-action-btns a, .adm-action-btns button {
        border: none;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 600;
        margin-right: 4px;
        text-decoration: none;
        display: inline-block;
    }
    .adm-btn-edit { background: #eff6ff; color: #3b82f6; }
    .adm-btn-hapus { background: #fef2f2; color: #ef4444; }

    /* ==========================================
       5. PAGINATION 
       ========================================== */
    .adm-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .adm-pagination p {
        font-size: 0.75rem;
        color: #b07d7d;
        margin: 0;
    }
    .adm-page-controls {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
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
        font-size: 0.75rem;
        text-decoration: none;
    }
    .adm-page-controls a:hover {
        background: #f5ebeb;
        border-color: #d4aeae;
    }
    .adm-page-controls span.active {
        background: #5B1A1A;
        color: #fff;
        border-color: #5B1A1A;
    }
    .adm-page-controls span.disabled {
        color: #ccc;
        cursor: not-allowed;
    }

    /* ==========================================
       6. MODAL FILTER 
       ========================================== */
    .custom-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        justify-content: center; align-items: center;
        z-index: 9999;
    }
    .custom-modal-content {
        background: #fff;
        width: 90%;
        max-width: 480px;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .custom-modal-header h3 { margin: 0; font-size: 1.2rem; font-weight: 700; color: #2D1A1A; }
    .custom-modal-header p { margin: 4px 0 0 0; font-size: 0.8rem; color: #888; }
    .modal-divider { border: 0; border-top: 1px solid #E5E7EB; margin: 16px 0; }
    .section-title { font-size: 0.9rem; font-weight: 600; color: #333; margin-bottom: 12px; display: block; }
    .checkbox-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .filter-checkbox-card {
        border: 1px solid #D1D5DB;
        border-radius: 12px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
    }
    .filter-checkbox-card input[type="checkbox"] { accent-color: #EF4444; width: 16px; height: 16px; cursor: pointer; }
    .filter-checkbox-card.selected-box { border-color: #EF4444; background-color: #FFF5F5; }
    .filter-checkbox-card.selected-box .checkbox-label { font-weight: 600; color: #EF4444; }
    .checkbox-label { font-size: 0.85rem; color: #4B5563; cursor: pointer; }
    .custom-modal-footer { display: flex; gap: 12px; margin-top: 20px; }
    .btn-modal-batal, .btn-modal-terapkan {
        flex: 1; padding: 10px; border-radius: 10px; font-family: 'Poppins', sans-serif;
        font-weight: 600; font-size: 0.85rem; border: none; cursor: pointer; text-align: center;
    }
    .btn-modal-batal { background: #E5E7EB; color: #374151; }
    .btn-modal-terapkan { background: #EF4444; color: white; }

    /* ==========================================
       7. MEDIA QUERIES UNTUK HP (MOBILE) 
       ========================================== */
    @media (max-width: 640px) {
        
        .adm-stats-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
            width: 100% !important;
        }
        
        /* ✅ FIX: icon & text side by side */
        .adm-stat-card {
            padding: 10px 12px !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: flex-start !important;
            text-align: left !important;
            gap: 10px !important;
            min-width: 0 !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .adm-icon-box {
            width: 32px !important;
            height: 32px !important;
            font-size: 0.9rem !important;
            flex-shrink: 0 !important;
        }
        
        .adm-stat-text {
            text-align: left !important;
            flex: 1 !important;
            min-width: 0 !important;
            width: auto !important;
        }
        
        .adm-stat-text h4 {
            font-size: 0.6rem !important;
            letter-spacing: 0.2px !important;
            line-height: 1.2 !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }
        
        .adm-stat-text h2 {
            font-size: 1.2rem !important;
            margin: 2px 0 0 0 !important;
            line-height: 1.1 !important;
        }
        
        .adm-btn-tambah {
            grid-column: span 2 !important; 
            padding: 12px !important;
            font-size: 0.9rem !important;
            flex-direction: row !important;
            width: 100% !important;
        }

        /* MEMAKSA SEARCH DAN FILTER 1 BARIS DI HP */
        .adm-search-row {
            display: flex !important;
            flex-direction: row !important; 
            flex-wrap: nowrap !important;
            gap: 8px !important;
            width: 100% !important;
        }
        .adm-search-box {
            flex: 1 1 0% !important;
        }
        .adm-search-box input {
            padding: 10px 30px 10px 12px !important;
            font-size: 0.8rem !important;
            min-width: 0 !important;
            width: 100% !important;
        }
        .adm-search-box .fa-search {
            right: 10px !important;
            font-size: 0.9rem !important;
        }
        .adm-btn-filter {
            flex: 0 0 auto !important;
            padding: 0 12px !important; 
            font-size: 0.8rem !important;
            gap: 5px !important;
        }
        .adm-btn-filter span { display: none !important; }

        /* PERBAIKAN TABEL CARD DI HP */
        .adm-table-card {
            padding: 15px 10px !important; 
            width: 100% !important;
        }
        
        .adm-pagination {
            flex-direction: column !important;
            gap: 12px !important;
        }
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

    {{-- SEARCH & FILTER --}}
    <form method="GET" action="{{ route('superadmin.kelola-admin') }}" id="formFilterAdmin">
        <div class="adm-search-row">
            <div class="adm-search-box">
                <input type="text" name="search" id="searchAdmin" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                <i class="fas fa-search" onclick="submitFormFilter()"></i>
            </div>
            <button type="button" class="adm-btn-filter" onclick="openFilterModal()">
                <i class="fas fa-filter"></i> <span>Filter</span>
            </button>
        </div>

        {{-- MODAL FILTER --}}
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
        
        {{-- WRAPPER TABEL SCROLL HORIZONTAL --}}
        <div class="table-responsive-wrapper">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>FOTO</th>
                        <th>NAMA LENGKAP</th>
                        <th>EMAIL</th>
                        <th>NO. HP</th>
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
                            <button class="adm-btn-hapus" onclick="showPopupHapus('delete-form-{{ $admin->id }}')">Hapus</button>
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
        </div>
        
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
    function openFilterModal() {
        document.getElementById('filterModal').style.display = 'flex';
    }
    function closeFilterModal() {
        document.getElementById('filterModal').style.display = 'none';
    }
    window.onclick = function(event) {
        const modal = document.getElementById('filterModal');
        if (event.target == modal) closeFilterModal();
    }
    function syncCardVisual(checkboxElement) {
        let parentCard = checkboxElement.closest('.filter-checkbox-card');
        if (checkboxElement.checked) parentCard.classList.add('selected-box');
        else parentCard.classList.remove('selected-box');
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

{{-- Popup Konfirmasi Hapus --}}
@include('popup.hapus')
@endsection