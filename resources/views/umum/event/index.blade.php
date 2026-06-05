@extends('layouts.umum')

@section('title', 'Kalender & Event - Sanggar Tari JEB')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>
    /* ══ GLOBAL PUBLIC WRAPPER ══ */
    .public-page-wrapper {
        background-color: #f4eae6;
        width: 100%;
        min-height: 100vh;
        position: relative;
        padding-bottom: 80px;
    }

    /* ══ HERO BANNER ATAS ══ */
    .berita-banner-container {
        width: 100%;
        height: 380px;
        position: relative;
        overflow: hidden;
        background: #333;
    }

    .banner-slideshow-wrapper {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: 1;
    }

    .banner-slideshow-wrapper img, 
    .banner-slideshow-wrapper .banner-error-fallback {
        width: 100%; height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
    }

    .banner-slideshow-wrapper img.active,
    .banner-slideshow-wrapper .banner-error-fallback.active {
        opacity: 1;
    }

    .banner-overlay-dark {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4));
        z-index: 2;
    }

    .berita-banner-content {
        position: relative;
        z-index: 3;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 8%;
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    .banner-error-fallback {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #2c3e50;
    }
    .banner-error-fallback.active { 
        display: flex; 
    }

    .badge-theme {
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

    /* ══ CONTENT LAYOUT WRAPPER ══ */
    .event-wrapper {
        width: 90%;
        max-width: 1300px;
        margin: 40px auto;
        font-family: 'Poppins', sans-serif;
    }

    .event-split-layout {
        display: grid;
        grid-template-columns: 38% 57%;
        gap: 5%;
        align-items: start;
        margin-top: 15px;
    }

    /* ══ KUSTOMISASI KALENDER ══ */
    .calendar-card {
        background: #ffffff;
        border: 1px solid #f0f0f0;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        height: auto;
    }

    .fc { font-family: 'Poppins', sans-serif; --fc-border-color: #f1f1f1; }
    .fc .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 600; color: #333; }
    .fc .fc-button-primary { background-color: #ffffff !important; border: 1px solid #eaeaea !important; color: #666 !important; }
    .fc .fc-button-primary:hover { background-color: #f5f5f5 !important; color: #333 !important; }
    .fc .fc-col-header-cell-cushion, .fc .fc-daygrid-day-number { color: #333; text-decoration: none !important; font-size: 0.85rem; }
    
    .fc-daygrid-event-harness, .fc-event, .fc-daygrid-event { display: none !important; }

    .fc .fc-daygrid-day-top {
        justify-content: center !important;
        align-items: center !important;
        padding: 4px 0;
    }

    .fc .fc-day-today { background: none !important; }

    .fc .fc-daygrid-day-number {
        display: flex !important;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .fc-day-today-custom .fc-daygrid-day-number {
        background-color: #000000 !important;
        color: #ffffff !important;
        font-weight: bold;
    }

    .fc-day-has-event-future {
        cursor: pointer;
    }
    .fc-day-has-event-future .fc-daygrid-day-number {
        background-color: #C0392B !important;
        color: #ffffff !important;
        font-weight: 600;
    }
    .fc-day-has-event-future:hover .fc-daygrid-day-number {
        background-color: #A93226 !important;
    }

    .fc-day-has-event-past {
        cursor: pointer;
    }
    .fc-day-has-event-past .fc-daygrid-day-number {
        background-color: #D4AF37 !important;
        color: #ffffff !important;
        font-weight: 600;
    }
    .fc-day-has-event-past:hover .fc-daygrid-day-number {
        background-color: #C5A028 !important;
    }

    /* ══ SEARCH & FILTER BAR ══ */
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
        border: 1px solid #ddd;
        background: white;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        outline: none;
    }

    .search-box-container .fa-search {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #C0392B;
        cursor: pointer;
    }

    .btn-trigger-filter {
        padding: 15px 35px;
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
        box-shadow: 0 2px 10px rgba(192, 57, 43, 0.2);
    }
    .btn-trigger-filter:hover { background: #A93226; }

    /* ══ FILTER POPUP MODAL STYLES ══ */
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

    /* ══ LIST EVENT ITEM CARDS ══ */
    .event-list { display: flex; flex-direction: column; gap: 20px; }
    
    .event-item-card {
        background: #ffffff;
        border: 1px solid #f0f0f0;
        border-radius: 16px;
        display: flex;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .event-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .event-slideshow-box {
        width: 180px;
        min-width: 180px;
        height: auto;
        position: relative;
        background-color: #f3f4f6;
        overflow: hidden;
    }

    .event-slideshow-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        z-index: 1;
    }

    .event-slideshow-box img.active {
        opacity: 1;
        position: relative;
    }

    .event-details-box {
        padding: 25px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-grow: 1;
        background: #fff;
    }
    
    .event-details-box h4 {
        margin: 0 0 8px 0;
        font-size: 1.25rem;
        color: #111;
        font-weight: 700;
        line-height: 1.4;
    }

    .event-desc-excerpt {
        font-size: 0.88rem;
        color: #666;
        line-height: 1.5;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .event-meta-info {
        display: flex;
        flex-wrap: wrap;
        gap: 12px 20px;
        font-size: 0.8rem;
        color: #555555;
        align-items: center;
        border-top: 1px solid #f0f0f0;
        padding-top: 12px;
    }
    .event-meta-info span { display: inline-flex; align-items: center; gap: 5px; }

    /* LEGENDA KALENDER */
    .calendar-legend {
        display: flex;
        gap: 15px;
        margin-top: 15px;
        font-size: 0.8rem;
        flex-wrap: wrap;
    }
    .legend-item { display: flex; align-items: center; gap: 6px; color: #555; }

    /* INDIKATOR HEADER FILTER TANGGAL AKTIF */
    #header-filter-tanggal { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    #header-filter-tanggal h3 { font-size: 1.1rem; margin: 0; color: #6b1414; font-weight: 600; }
    #btn-reset-filter { font-size: 0.85rem; color: #C0392B; text-decoration: underline; background: none; border: none; cursor: pointer; }

    /* ══ PAGINATION BAR ══ */
    .news-pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 35px; }
    .news-pagination p { font-size: 0.85rem; color: #555; margin: 0; font-weight: 500; }
    .news-page-controls { display: flex; gap: 8px; }
    .news-page-controls a, .news-page-controls span {
        display: inline-flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 8px; border: 1px solid #c8bcba;
        background: #fff; color: #333; font-size: 0.85rem; text-decoration: none;
    }
    .news-page-controls span.active { background: #C0392B; color: #fff; font-weight: 700; }
    .news-page-controls span.disabled { color: #ccc; cursor: not-allowed; }

    .inline-error-fallback {
        width: 100%; height: 100%; display: none; flex-direction: column;
        align-items: center; justify-content: center; background: #e5e7eb; color: #9ca3af;
        position: absolute; top: 0; left: 0; z-index: 2;
    }
    .inline-error-fallback.active { display: flex; }

    @media (max-width: 992px) { 
        .event-split-layout { grid-template-columns: 1fr; gap: 40px; } 
        .event-item-card { flex-direction: column; }
        .event-slideshow-box { width: 100%; height: 200px; }
    }
    @keyframes popupSlightIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<div class="public-page-wrapper">
    {{-- Hero Banner Slider --}}
    <div class="berita-banner-container">
        <div class="banner-slideshow-wrapper header-slideshow-container">
            @if(isset($profil) && $profil->headers && $profil->headers->isNotEmpty())
                @foreach($profil->headers as $index => $headerFoto)
                    <img src="{{ asset('storage/' . $headerFoto->path) }}" alt="Header Sanggar JEB" class="{{ $index === 0 ? 'active' : '' }}" onerror="this.style.display='none'; document.getElementById('header-fallback').classList.add('active');">
                @endforeach
                <div id="header-fallback" class="banner-error-fallback">
                    <i class="fas fa-image" style="font-size: 3.5rem; color: rgba(255,255,255,0.3); margin-bottom: 8px;"></i>
                    <span style="font-size: 0.85rem; color: rgba(255,255,255,0.5); font-weight: 500;">Gagal Memuat Gambar Banner</span>
                </div>
            @else
                <div class="banner-error-fallback active">
                    <i class="fas fa-image" style="font-size: 3.5rem; color: rgba(255,255,255,0.3); margin-bottom: 8px;"></i>
                    <span style="font-size: 0.85rem; color: rgba(255,255,255,0.5); font-weight: 500;">Gagal Memuat Gambar Banner</span>
                </div>
            @endif
        </div>
        <div class="banner-overlay-dark"></div>
        <div class="berita-banner-content">
            <span class="badge-theme">Dokumentasi</span>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin: 0; font-weight: 700;">Kalender Sanggar</h1>
            <p style="font-size: 1.1rem; margin-top: 10px; color: rgba(255,255,255,0.9);">Momen indah perjalanan seni dan budaya Sanggar Tari Jiwa Etnik Blambangan</p>
        </div>
    </div>

    <div class="event-wrapper">
        <div class="section-header" style="margin-bottom: 15px;">
            <h2 style="font-family: 'Playfair Display', serif; font-weight: 700; color: #6b1414; font-size: 1.8rem; margin-bottom: 5px;">Agenda & Jadwal Kegiatan</h2>
        </div>

        <div class="event-split-layout">
            {{-- KIRI: Kalender --}}
            <div class="calendar-card">
                <div id="calendar-box"></div>
                <div class="calendar-legend">
                    <div class="legend-item"><span style="width: 14px; height: 14px; background: #000000; border-radius: 4px; display: inline-block;"></span> Hari Ini</div>
                    <div class="legend-item"><span style="width: 14px; height: 14px; background: #C0392B; border-radius: 4px; display: inline-block;"></span> Tanggal Event</div>
                </div>
            </div>

            {{-- KANAN: List Kartu Event --}}
            <div>
                {{-- Form Pencarian dan Filter Pop-up --}}
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

                    {{-- Modal Filter Pop-up Status --}}
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
                                        @foreach(['belum selesai', 'selesai'] as $status)
                                            <label class="filter-checkbox-card {{ in_array($status, request('status', [])) ? 'selected-box' : '' }}">
                                                <input type="checkbox" name="status[]" value="{{ $status }}" onchange="syncCardVisual(this)" {{ in_array($status, request('status', [])) ? 'checked' : '' }}> 
                                                <span class="checkbox-label" style="text-transform: capitalize;">{{ $status }}</span>
                                            </label>
                                        @endforeach
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

                {{-- Judul Penanda saat Filter Tanggal Aktif --}}
                <div id="header-filter-tanggal" style="display: none;">
                    <h3>Menampilkan Acara Tanggal: <span id="label-tanggal-pilihan">-</span></h3>
                    <a href="{{ route('umum.event') }}" class="{{ request()->routeIs('umum.event*') ? 'active' : '' }}">Reset Pilihan Tanggal</a>
                </div>

                {{-- Container Utama List Acara --}}
                <div class="event-list" id="event-render-container">
                    @forelse($events as $event)
                        <a href="{{ route('umum.event-detail', $event->id) }}" class="event-item-card">
                            
                            <div class="event-slideshow-box slideshow-container-mini">
                                @if($event->galeri && $event->galeri->isNotEmpty())
                                    @foreach($event->galeri as $index => $fotoEvent)
                                        <img src="{{ asset('storage/' . $fotoEvent->path) }}" 
                                             alt="{{ $event->nama }}" 
                                             class="{{ $index === 0 ? 'active' : '' }}"
                                             onerror="this.style.display='none'; document.getElementById('event-fallback-{{ $event->id }}').classList.add('active');">
                                    @endforeach
                                    <div id="event-fallback-{{ $event->id }}" class="inline-error-fallback">
                                        <i class="fas fa-image" style="font-size: 1.5rem;"></i>
                                    </div>
                                @else
                                    <img src="{{ asset('img/images-landing-page/banner_berita.jpeg') }}" alt="{{ $event->nama }}" class="active">
                                @endif
                            </div>

                            <div class="event-details-box">
                                <div>
                                    <h4>{{ $event->nama }}</h4>
                                    <div class="event-desc-excerpt">{{ strip_tags($event->deskripsi) }}</div>
                                </div>
                                <div class="event-meta-info">
                                    <span><i class="far fa-calendar-alt" style="color: #3D0C0C;"></i> {{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d M Y') }}</span>
                                    <span><i class="fas fa-map-marker-alt" style="color: #3D0C0C;"></i> {{ Str::limit($event->lokasi, 22) }}</span>
                                    <span><i class="far fa-clock" style="color: #3D0C0C;"></i> {{ \Carbon\Carbon::parse($event->jam)->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="empty-state-box" style="text-align: center; padding: 50px 20px; color: #7f8c8d; border: 1px dashed #DDD; border-radius: 16px;">
                            <p style="font-size: 1.1rem; font-weight: 600; margin: 0;">Event belum tersedia</p>
                        </div>
                    @endforelse
                </div>

                {{-- Navigasi Paginasi Bar --}}
                <div class="news-pagination" id="paginasi-container-wrapper">
                    @if($events->total() > 0) 
                        <p>Menampilkan {{ $events->firstItem() }} - {{ $events->lastItem() }} dari {{ $events->total() }} event</p>
                    @else 
                        <p>Menampilkan 0 event</p> 
                    @endif
                    <div class="news-page-controls">
                        @if($events->hasPages())
                            @if ($events->onFirstPage()) <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                            @else <a href="{{ $events->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a> @endif
                            @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                                @if ($page == $events->currentPage()) <span class="active">{{ $page }}</span>
                                @else <a href="{{ $url }}">{{ $page }}</a> @endif
                            @endforeach
                            @if ($events->hasMorePages()) <a href="{{ $events->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                            @else <span class="disabled"><i class="fas fa-chevron-right"></i></span> @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openFilterModal() { document.getElementById('filterModal').style.display = 'flex'; }
    function closeFilterModal() { document.getElementById('filterModal').style.display = 'none'; }
    
    window.onclick = function(e) { 
        if (e.target == document.getElementById('filterModal')) closeFilterModal(); 
    }

    function syncCardVisual(c) { 
        let p = c.closest('.filter-checkbox-card'); 
        if (c.checked) p.classList.add('selected-box'); else p.classList.remove('selected-box'); 
    }

    function submitPublicForm() { document.getElementById('formPublicEvent').submit(); }
    
    document.getElementById('searchPublicEvent').addEventListener('keypress', function(e) { 
        if (e.key === 'Enter') { e.preventDefault(); submitPublicForm(); } 
    });

    const todayStr = new Date().toISOString().split('T')[0];

    // AMBIL DATA ACARA DINAMIS DARI CONTROLLER VIA $allEvents
    const masterEventsDatabase = [
        @if(isset($allEvents))
            @foreach($allEvents as $e)
            {
                id: "{{ $e->id }}",
                title: "{{ $e->nama }}",
                excerpt: "{{ Str::limit(strip_tags($e->deskripsi), 120) }}",
                date: "{{ \Carbon\Carbon::parse($e->tanggal)->format('Y-m-d') }}", 
                formattedDate: "{{ \Carbon\Carbon::parse($e->tanggal)->translatedFormat('d M Y') }}",
                lokasi: "{{ Str::limit($e->lokasi, 22) }}",
                jam: "{{ \Carbon\Carbon::parse($e->jam)->format('H:i') }} WIB",
                urlDetail: "{{ route('umum.event-detail', $e->id) }}",
                galeriHTML: `@if($e->galeri && $e->galeri->isNotEmpty())
                                @foreach($e->galeri as $index => $f)
                                    <img src="{{ asset('storage/' . $f->path) }}" class="{{ $index === 0 ? 'active' : '' }}">
                                @endforeach
                             @else
                                <img src="{{ asset('img/images-landing-page/banner_berita.jpeg') }}" class="active">
                             @endif`
            },
            @endforeach
        @endif
    ];

    const daftarTanggalAcara = masterEventsDatabase.map(item => item.date);

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar-box');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            height: 'auto',
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'prev,next'
            },
            events: masterEventsDatabase,

            dayCellDidMount: function(info) {
                let tahun = info.date.getFullYear();
                let bulan = String(info.date.getMonth() + 1).padStart(2, '0');
                let hari = String(info.date.getDate()).padStart(2, '0');
                let stringTanggalCell = `${tahun}-${bulan}-${hari}`;
                
                if (stringTanggalCell === todayStr) {
                    info.el.classList.add('fc-day-today-custom');
                }

                if (daftarTanggalAcara.includes(stringTanggalCell)) {
                    if (stringTanggalCell < todayStr) {
                        info.el.classList.add('fc-day-has-event-past');
                    } 
                    else {
                        info.el.classList.add('fc-day-has-event-future');
                    }
                }
            },

            dateClick: function(info) {
                const tanggalKlik = info.dateStr; 
                const wadahRender = document.getElementById('event-render-container');
                const wadahPaginasi = document.getElementById('paginasi-container-wrapper');
                const indikatorHeader = document.getElementById('header-filter-tanggal');
                const labelTanggal = document.getElementById('label-tanggal-pilihan');

                const hasilSaring = masterEventsDatabase.filter(ev => ev.date === tanggalKlik);

                const pecahan = tanggalKlik.split('-');
                labelTanggal.textContent = `${pecahan[2]}-${pecahan[1]}-${pecahan[0]}`;
                indikatorHeader.style.display = 'flex';
                wadahPaginasi.style.display = 'none';

                if (hasilSaring.length > 0) {
                    let kartuHTML = '';
                    hasilSaring.forEach(item => {
                        kartuHTML += `
                            <a href="${item.urlDetail}" class="event-item-card">
                                <div class="event-slideshow-box slideshow-container-mini">
                                    ${item.galeriHTML}
                                </div>
                                <div class="event-details-box">
                                    <div>
                                        <h4>${item.title}</h4>
                                        <div class="event-desc-excerpt">${item.excerpt}</div>
                                    </div>
                                    <div class="event-meta-info">
                                        <span><i class="far fa-calendar-alt" style="color: #3D0C0C;"></i> ${item.formattedDate}</span>
                                        <span><i class="fas fa-map-marker-alt" style="color: #3D0C0C;"></i> ${item.lokasi}</span>
                                        <span><i class="far fa-clock" style="color: #3D0C0C;"></i> ${item.jam}</span>
                                    </div>
                                </div>
                            </a>`;
                    });
                    wadahRender.innerHTML = kartuHTML;
                    inisialisasiUlangSlideshowMini();
                } else {
                    wadahRender.innerHTML = `
                        <div class="empty-state-box" style="text-align: center; padding: 50px 20px; color: #7f8c8d; border: 1px dashed #DDD; border-radius: 16px; width: 100%;">
                            <p style="font-size: 1.1rem; font-weight: 600; margin: 0;">Tidak ada acara</p>
                            <p style="font-size: 0.9rem; color: #888; margin-top: 5px;">Tidak ada jadwal agenda kegiatan sanggar pada tanggal ini.</p>
                        </div>`;
                }
            }
        });
        
        calendar.render();

        // Logika Pengatur Slideshow Foto Mini (Interval 5 detik)
        let intervalSlideshowMini;
        function inisialisasiUlangSlideshowMini() {
            if(intervalSlideshowMini) clearInterval(intervalSlideshowMini);
            
            const miniContainers = document.querySelectorAll('.slideshow-container-mini');
            miniContainers.forEach(container => {
                const miniImages = container.querySelectorAll('img');
                if (miniImages.length > 1) {
                    let miniIndex = 0;
                    intervalSlideshowMini = setInterval(() => {
                        miniImages[miniIndex].classList.remove('active');
                        miniIndex = (miniIndex + 1) % miniImages.length;
                        miniImages[miniIndex].classList.add('active');
                    }, 5000);
                }
            });
        }
        
        inisialisasiUlangSlideshowMini();

        // Banner Hero Slideshow 
        const headerContainer = document.querySelector('.header-slideshow-container');
        if (headerContainer) {
            setInterval(() => {
                const slides = headerContainer.querySelectorAll('img:not([style*="display: none"]), .banner-error-fallback.active');
                if (slides.length > 1) {
                    let currentIndex = Array.from(slides).findIndex(el => el.classList.contains('active'));
                    if (currentIndex !== -1) {
                        slides[currentIndex].classList.remove('active');
                        slides[(currentIndex + 1) % slides.length].classList.add('active');
                    }
                }
            }, 7000);
        }
    });
</script>
@endsection