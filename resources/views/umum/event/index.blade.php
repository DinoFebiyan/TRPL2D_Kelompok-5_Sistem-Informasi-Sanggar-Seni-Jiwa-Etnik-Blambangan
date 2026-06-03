@extends('layouts.umum')

@section('title', 'Kalender & Event - Sanggar Tari JEB')

@push('styles')
<style>
    /* ══ RESET & GLOBAL BODY ELEMENT ══ */
    body {
        background-color: #ffffff;
        margin: 0;
        padding: 0;
    }

    /* ══ 1. HERO BANNER ATAS ══ */
    .event-banner {
        width: 100%;
        height: 380px;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ asset('img/images-landing-page/banner_berita.jpeg') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 8%;
        color: white;
    }

    .badge-dokumentasi {
        background-color: #C0392B;
        color: white;
        padding: 6px 16px;
        border-radius: 4px;
        width: fit-content;
        margin-bottom: 15px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ══ 2. LAYOUT GRID UTAMA ══ */
    .event-container {
        width: 90%;
        max-width: 1400px;
        margin: 60px auto;
        display: grid;
        grid-template-columns: 35% 60%;
        gap: 5%;
        align-items: start;
    }

    /* ══ 3. KALENDER (SISI KIRI) ══ */
    .calendar-card {
        background: #ffffff;
        border: 1px solid #EAEAEA;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.02);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .calendar-btn {
        background: none;
        border: 1px solid #EAEAEA;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        cursor: pointer;
        color: #666;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .calendar-btn:hover {
        background: #F5F5F5;
    }

    #calendar-month-year {
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
    }

    .calendar-days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 15px 10px;
        text-align: center;
        font-family: 'Poppins', sans-serif;
    }

    .day-name {
        font-size: 0.85rem;
        color: #999;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .day-number {
        font-size: 0.95rem;
        color: #333;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: pointer;
        border-radius: 8px;
    }

    .day-number.disabled {
        color: #DDD;
        cursor: default;
    }

    .day-number.today {
        background: #000000;
        color: #ffffff;
        font-weight: 700;
    }

    .day-number.has-event::after {
        content: '';
        position: absolute;
        bottom: 4px;
        width: 5px;
        height: 5px;
        background-color: #D4AF37;
        border-radius: 50%;
    }

    .day-number.today.has-event::after {
        background-color: #ffffff;
    }

    .calendar-legend {
        margin-top: 30px;
        border-top: 1px solid #F0F0F0;
        padding-top: 20px;
        display: flex;
        gap: 20px;
        font-size: 0.8rem;
        color: #666;
        font-family: 'Poppins', sans-serif;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ══ 4. SEARCH & FILTER INTEGRATION (PENGGANTI TITLE ASLI) ══ */
    .search-filter-wrapper {
        background: transparent;
        padding-bottom: 25px;
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .search-box-container {
        flex: 1;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border-radius: 10px;
        border: 1px solid #EAEAEA;
        background: white;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        outline: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.01);
    }

    .search-box-container .fa-search {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #C0392B;
        cursor: pointer;
        font-size: 1.1rem;
    }

    .btn-trigger-filter {
        padding: 15px 30px;
        background: #C0392B;
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .btn-trigger-filter:hover {
        background: #A93226;
    }

    /* ══ 5. MODAL POPUP DI SISI UMUM ══ */
    .custom-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    .custom-modal-content {
        background: #fff;
        width: 100%;
        max-width: 480px;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        animation: popupSlightIn 0.3s ease-out;
    }
    .custom-modal-header h3 { margin: 0; font-size: 1.3rem; font-weight: 700; color: #2D1A1A; font-family: 'Poppins', sans-serif; }
    .custom-modal-header p { margin: 4px 0 0 0; font-size: 0.85rem; color: #888; font-family: 'Poppins', sans-serif; }
    .modal-divider { border: 0; border-top: 1px solid #E5E7EB; margin: 18px 0; }
    .section-title { font-size: 0.95rem; font-weight: 600; color: #333; display: block; margin-bottom: 12px; font-family: 'Poppins', sans-serif;}
    
    .checkbox-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .filter-checkbox-card {
        border: 1px solid #D1D5DB; border-radius: 12px; padding: 12px 15px;
        display: flex; align-items: center; gap: 10px; cursor: pointer;
        transition: all 0.2s; background: #fff; user-select: none; font-family: 'Poppins', sans-serif;
    }
    .filter-checkbox-card:hover { border-color: #b07d7d; background: #fffbfb; }
    .filter-checkbox-card input[type="checkbox"] { accent-color: #C0392B; width: 17px; height: 17px; cursor: pointer; }
    .filter-checkbox-card.selected-box { border-color: #C0392B; background-color: #FFF5F5; }
    .filter-checkbox-card.selected-box .checkbox-label { font-weight: 600; color: #C0392B; }
    .checkbox-label { font-size: 0.85rem; color: #4B5563; cursor: pointer; }
    
    .custom-modal-footer { display: flex; gap: 15px; margin-top: 25px; }
    .btn-modal-batal, .btn-modal-terapkan {
        flex: 1; padding: 12px; border-radius: 10px; font-family: 'Poppins', sans-serif;
        font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: 0.2s;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .btn-modal-batal { background: #E5E7EB; color: #374151; }
    .btn-modal-batal:hover { background: #D1D5DB; }
    .btn-modal-terapkan { background: #C0392B; color: white; }
    .btn-modal-terapkan:hover { background: #A93226; }

    /* ══ 6. LIST CARD EVENT ══ */
    .event-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .event-item-card {
        background: #ffffff;
        border: 1px solid #EAEAEA;
        border-radius: 16px;
        display: flex;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .event-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.06);
    }

    .event-date-box {
        background: #3D0C0C;
        color: white;
        width: 100px;
        min-width: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 20px;
        font-family: 'Poppins', sans-serif;
    }

    .event-date-box .day {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    .event-date-box .month {
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
        margin-top: 5px;
    }

    .event-details-box {
        padding: 25px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-grow: 1;
    }

    .event-details-box h4 {
        margin: 0 0 12px 0;
        font-size: 1.25rem;
        color: #111;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
    }

    .event-meta-info {
        display: flex;
        gap: 25px;
        font-size: 0.85rem;
        color: #555555;
        font-family: 'Poppins', sans-serif;
        align-items: center;
    }

    .event-meta-info span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ══ 7. PAGINASI INTERAKTIF UMUM ══ */
    .news-pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 35px; font-family: 'Poppins', sans-serif; }
    .news-pagination p { font-size: 0.85rem; color: #7f8c8d; margin: 0; font-weight: 500; }
    .news-page-controls { display: flex; gap: 8px; }
    .news-page-controls a, .news-page-controls span {
        display: inline-flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 8px; border: 1px solid #e8dede;
        background: #fff; color: #333; font-size: 0.85rem; text-decoration: none; transition: 0.2s;
    }
    .news-page-controls a:hover { background: #FFF5F5; border-color: #C0392B; color: #C0392B; }
    .news-page-controls span.active { background: #C0392B; color: #fff; border-color: #C0392B; font-weight: 700; }
    .news-page-controls span.disabled { color: #ccc; cursor: not-allowed; background: #fdfdfd; }

    @media (max-width: 992px) {
        .event-container {
            grid-template-columns: 1fr;
            gap: 50px;
        }
    }
    @keyframes popupSlightIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>
@endpush

@section('content')

<div class="event-banner">
    <span class="badge-dokumentasi">Dokumentasi</span>
    <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin: 0; font-weight: 700;">Kalender Sanggar</h1>
    <p style="font-size: 1.1rem; margin-top: 10px; color: rgba(255,255,255,0.9);">Momen indah perjalanan seni dan budaya Sanggar Tari Jiwa Etnik Blambangan</p>
</div>

<div class="event-container">
    
    {{-- KALENDER WIDGET (KIRI) --}}
    <div class="calendar-card">
        <div class="calendar-header">
            <button class="calendar-btn" id="prev-month">&lsaquo;</button>
            <span id="calendar-month-year">{{ $currentMonthName ?? 'May' }} {{ $currentYear ?? '2026' }}</span>
            <button class="calendar-btn" id="next-month">&rsaquo;</button>
        </div>

        <div class="calendar-days-grid" id="calendar-days">
            <div class="day-name">Su</div>
            <div class="day-name">Mo</div>
            <div class="day-name">Tu</div>
            <div class="day-name">We</div>
            <div class="day-name">Th</div>
            <div class="day-name">Fr</div>
            <div class="day-name">Sa</div>

            @for($i = 0; $i < ($blankDays ?? 0); $i++)
                <div class="day-number disabled"></div>
            @endfor

            @for($day = 1; $day <= ($daysInMonth ?? 31); $day++)
                @php
                    $isEventDay = in_array($day, $eventDays ?? []);
                    $isToday = ($day == date('d') && ($currentMonth ?? date('m')) == date('m')); 
                @endphp
                <div class="day-number {{ $isToday ? 'today' : '' }} {{ $isEventDay ? 'has-event' : '' }}">
                    {{ $day }}
                </div>
            @endfor
        </div>

        <div class="calendar-legend">
            <div class="legend-item">
                <span style="width: 6px; height: 6px; background: #D4AF37; border-radius: 50%;"></span> Tanggal dengan acara
            </div>
            <div class="legend-item">
                <span style="width: 8px; height: 8px; background: #000; border-radius: 50%;"></span> Hari ini
            </div>
        </div>
    </div>

    {{-- GRUP PENCARIAN & DAFTAR EVENT (KANAN) --}}
    <div>
        {{-- IMPLEMENTASI BAR PENCARIAN & FILTER POPUP --}}
        <form action="{{ route('umum.event') }}" method="GET" id="formPublicEvent">
            <div class="search-filter-wrapper">
                <div class="search-box-container">
                    <input type="text" name="search" id="searchPublicEvent" class="search-input" placeholder="Cari nama event, lokasi agenda kegiatan..." value="{{ request('search') }}">
                    <i class="fas fa-search" onclick="submitPublicForm()"></i>
                </div>
                <button type="button" class="btn-trigger-filter" onclick="openFilterModal()">
                    <i class="fas fa-filter"></i> Status Event
                </button>
            </div>

            {{-- MULTI-SELECT STATUS EVENT CHECKBOX POPUP --}}
            <div id="filterModal" class="custom-modal-overlay">
                <div class="custom-modal-content">
                    <div class="custom-modal-header">
                        <h3>Filter Status Kegiatan</h3>
                        <p>Saring agenda event berdasarkan kondisi pelaksanaan</p>
                    </div>
                    <hr class="modal-divider">

                    <div class="custom-modal-body">
                        <div class="filter-group-section">
                            <label class="section-title">Status Pelaksanaan</label>
                            <div class="checkbox-grid">
                                <label class="filter-checkbox-card {{ in_array('belum selesai', request('status', [])) ? 'selected-box' : '' }}">
                                    <input type="checkbox" name="status[]" value="belum selesai" onchange="syncCardVisual(this)" {{ in_array('belum selesai', request('status', [])) ? 'checked' : '' }}> 
                                    <span class="checkbox-label">Belum Selesai</span>
                                </label>
                                <label class="filter-checkbox-card {{ in_array('selesai', request('status', [])) ? 'selected-box' : '' }}">
                                    <input type="checkbox" name="status[]" value="selesai" onchange="syncCardVisual(this)" {{ in_array('selesai', request('status', [])) ? 'checked' : '' }}> 
                                    <span class="checkbox-label">Selesai</span>
                                </label>
                                <label class="filter-checkbox-card {{ in_array('berlangsung', request('status', [])) ? 'selected-box' : '' }}">
                                    <input type="checkbox" name="status[]" value="berlangsung" onchange="syncCardVisual(this)" {{ in_array('berlangsung', request('status', [])) ? 'checked' : '' }}> 
                                    <span class="checkbox-label">Berlangsung</span>
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

        {{-- LIST ELEMENT HASIL AGENDA EVENT --}}
        <div class="event-list">
            @forelse($events as $event)
                <a href="{{ route('umum.event-detail', $event->id) }}" class="event-item-card">
                    <div class="event-date-box">
                        <span class="day">{{ \Carbon\Carbon::parse($event->tanggal)->format('d') }}</span>
                        <span class="month">{{ \Carbon\Carbon::parse($event->tanggal)->format('M') }}</span>
                    </div>
                    
                    <div class="event-details-box">
                        <h4>{{ $event->nama_event }}</h4>
                        
                        <div class="event-meta-info">
                            <span>📍 {{ $event->lokasi ?? 'Lokasi belum ditentukan' }}</span>
                            <span>🕒 {{ \Carbon\Carbon::parse($event->jam)->format('H:i') }} WIB</span>
                        </div>
                    </div>
                </a>
            @empty
                <div style="text-align: center; padding: 50px; color: #888; border: 1px dashed #DDD; border-radius: 16px; font-family: 'Poppins', sans-serif;">
                    <i class="far fa-calendar-times" style="font-size: 2.5rem; color: #ccc; margin-bottom: 12px; display: block;"></i>
                    Tidak ada agenda atau event yang sesuai dengan kriteria pencarian saat ini.
                </div>
            @endforelse
        </div>

        {{-- NAVIGASI LINK PAGINASI DINAMIS SISI PUBLIK --}}
        <div class="news-pagination">
            @if($events->total() > 0)
                <p>Menampilkan {{ $events->firstItem() }} - {{ $events->lastItem() }} dari {{ $events->total() }} event</p>
            @else
                <p>Menampilkan 0 event</p>
            @endif
            
            <div class="news-page-controls">
                @if($events->hasPages())
                    {{-- Sebelumnya --}}
                    @if ($events->onFirstPage())
                        <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $events->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif

                    {{-- Loop range nomor halaman --}}
                    @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                        @if ($page == $events->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Selanjutnya --}}
                    @if ($events->hasMorePages())
                        <a href="{{ $events->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
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

    function submitPublicForm() {
        document.getElementById('formPublicEvent').submit();
    }

    document.getElementById('searchPublicEvent').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitPublicForm();
        }
    });
</script>
@endsection