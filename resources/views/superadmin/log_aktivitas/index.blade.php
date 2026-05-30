@extends('layouts.superadmin')

@section('title', 'Log Aktivitas — JEB')
@section('header_title', 'Log Aktivitas')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .log-wrapper {
        width: 100%;
        font-family: 'Poppins', sans-serif;
        color: #333;
    }
    
    .log-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 24px;
    }
    .log-stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid #f0f0f0;
    }
    .log-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .log-icon-blue { background: #eff6ff; color: #3b82f6; }
    .log-icon-green { background: #f0fdf4; color: #16a34a; }
    .log-icon-yellow { background: #fefce8; color: #eab308; }
    .log-icon-red { background: #fef2f2; color: #ef4444; }

    .log-search-row {
        display: flex;
        gap: 15px;
        margin-bottom: 24px;
    }
    .log-search-box {
        flex: 1;
        position: relative;
    }
    .log-search-box input {
        width: 100%;
        padding: 14px 20px;
        border-radius: 12px;
        border: 1px solid #e8dede;
        outline: none;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        background: #fff;
    }
    .log-search-box .fa-search {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #eab308;
        font-size: 1.2rem;
        cursor: pointer;
    }
    .log-btn-filter {
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
    
    .log-table-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 25px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border: 1px solid #f0f0f0;
        overflow-x: auto;
    }
    .log-table-card h3 {
        margin: 0 0 20px 0;
        padding: 0 25px;
        font-size: 1.1rem;
        color: #333;
    }
    .log-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }
    .log-table thead {
        background-color: #F5EBEB;
    }
    .log-table th {
        text-align: left;
        padding: 14px 25px;
        color: #b07d7d;
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: none;
        text-transform: uppercase;
    }
    .log-table td {
        padding: 15px 25px;
        border-bottom: 1px solid #E0E0E0;
        vertical-align: middle;
        font-size: 0.85rem;
        color: #333;
    }
    
    .log-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding: 0 25px;
    }
    .log-pagination p {
        font-size: 0.85rem;
        color: #b07d7d;
        margin: 0;
        font-weight: 500;
    }
    .log-page-controls {
        display: flex;
        gap: 8px;
    }
    .log-page-controls a, .log-page-controls span {
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
    .log-page-controls a:hover {
        background: #f5ebeb;
        border-color: #d4aeae;
    }
    .log-page-controls span.active {
        background: #5B1A1A;
        color: #fff;
        border-color: #5B1A1A;
        font-weight: 700;
    }
    .log-page-controls span.disabled {
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

<div class="log-wrapper">
    <div class="log-stats-grid">
        <div class="log-stat-card">
            <div class="log-icon-box log-icon-blue"><i class="fas fa-wave-square"></i></div>
            <div class="log-stat-text">
                <h4>Total Aktivitas</h4>
                <h2>{{ $totalaktivitas ?? 0 }}</h2>
            </div>
        </div>
        <div class="log-stat-card">
            <div class="log-icon-box log-icon-green"><i class="fas fa-plus-circle"></i></div>
            <div class="log-stat-text">
                <h4>Tambah</h4>
                <h2>{{ $tambahaktivitas ?? 0 }}</h2>
            </div>
        </div>
        <div class="log-stat-card">
            <div class="log-icon-box log-icon-yellow"><i class="fas fa-edit"></i></div>
            <div class="log-stat-text">
                <h4>Edit</h4>
                <h2>{{ $editaktivitas ?? 0 }}</h2>
            </div>
        </div>
        <div class="log-stat-card">
            <div class="log-icon-box log-icon-red"><i class="fas fa-trash"></i></div>
            <div class="log-stat-text">
                <h4>Hapus</h4>
                <h2>{{ $hapusaktivitas ?? 0 }}</h2>
            </div>
        </div>
    </div>

    {{-- INTEGRATED FORM ACTION GET FOR ACTIVITY LOGS --}}
    <form method="GET" action="{{ route('superadmin.log-aktivitas') }}" id="formFilterLog">
        <div class="log-search-row">
            <div class="log-search-box">
                <input type="text" name="search" id="searchLog" placeholder="Cari berdasarkan aktivitas, nama login/aktor..." value="{{ request('search') }}">
                <i class="fas fa-search" onclick="submitFormFilter()"></i>
            </div>
            <button type="button" class="log-btn-filter" onclick="openFilterModal()">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>

        {{-- FILTER MODAL POPUP DI DALAM FORM --}}
        <div id="filterModal" class="custom-modal-overlay">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h3>Filter Pencarian anda</h3>
                    <p>Sesuaikan apa yang ingin anda tampilkan</p>
                </div>
                <hr class="modal-divider">

                <div class="custom-modal-body">
                    <div class="filter-group-section">
                        <label class="section-title">Jenis Operasi Data</label>
                        <div class="checkbox-grid">
                            <label class="filter-checkbox-card {{ in_array('menambahkan', request('tipe', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="tipe[]" value="menambahkan" onchange="syncCardVisual(this)" {{ in_array('menambahkan', request('tipe', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Tambah Data</span>
                            </label>
                            <label class="filter-checkbox-card {{ in_array('memperbarui', request('tipe', [])) ? 'selected-box' : '' }}">
                                <input type="checkbox" name="tipe[]" value="memperbarui" onchange="syncCardVisual(this)" {{ in_array('memperbarui', request('tipe', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Edit Data</span>
                            </label>
                            <label class="filter-checkbox-card {{ in_array('menghapus', request('tipe', [])) ? 'selected-box' : '' }}" style="grid-column: span 2;">
                                <input type="checkbox" name="tipe[]" value="menghapus" onchange="syncCardVisual(this)" {{ in_array('menghapus', request('tipe', [])) ? 'checked' : '' }}> 
                                <span class="checkbox-label">Hapus Data</span>
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

    <div class="log-table-card">
        <h3>Daftar Aktivitas</h3>
        <table class="log-table" id="logTable">
            <thead>
                <tr>
                    <th>Aktivitas</th>
                    <th>Aktor</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs ?? [] as $log)
                <tr>
                    <td>{{ $log->aktivitas ?? 'Menambahkan Data' }}</td>
                    <td><strong style="color:#000;">{{ $log->aktor ?? ($log->user->name ?? 'Login 1') }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('l/j/F-H:i:s') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center">Belum ada aktivitas tercatat yang sesuai kriteria.</td></tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- UNIFIED PAGINATION SYSTEM LOGIC --}}
        <div class="log-pagination">
            @if(isset($logs) && $logs instanceof \Illuminate\Pagination\LengthAwarePaginator && $logs->total() > 0)
                <p>Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas</p>
            @else
                <p>Menampilkan 0 aktivitas</p>
            @endif
            
            <div class="log-page-controls">
                @if (isset($logs) && $logs instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    @if ($logs->onFirstPage())
                        <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    <span class="active">{{ $logs->currentPage() }}</span>

                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                    @endif
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
        document.getElementById('formFilterLog').submit();
    }

    document.getElementById('searchLog').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitFormFilter();
        }
    });
</script>
@endsection