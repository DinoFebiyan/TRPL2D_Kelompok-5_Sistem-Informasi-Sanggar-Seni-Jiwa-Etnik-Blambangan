{{-- Pop Up: Login Diperlukan --}}
{{-- Cara pakai: tambahkan @include('popups.akses') di blade manapun --}}
{{-- Trigger tampil: panggil showPopupAkses() --}}

<!-- Overlay Pop Up Login Required -->
<div id="popup-akses" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 24px;
    font-family: 'Plus Jakarta Sans', sans-serif;
">
    <div style="
        background: #EFF6FF;
        border-radius: 16px;
        padding: 36px 28px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        width: 100%;
        max-width: 420px;
        text-align: center;
        animation: popupIn 0.25s ease;
    ">
        {{-- Icon --}}
        <div style="
            width: 72px; height: 72px;
            border-radius: 50%;
            background: #BFDBFE;
            color: #1E88E5;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
        ">🔒</div>

        {{-- Label --}}
        <p style="
            font-size: 11px; font-weight: 800;
            letter-spacing: .14em; text-transform: uppercase;
            color: #475569; margin-bottom: 8px;
        ">Login Required</p>

        {{-- Judul --}}
        <h2 style="
            font-size: 18px; font-weight: 800;
            color: #1E88E5; margin-bottom: 10px;
        ">Login Diperlukan</h2>

        {{-- Pesan --}}
        <p style="
            font-size: 13.5px; color: #475569;
            line-height: 1.65; margin-bottom: 24px;
        ">Silakan login untuk mengakses halaman ini.</p>

        {{-- Tombol Login --}}
        <a href="{{ route('login') }}" style="
            display: block; width: 100%;
            padding: 13px;
            border: none; border-radius: 10px;
            background: #1565C0; color: #fff;
            font-size: 14.5px; font-weight: 700;
            font-family: inherit; cursor: pointer;
            margin-bottom: 14px;
            text-decoration: none;
            transition: filter .2s, transform .1s;
        "
        onmouseover="this.style.filter='brightness(1.1)'"
        onmouseout="this.style.filter='brightness(1)'"
        onmousedown="this.style.transform='scale(.98)'"
        onmouseup="this.style.transform='scale(1)'">
            Login
        </a>

        {{-- Link Kembali --}}
        <a onclick="hidePopupAkses()" style="
            display: block;
            color: #1E88E5; font-size: 13.5px; font-weight: 600;
            cursor: pointer; text-decoration: none;
        "
        onmouseover="this.style.textDecoration='underline'"
        onmouseout="this.style.textDecoration='none'">
            Kembali
        </a>
    </div>
</div>

@push('scripts')
<script>
    function showPopupAkses() {
        var el = document.getElementById('popup-akses');
        el.style.display = 'flex';
    }
    function hidePopupAkses() {
        var el = document.getElementById('popup-akses');
        el.style.display = 'none';
    }
    // Tutup saat klik overlay
    document.getElementById('popup-akses').addEventListener('click', function(e) {
        if (e.target === this) hidePopupAkses();
    });
</script>
@endpush

@push('styles')
<style>
    @keyframes popupIn {
        from { opacity: 0; transform: scale(0.92) translateY(16px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
</style>
@endpush