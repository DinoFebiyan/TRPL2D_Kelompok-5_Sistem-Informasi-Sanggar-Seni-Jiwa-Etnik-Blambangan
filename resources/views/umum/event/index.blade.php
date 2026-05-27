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

    /* ══ 4. LIST EVENT (SISI KANAN) ══ */
    .event-section-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.4rem;
        color: #3D0C0C;
        font-weight: 700;
        margin: 0 0 30px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .event-count-badge {
        font-size: 0.8rem;
        background: #F5F5F5;
        color: #666;
        padding: 5px 14px;
        border-radius: 20px;
        font-family: 'Poppins', sans-serif;
        border: 1px solid #EAEAEA;
    }

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

    @media (max-width: 992px) {
        .event-container {
            grid-template-columns: 1fr;
            gap: 50px;
        }
    }
</style>
@endpush

@section('content')

<div class="event-banner">
    <span class="badge-dokumentasi">Dokumentasi</span>
    <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin: 0; font-weight: 700;">Kalender Sanggar</h1>
    <p style="font-size: 1.1rem; margin-top: 10px; color: rgba(255,255,255,0.9);">Momen indah perjalanan seni dan budaya Sanggar Tari Jiwa Etnik Blambangan</p>
</div>

<div class="event-container">
    
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

    <div>
        <h3 class="event-section-title">
            Event Kami 
            <span class="event-count-badge">{{ $events->count() }} event</span>
        </h3>

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
                    Belum ada agenda atau event yang terdaftar saat ini.
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection