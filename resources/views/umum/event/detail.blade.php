@extends('layouts.umum')

@section('title', $event->nama_event . ' - Sanggar Tari JEB')

@push('styles')
<style>
    body {
        background-color: #ffffff;
        margin: 0;
        padding: 0;
    }

    .detail-event-container {
        max-width: 1300px;
        margin: 50px auto;
        padding: 0 4%;
    }

    .event-main-card {
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        border: 1px solid #EAEAEA;
    }

    .event-image-wrapper {
        position: relative;
        width: 100%;
        height: 520px;
    }

    .event-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .badge-kategori {
        position: absolute;
        top: 25px;
        left: 35px;
        background-color: #D4AF37;
        color: #3D0C0C;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 6px;
        text-transform: uppercase;
    }

    .event-info-body {
        padding: 40px 45px;
    }

    .event-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 30px;
        margin-bottom: 20px;
    }

    .event-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: #111111;
        font-weight: 700;
        margin: 0;
    }

    .event-time-place {
        text-align: right;
        font-family: 'Poppins', sans-serif;
        color: #333333;
        font-size: 1rem;
        line-height: 1.6;
        min-width: 250px;
    }

    .event-time-place .date {
        font-weight: 700;
        color: #111111;
        font-size: 1.1rem;
    }

    .event-description {
        font-family: 'Poppins', sans-serif;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #555555;
        margin-top: 15px;
        text-align: justify;
    }

    /* ══ SECTION EVENT LAIN (BAWAH) ══ */
    .event-lain-section {
        background-color: #E7DCDA;
        padding: 6rem 8%;
    }

    .event-lain-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.6rem;
        color: #3D0C0C;
        font-weight: 800;
        margin: 0 0 35px 0;
    }

    .event-lain-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }

    .lain-card {
        background: #ffffff;
        border-radius: 16px;
        display: flex;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform 0.3s;
    }

    .lain-card:hover {
        transform: translateY(-4px);
    }

    .lain-date-box {
        background: #3D0C0C;
        color: white;
        width: 95px;
        min-width: 95px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        font-family: 'Poppins', sans-serif;
    }

    .lain-date-box .day {
        font-size: 1.8rem;
        font-weight: 700;
    }

    .lain-date-box .month {
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-top: 5px;
    }

    .lain-details-box {
        padding: 25px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex-grow: 1;
    }

    .lain-details-box h4 {
        margin: 0 0 10px 0;
        font-size: 1.2rem;
        color: #111;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
    }

    .lain-meta-info {
        display: flex;
        gap: 20px;
        font-size: 0.82rem;
        color: #555;
        font-family: 'Poppins', sans-serif;
    }

    @media (max-width: 992px) {
        .event-image-wrapper { height: 380px; }
        .event-header-row { flex-direction: column; gap: 15px; }
        .event-time-place { text-align: left; }
        .event-lain-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div class="detail-event-container">
    <div class="event-main-card">
        
        <div class="event-image-wrapper">
            @php
                $gambarPath = 'img/images-landing-page/banner_berita.jpeg';
                if ($event->galeri) {
                    $gambarPath = $event->galeri instanceof \Illuminate\Support\Collection 
                        ? ($event->galeri->first()->path ?? $gambarPath)
                        : ($event->galeri->path ?? $gambarPath);
                }
            @endphp
            <img src="{{ asset($gambarPath) }}" alt="{{ $event->nama_event }}">
            <span class="badge-kategori">{{ $event->kategori }}</span>
        </div>

        <div class="event-info-body">
            <div class="event-header-row">
                <h1 class="event-title">{{ $event->nama_event }}</h1>
                <div class="event-time-place">
                    <div class="date">{{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}</div>
                    <div>{{ $event->lokasi ?? 'Lokasi belum ditentukan' }}</div>
                    <div style="font-size: 0.9rem; color: #666; margin-top: 4px;">
                        🕒 {{ \Carbon\Carbon::parse($event->jam)->format('H:i') }} WIB
                    </div>
                </div>
            </div>
            
            <div class="event-description">
                {!! $event->deskripsi !!}
            </div>
        </div>

    </div>
</div>

<section class="event-lain-section">
    <h3 class="event-lain-title">Event lain</h3>
    
    <div class="event-lain-grid">
        @forelse($eventLainnya as $lain)
            <a href="{{ route('umum.event-detail', $lain->id) }}" class="lain-card">
                <div class="lain-date-box">
                    <span class="day">{{ \Carbon\Carbon::parse($lain->tanggal)->format('d') }}</span>
                    <span class="month">{{ \Carbon\Carbon::parse($lain->tanggal)->format('M') }}</span>
                </div>
                
                <div class="lain-details-box">
                    <h4>{{ $lain->nama_event }}</h4>
                    <div class="lain-meta-info">
                        <span>📍 {{ Str::limit($lain->lokasi, 25) }}</span>
                        <span>🕒 {{ \Carbon\Carbon::parse($lain->jam)->format('H:i') }} WIB</span>
                    </div>
                </div>
            </a>
        @empty
            <p style="grid-column: 1/-1; text-align: center; color: #666; font-family: 'Poppins', sans-serif;">
                Tidak ada event lainnya saat ini.
            </p>
        @endforelse
    </div>
</section>

@endsection