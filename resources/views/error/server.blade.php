{{-- Pop Up: Server Error --}}
{{-- Cara pakai: tambahkan @include('popups.server') di blade manapun --}}
{{-- Trigger tampil: tambahkan class 'show' ke #popup-server, atau panggil showPopupServer() --}}

<!-- Overlay Pop Up Server Error -->
<div id="popup-server" style="
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
        background: #FFF5F5;
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
            background: #FECACA;
            color: #E53935;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px; font-weight: 700;
        ">✕</div>

        {{-- Label --}}
        <p style="
            font-size: 11px; font-weight: 800;
            letter-spacing: .14em; text-transform: uppercase;
            color: #475569; margin-bottom: 8px;
        ">Server Error</p>

        {{-- Judul --}}
        <h2 style="
            font-size: 18px; font-weight: 800;
            color: #E53935; margin-bottom: 10px;
        ">Terjadi Kesalahan Server</h2>

        {{-- Pesan --}}
        <p style="
            font-size: 13.5px; color: #475569;
            line-height: 1.65; margin-bottom: 24px;
        ">Kami mengalami kendala. Silakan coba lagi beberapa saat.</p>

        {{-- Tombol --}}
        <button onclick="hidePopupServer()" style="
            display: inline-block;
            padding: 12px 32px;
            border: none; border-radius: 10px;
            background: #E53935; color: #fff;
            font-size: 14.5px; font-weight: 700;
            font-family: inherit; cursor: pointer;
            transition: filter .2s, transform .1s;
        "
        onmouseover="this.style.filter='brightness(1.08)'"
        onmouseout="this.style.filter='brightness(1)'"
        onmousedown="this.style.transform='scale(.97)'"
        onmouseup="this.style.transform='scale(1)'">
            Coba Lagi
        </button>
    </div>
</div>

@push('scripts')
<script>
    function showPopupServer() {
        var el = document.getElementById('popup-server');
        el.style.display = 'flex';
    }
    function hidePopupServer() {
        var el = document.getElementById('popup-server');
        el.style.display = 'none';
    }
    // Tutup saat klik overlay
    document.getElementById('popup-server').addEventListener('click', function(e) {
        if (e.target === this) hidePopupServer();
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